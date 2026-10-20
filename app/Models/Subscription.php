<?php

namespace App\Models;

use App\Services\TelegramNotifier;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'technician_id',
        'survey_date',
        'team',
        'odp',
        'odp_port',
        'odc',
        'materials',
        'latitude',
        'longitude',
        'location_source',
        'location_link',
        'ktp_photo',
        'surveyed_at',
        'activated_at',
        'product_id',
        'product_name',
        'name',
        'email',
        'phone',
        'installation_address',
        'photo',
        'notes',
        'status',
    ];

    protected $casts = [
        'survey_date' => 'date',
        'surveyed_at' => 'datetime',
        'activated_at' => 'datetime',
        'materials' => 'array',
    ];

    protected static function booted(): void
    {
        // Tanggal aktivasi: terisi otomatis saat Terpasang, dikosongkan kalau status keluar dari Terpasang
        static::saving(function (Subscription $subscription) {
            if ($subscription->status === 'terpasang') {
                if ($subscription->activated_at === null) {
                    $subscription->activated_at = now();
                }
            } elseif ($subscription->isDirty('status')) {
                $subscription->activated_at = null;
            }
        });

        // Pendaftaran dihapus: unit modem kembali ke stok
        static::deleting(fn (Subscription $subscription) => $subscription->releaseModem());

        static::updated(function (Subscription $subscription) {
            if (! $subscription->wasChanged('status')) {
                return;
            }

            // Di event "updated", getOriginal() masih berisi status sebelum diubah
            $from = $subscription->getOriginal('status');

            // Dibatalkan: unit modem kembali ke stok
            if ($subscription->status === 'dibatalkan') {
                $subscription->releaseModem();
            }

            // Pending atau Dijadwalkan: kabari lewat Telegram
            if (in_array($subscription->status, ['pending', 'dijadwalkan'], true)) {
                $subscription->notifyStatusChange($from);
            }
        });
    }

    public function releaseModem(): void
    {
        Modem::where('subscription_id', $this->id)
            ->where('status', 'terpasang')
            ->update([
                'status' => 'tersedia',
                'subscription_id' => null,
                'installed_at' => null,
            ]);
    }

    // Kirim notifikasi Telegram saat status berubah menjadi Pending / Dijadwalkan
    protected function notifyStatusChange(?string $from): void
    {
        $this->loadMissing(['technician', 'user']);

        $technician = $this->technician?->name ?? 'Belum ditugaskan';
        $date = $this->survey_date?->translatedFormat('d F Y') ?? 'Belum ditentukan';
        $actor = auth()->user()?->name ?? '-';

        $details = '👤 Pelanggan: ' . e($this->name) . "\n"
            . '📦 Paket: ' . e($this->product_name) . "\n"
            . '📱 Telepon: ' . e($this->phone) . "\n"
            . '📍 Alamat: ' . e($this->installation_address) . "\n"
            . '🧑‍💼 Sales: ' . e($this->user?->name ?? '-') . "\n"
            . '🧑‍🔧 Teknisi: ' . e($technician) . "\n";

        if ($this->status === 'pending') {
            $message = "⏸️ <b>Pendaftaran PENDING</b>\n\n"
                . $details
                . "ℹ️ Menunggu konfirmasi pelanggan sebelum dijadwalkan.\n";
        } else {
            $message = "📅 <b>Survei DIJADWALKAN</b>\n\n"
                . $details
                . '🗓️ Tanggal survei: ' . $date . "\n"
                . ($from === 'pending' ? "✅ Pelanggan sudah konfirmasi.\n" : '');
        }

        $message .= '✏️ Diubah oleh: ' . e($actor);

        // Dikirim setelah respons ke browser, jadi admin tidak menunggu Telegram
        dispatch(fn () => TelegramNotifier::send($message))->afterResponse();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Sales yang menginput
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Teknisi yang ditugaskan survei
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    // Unit modem yang terpasang di pelanggan ini
    public function modem()
    {
        return $this->hasOne(Modem::class);
    }

    // Link Google Maps dari koordinat (null kalau belum ada)
    public function getMapsUrlAttribute(): ?string
    {
        if ($this->latitude === null || $this->longitude === null) {
            return null;
        }

        return 'https://www.google.com/maps?q=' . (float) $this->latitude . ',' . (float) $this->longitude;
    }
}