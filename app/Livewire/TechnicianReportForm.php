<?php

namespace App\Livewire;

use App\Models\Modem;
use App\Models\Product;
use App\Models\Subscription;
use App\Services\MapsLinkParser;
use App\Services\TelegramNotifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use  Illuminate\Support\Str;

class TechnicianReportForm extends Component
{
    use WithFileUploads;

    public Subscription $subscription;

    // Data pelanggan (boleh dikoreksi teknisi)
    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public $productId = null;

    // Tim & jaringan
    public string $team = '';
    public string $odp = '';
    public string $odpPort = '';
    public string $odc = '';

    // Modem (opsional, dipilih dari stok)
    public ?int $modemId = null;
    public string $modemSearch = '';

    // Material (opsional)
    public array $materials = [''];

    // Titik lokasi
    public ?string $latitude = null;
    public ?string $longitude = null;
    public string $locationLink = '';
    public ?string $locationSource = null;
    public ?string $gpsAccuracy = null;

    // Foto KTP
    public $ktpPhoto = null;
    public ?string $existingKtp = null;

    public function mount(Subscription $subscription)
    {
        $this->subscription = $subscription;
        $this->authorizeTask();

        $this->name = $subscription->name;
        $this->phone = $subscription->phone;
        $this->address = $subscription->installation_address;
        $this->productId = $subscription->product_id;

        $this->team = $subscription->team ?? auth()->user()->name;
        $this->odp = $subscription->odp ?? '';
        $this->odpPort = $subscription->odp_port ?? '';
        $this->odc = $subscription->odc ?? '';

        $this->modemId = $subscription->modem?->id;

        $this->materials = ! empty($subscription->materials)
            ? array_values($subscription->materials)
            : [''];

        if ($subscription->latitude !== null && $subscription->longitude !== null) {
            $this->latitude = (string) (float) $subscription->latitude;
            $this->longitude = (string) (float) $subscription->longitude;
        }

        $this->locationSource = $subscription->location_source;
        $this->locationLink = $subscription->location_link ?? '';
        $this->existingKtp = $subscription->ktp_photo;
    }

    // Hanya teknisi yang ditugaskan, dan tugas belum ditutup admin
    private function authorizeTask(): void
    {
        abort_unless(
            auth()->check()
                && auth()->user()->isTechnician()
                && $this->subscription->technician_id === auth()->id(),
            403
        );

        abort_unless(
            in_array($this->subscription->status, ['dijadwalkan', 'disurvei'], true),
            403,
            'Tugas ini sudah ditutup.'
        );
    }

    protected function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'phone' => 'required|min:9|max:20',
            'address' => 'required|min:10',
            'productId' => 'required|exists:products,id',
            'team' => 'required|max:255',
            'odp' => 'nullable|max:50',
            'odpPort' => 'nullable|max:50',
            'odc' => 'nullable|max:50',
            'modemId' => 'nullable|integer',
            'materials' => 'nullable|array',
            'materials.*' => 'nullable|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'ktpPhoto' => ($this->existingKtp ? 'nullable' : 'required') . '|image|max:5120',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Nama pelanggan wajib diisi.',
            'name.min' => 'Nama terlalu pendek.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.min' => 'Nomor HP terlalu pendek.',
            'address.required' => 'Alamat wajib diisi.',
            'address.min' => 'Mohon tulis alamat lebih lengkap.',
            'productId.required' => 'Pilih paket yang dipasang.',
            'productId.exists' => 'Paket tidak valid.',
            'team.required' => 'Nama tim wajib diisi.',
            'materials.*.max' => 'Nama material terlalu panjang.',
            'latitude.required' => 'Titik lokasi (tikor) wajib diisi. Gunakan tombol GPS atau paste link.',
            'latitude.numeric' => 'Koordinat tidak valid.',
            'latitude.between' => 'Koordinat tidak valid.',
            'longitude.required' => 'Titik lokasi (tikor) wajib diisi.',
            'longitude.numeric' => 'Koordinat tidak valid.',
            'longitude.between' => 'Koordinat tidak valid.',
            'ktpPhoto.required' => 'Foto KTP wajib diunggah.',
            'ktpPhoto.image' => 'File harus berupa gambar (jpg, png, dll).',
            'ktpPhoto.max' => 'Ukuran foto maksimal 5MB.',
        ];
    }

    // Unit yang boleh dipilih: yang tersedia, atau yang sudah milik pendaftaran ini
    private function selectableModems()
    {
        return Modem::with('modemModel')->where(function ($query) {
            $query->where('status', 'tersedia')
                ->orWhere('subscription_id', $this->subscription->id);
        });
    }

    public function getSelectedModemProperty(): ?Modem
    {
        return $this->modemId
            ? Modem::with('modemModel')->find($this->modemId)
            : null;
    }

    public function getModemResultsProperty()
    {
        $term = trim($this->modemSearch);

        return $this->selectableModems()
            ->when($term !== '', function ($query) use ($term) {
                $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $term) . '%';
                $query->where('serial_number', 'like', $like);
            })
            ->orderBy('serial_number')
            ->limit(10)
            ->get();
    }

    public function selectModem(int $id): void
    {
        if (! $this->selectableModems()->whereKey($id)->exists()) {
            $this->addError('modemId', 'Unit ini sudah tidak tersedia. Pilih unit lain.');

            return;
        }

        $this->modemId = $id;
        $this->modemSearch = '';
        $this->resetErrorBag('modemId');
    }

    public function clearModem(): void
    {
        $this->modemId = null;
    }

    // Dipanggil dari scanner barcode (JavaScript). Mengembalikan hasil supaya scanner tahu lanjut atau berhenti.
public function scanModem(string $code): array
{
    $code = Str::upper(preg_replace('/\s+/', '', trim($code)));

    if ($code === '') {
        return ['ok' => false, 'message' => 'Barcode kosong, coba scan lagi.'];
    }

    // Kandidat: kode apa adanya, dan kode tanpa awalan umum seperti "SN:" atau "S/N"
    $candidates = array_values(array_unique(array_filter([
        $code,
        preg_replace('/^(S\/N|SN|SERIALNUMBER|SERIAL)[:#\-]?/', '', $code),
    ])));

    // 1) Cocok persis dengan SN di stok
    $modem = Modem::with('modemModel')->whereIn('serial_number', $candidates)->first();

    // 2) Hasil scan memuat SN (misalnya ada awalan atau akhiran tambahan)
    if (! $modem && strlen($code) >= 6) {
        $matches = Modem::with('modemModel')
            ->whereRaw("? LIKE CONCAT('%', serial_number, '%')", [$code])
            ->limit(2)
            ->get();

        if ($matches->count() === 1) {
            $modem = $matches->first();
        }
    }

    if (! $modem) {
        return [
            'ok' => false,
            'message' => 'Terbaca ' . $code . ', tapi tidak ada di stok. Arahkan ke barcode SN (bukan MAC). Kalau memang belum terdaftar, hubungi admin.',
        ];
    }

    $ownedByThisTask = (int) $modem->subscription_id === (int) $this->subscription->id;

    if ($modem->status !== 'tersedia' && ! $ownedByThisTask) {
        $reason = $modem->status === 'terpasang'
            ? 'sudah terpasang di pelanggan lain'
            : 'berstatus rusak/retur';

        return [
            'ok' => false,
            'message' => 'SN ' . $modem->serial_number . ' ' . $reason . '. Pilih unit lain.',
        ];
    }

    $this->modemId = $modem->id;
    $this->modemSearch = '';
    $this->resetErrorBag('modemId');

    return ['ok' => true, 'message' => 'Berhasil'];
}

    // Dipanggil dari tombol GPS (JavaScript)
    public function setGps(float $lat, float $lng, ?float $accuracy = null): void
    {
        $this->latitude = number_format($lat, 7, '.', '');
        $this->longitude = number_format($lng, 7, '.', '');
        $this->locationSource = 'gps';
        $this->locationLink = '';
        $this->gpsAccuracy = $accuracy !== null ? (string) round($accuracy) : null;
        $this->resetErrorBag(['latitude', 'longitude', 'locationLink']);
    }

    // Dipanggil dari tombol "Ambil koordinat dari link"
    public function resolveLink(): void
    {
        $this->resetErrorBag(['locationLink', 'latitude', 'longitude']);

        $coords = MapsLinkParser::parse($this->locationLink);

        if (! $coords) {
            $this->addError(
                'locationLink',
                'Koordinat tidak ditemukan. Pastikan link dari Google Maps (link pendek juga boleh), atau ketik koordinat seperti: -7.08361, 109.31207'
            );

            return;
        }

        $this->latitude = number_format($coords[0], 7, '.', '');
        $this->longitude = number_format($coords[1], 7, '.', '');
        $this->locationSource = 'link';
        $this->gpsAccuracy = null;
    }

    public function addMaterial(): void
    {
        $this->materials[] = '';
    }

    public function removeMaterial(int $index): void
    {
        unset($this->materials[$index]);
        $this->materials = array_values($this->materials);

        if (empty($this->materials)) {
            $this->materials = [''];
        }
    }

    public function removeKtpPhoto(): void
    {
        $this->ktpPhoto = null;
    }

    // Pasang unit modem ke pelanggan ini. Return false kalau unit baru saja diambil orang lain.
    private function assignModem(): bool
    {
        try {
            DB::transaction(function () {
                $modem = null;

                if ($this->modemId) {
                    // Kunci baris supaya dua teknisi tidak bisa mengambil unit yang sama
                    $modem = Modem::whereKey($this->modemId)->lockForUpdate()->first();

                    $available = $modem
                        && ($modem->status === 'tersedia'
                            || (int) $modem->subscription_id === (int) $this->subscription->id);

                    if (! $available) {
                        throw new \DomainException('Unit modem tidak tersedia.');
                    }
                }

                // Lepas unit lama milik pelanggan ini kalau diganti atau dikosongkan
                Modem::where('subscription_id', $this->subscription->id)
                    ->where('status', 'terpasang')
                    ->when($modem, fn ($query) => $query->where('id', '!=', $modem->id))
                    ->update([
                        'status' => 'tersedia',
                        'subscription_id' => null,
                        'installed_at' => null,
                    ]);

                if ($modem) {
                    $modem->update([
                        'status' => 'terpasang',
                        'subscription_id' => $this->subscription->id,
                        'installed_at' => $modem->installed_at ?? now(),
                    ]);
                }
            });
        } catch (\DomainException $e) {
            $this->modemId = null;
            $this->addError('modemId', 'Unit modem yang dipilih baru saja dipakai orang lain. Silakan pilih unit lain.');

            return false;
        }

        return true;
    }

    public function submit()
    {
        $this->authorizeTask();

        $this->validate();

        // Material opsional: buang baris kosong, kalau tidak ada yang terisi simpan null
        $materials = collect($this->materials)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();

        $product = Product::findOrFail($this->productId);
        $isRevision = $this->subscription->surveyed_at !== null;

        // Pasang modem lebih dulu (kalau dipilih)
        if (! $this->assignModem()) {
            return;
        }

                // KTP disimpan di disk public
        $ktpPath = $this->existingKtp;

        if ($this->ktpPhoto) {
            $ktpPath = $this->ktpPhoto->store('ktp', 'public');

            if ($this->existingKtp) {
                Storage::disk('public')->delete($this->existingKtp);
            }
        }

        $this->subscription->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'installation_address' => $this->address,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'team' => $this->team,
            'odp' => $this->odp ?: null,
            'odp_port' => $this->odpPort ?: null,
            'odc' => $this->odc ?: null,
            'materials' => $materials ?: null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'location_source' => $this->locationSource,
            'location_link' => $this->locationSource === 'link' ? $this->locationLink : null,
            'ktp_photo' => $ktpPath,
            'status' => 'disurvei',
            'surveyed_at' => $this->subscription->surveyed_at ?? now(),
        ]);

        TelegramNotifier::send($this->buildReport($product, $isRevision));

        session()->flash('success', $isRevision
            ? 'Laporan berhasil diperbarui dan dikirim ulang.'
            : 'Laporan pemasangan berhasil dikirim.');

        return $this->redirect(route('technician.tasks'));
    }

    // Susun laporan sesuai template formulir pemasangan
    private function buildReport(Product $product, bool $isRevision): string
    {
        $s = $this->subscription->fresh(['user', 'modem.modemModel']);
        $line = str_repeat('=', 26);

        $materialLines = collect($s->materials ?? [])
            ->map(fn ($item, $i) => ($i + 1) . '. ' . e($item))
            ->implode("\n") ?: '-';

        $modem = $s->modem;
        $modemName = $modem?->modemModel
            ? $modem->modemModel->name . ' (' . strtoupper($modem->modemModel->type) . ')'
            : '-';

        $report = ($isRevision ? 'Formulir pemasangan (REVISI)' : 'Formulir pemasangan Baru') . "\n"
            . $line . "\n"
            . "Aktivasi SAKAMEDIA\n"
            . 'DATE : ' . now()->translatedFormat('d F Y') . "\n"
            . 'TEAM : ' . e($s->team) . "\n"
            . $line . "\n"
            . 'Nama : ' . e($s->name) . "\n\n"
            . 'Alamat : ' . e($s->installation_address) . "\n"
            . 'Paket : ' . e($product->name) . ' (' . e($product->price) . ")\n"
            . 'No. HP : ' . e($s->phone) . "\n"
            . 'Sales : ' . e($s->user?->name ?? '-') . "\n\n"
            . 'ODP : ' . e($s->odp ?: '-') . "\n"
            . 'ODP PORT : ' . e($s->odp_port ?: '-') . "\n"
            . 'ODC : ' . e($s->odc ?: '-') . "\n"
            . 'MODEM : ' . e($modemName) . "\n"
            . 'SN : ' . e($modem?->serial_number ?? '-') . "\n\n"
            . "MATERIAL\n"
            . $materialLines . "\n"
            . $line . "\n"
            . 'Tikor : ' . (float) $s->latitude . ', ' . (float) $s->longitude;

        return '<pre>' . $report . '</pre>' . "\n"
            . '📍 ' . $s->maps_url . "\n"
            . '🧑‍🔧 Dilaporkan oleh: ' . e(auth()->user()->name);
    }

    public function render()
    {
        return view('livewire.technician-report-form', [
            'products' => Product::orderBy('sort_order')->get(['id', 'name', 'price']),
        ]);
    }
}