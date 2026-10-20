@php
    $input = 'w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20';
    $label = 'block font-label-md text-label-md text-on-surface font-semibold mb-1';
    $card = 'bg-surface-container-lowest rounded-2xl shadow-sm p-5 sm:p-6';
@endphp

<div class="w-full">
    <a href="{{ route('technician.tasks') }}" class="inline-flex items-center gap-1 font-label-md text-label-md text-primary font-semibold hover:underline mb-4">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>Kembali ke daftar tugas
    </a>

    <div class="mb-6">
        <h1 class="font-headline-lg text-headline-lg text-on-surface font-extrabold">Formulir Pemasangan</h1>
        <p class="font-body-md text-body-md text-on-surface-variant mt-1">
            Aktivasi SAKAMEDIA · {{ now()->translatedFormat('d F Y') }} · {{ $subscription->user?->name ?? '-' }}
        </p>
    </div>

    <form wire:submit="submit" class="space-y-5">

        {{-- Data pelanggan --}}
        <div class="{{ $card }} space-y-4">
            <h2 class="font-title-md text-title-md text-on-surface font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[22px]">person</span>Data Pelanggan
            </h2>

            <div>
                <label class="{{ $label }}">Nama *</label>
                <input wire:model="name" type="text" class="{{ $input }}">
                @error('name') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Alamat *</label>
                <textarea wire:model="address" rows="3" class="{{ $input }}"></textarea>
                @error('address') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="{{ $label }}">No. HP *</label>
                    <input wire:model="phone" type="tel" class="{{ $input }}">
                    @error('phone') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $label }}">Paket *</label>
                    <select wire:model="productId" class="{{ $input }} cursor-pointer">
                        <option value="">— Pilih paket —</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->price }})</option>
                        @endforeach
                    </select>
                    @error('productId') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Tim & jaringan --}}
        <div class="{{ $card }} space-y-4">
            <h2 class="font-title-md text-title-md text-on-surface font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[22px]">lan</span>Tim &amp; Jaringan
            </h2>

            <div>
                <label class="{{ $label }}">Team *</label>
                <input wire:model="team" type="text" class="{{ $input }}" placeholder="Contoh: Zaopal Nofa">
                @error('team') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="{{ $label }}">ODP *</label>
                    <input wire:model="odp" type="text" class="{{ $input }}" placeholder="Contoh: 1">
                    @error('odp') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $label }}">ODP Port *</label>
                    <input wire:model="odpPort" type="text" class="{{ $input }}" placeholder="Contoh: 8">
                    @error('odpPort') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $label }}">ODC *</label>
                    <input wire:model="odc" type="text" class="{{ $input }}" placeholder="Contoh: 4">
                    @error('odc') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

{{-- Modem --}}
@php
    $selectedModem = $this->selectedModem;
@endphp

<div wire:key="modem-section-{{ $selectedModem?->id ?? 'picker' }}" class="{{ $card }} space-y-3">
    <h2 class="font-title-md text-title-md text-on-surface font-bold flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-[22px]">router</span>Modem
        <span class="font-body-sm text-body-sm text-on-surface-variant font-normal">(opsional)</span>
    </h2>

    @if ($selectedModem)
        <div class="flex items-center justify-between gap-3 p-4 rounded-xl bg-surface-accent ring-1 ring-primary/20">
            <div class="min-w-0">
                <p class="font-title-sm text-title-sm text-on-surface font-bold">{{ $selectedModem->modemModel->name }}</p>
                <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">
                    SN: <span class="font-mono font-semibold text-on-surface">{{ $selectedModem->serial_number }}</span>
                    · {{ strtoupper($selectedModem->modemModel->type) }}
                </p>
            </div>
            <button type="button" wire:click="clearModem" class="w-9 h-9 rounded-full bg-white/70 hover:bg-white flex items-center justify-center text-on-surface-variant shrink-0" aria-label="Hapus pilihan modem">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>
        </div>
    @else
        {{-- Scanner barcode SN (dikelola penuh oleh Alpine, jadi dilewati Livewire) --}}
        <div
            wire:ignore
            x-data="{
                open: false,
                busy: false,
                handling: false,
                notice: '',
                scanner: null,
                secure: window.isSecureContext,

                formats() {
                    const f = Html5QrcodeSupportedFormats;
                    return [f.CODE_128, f.CODE_39, f.CODE_93, f.EAN_13, f.ITF, f.QR_CODE];
                },

                loadLib() {
                    if (window.Html5Qrcode) { return Promise.resolve(); }

                    if (!window.__html5qrPromise) {
                        window.__html5qrPromise = new Promise((resolve, reject) => {
                            const s = document.createElement('script');
                            s.src = 'https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js';
                            s.onload = resolve;
                            s.onerror = () => { window.__html5qrPromise = null; reject(new Error('load')); };
                            document.head.appendChild(s);
                        });
                    }

                    return window.__html5qrPromise;
                },

                async openScanner() {
                    this.notice = '';
                    this.handling = false;
                    this.open = true;
                    document.body.classList.add('overflow-hidden');

                    if (!this.secure) { return; }

                    try {
                        await this.loadLib();
                        await this.$nextTick();

                        this.scanner = new Html5Qrcode('sn-reader', {
                            formatsToSupport: this.formats(),
                            experimentalFeatures: { useBarCodeDetectorIfSupported: true },
                            verbose: false
                        });

                        await this.scanner.start(
                            { facingMode: 'environment' },
                            {
                                fps: 10,
                                qrbox: (w, h) => ({ width: Math.floor(w * 0.9), height: Math.floor(Math.min(h, w) * 0.4) })
                            },
                            (text) => this.onDecoded(text),
                            () => {}
                        );
                    } catch (e) {
                        this.notice = 'Kamera tidak bisa dibuka (cek izin kamera) atau scanner gagal dimuat. Gunakan Ambil foto barcode di bawah.';
                    }
                },

                async stopScan() {
                    const s = this.scanner;
                    this.scanner = null;
                    if (!s) { return; }
                    try { await s.stop(); } catch (e) {}
                    try { s.clear(); } catch (e) {}
                },

                async closeScanner() {
                    await this.stopScan();
                    this.open = false;
                    document.body.classList.remove('overflow-hidden');
                },

                async onDecoded(text) {
                    if (this.handling) { return; }
                    this.handling = true;

                    const result = await this.$wire.scanModem(text);

                    if (result && result.ok) {
                        if (navigator.vibrate) { navigator.vibrate(120); }
                        await this.closeScanner();
                        return;
                    }

                    this.notice = (result && result.message) ? result.message : 'Barcode tidak dikenali.';
                    setTimeout(() => { this.handling = false; }, 1500);
                },

                async scanPhoto(event) {
                    const file = event.target.files[0];
                    event.target.value = '';
                    if (!file) { return; }

                    this.busy = true;
                    this.notice = '';

                    try {
                        await this.loadLib();
                        const reader = new Html5Qrcode('sn-file-reader', { formatsToSupport: this.formats(), verbose: false });
                        const text = await reader.scanFile(file, false);
                        this.handling = false;
                        await this.onDecoded(text);
                    } catch (e) {
                        this.notice = 'Barcode tidak terbaca dari foto. Dekatkan kamera ke barcode, pastikan fokus dan cukup cahaya, lalu coba lagi.';
                    }

                    this.busy = false;
                },

                destroy() {
                    document.body.classList.remove('overflow-hidden');
                    this.stopScan();
                }
            }"
        >
            <button type="button" x-on:click="openScanner()" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep transition-all">
                <span class="material-symbols-outlined text-[20px]">qr_code_scanner</span>Scan Barcode SN
            </button>

            {{-- Tempat pembaca foto (tidak terlihat, tapi harus punya ukuran) --}}
            <div id="sn-file-reader" aria-hidden="true" style="position:fixed;left:-9999px;top:0;width:640px;height:480px"></div>

            <div
                x-show="open"
                style="display:none"
                x-on:keydown.escape.window="closeScanner()"
                class="fixed inset-0 z-[70] bg-black/80 flex items-end sm:items-center justify-center sm:p-4"
                role="dialog"
                aria-modal="true"
            >
                <div class="w-full sm:max-w-md bg-surface-container-lowest rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-outline-variant/30">
                        <h3 class="font-title-md text-title-md text-on-surface font-bold">Scan SN Modem</h3>
                        <button type="button" x-on:click="closeScanner()" class="w-9 h-9 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant" aria-label="Tutup">
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    <div class="p-5 space-y-4">
                        <div x-show="secure">
                            <div id="sn-reader" class="w-full min-h-[200px] rounded-xl overflow-hidden bg-black"></div>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-2">
                                Arahkan kamera ke barcode SN di label modem. Scan berjalan otomatis.
                            </p>
                        </div>

                        <div x-show="!secure" style="display:none" class="p-4 rounded-xl bg-amber-50 text-amber-800 font-body-sm text-body-sm">
                            Scan langsung hanya berfungsi di halaman HTTPS. Untuk sementara gunakan tombol Ambil foto barcode di bawah.
                        </div>

                        <p x-show="notice" x-text="notice" style="display:none" class="p-3 rounded-xl bg-rose-50 text-rose-700 font-body-sm text-body-sm"></p>

                        <label
                            class="flex items-center justify-center gap-2 px-5 py-3 rounded-full font-label-lg text-label-lg font-bold text-primary bg-surface-accent hover:bg-surface-container cursor-pointer transition-colors"
                            x-bind:class="busy ? 'opacity-60 pointer-events-none' : ''"
                        >
                            <span class="material-symbols-outlined text-[20px]" x-text="busy ? 'progress_activity' : 'photo_camera'">photo_camera</span>
                            <span x-text="busy ? 'Membaca foto...' : 'Ambil foto barcode'">Ambil foto barcode</span>
                            <input type="file" accept="image/*" capture="environment" class="hidden" x-on:change="scanPhoto($event)">
                        </label>

                        <p class="font-body-sm text-body-sm text-text-muted">
                            Tips: jarak sekitar 10–15 cm, cahaya cukup, dan jaga barcode tetap lurus di dalam bingkai.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 text-on-surface-variant">
            <div class="h-px flex-1 bg-outline-variant/50"></div>
            <span class="font-body-sm text-body-sm">atau cari manual</span>
            <div class="h-px flex-1 bg-outline-variant/50"></div>
        </div>

        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[20px] text-on-surface-variant pointer-events-none">search</span>
            <input wire:model.live.debounce.300ms="modemSearch" type="text" class="{{ $input }} pl-12" placeholder="Cari SN modem...">
        </div>

        @php
            $results = $this->modemResults;
        @endphp

        @if ($results->isNotEmpty())
            <div class="rounded-xl ring-1 ring-outline-variant/30 divide-y divide-outline-variant/30 overflow-hidden">
                @foreach ($results as $unit)
                    <button type="button" wire:key="modem-{{ $unit->id }}" wire:click="selectModem({{ $unit->id }})" class="w-full flex items-center justify-between gap-3 px-4 py-3 text-left hover:bg-surface-container-low transition-colors">
                        <span class="min-w-0">
                            <span class="block font-mono font-semibold text-on-surface truncate">{{ $unit->serial_number }}</span>
                            <span class="block font-body-sm text-body-sm text-on-surface-variant">{{ $unit->modemModel->name }}</span>
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full bg-surface-accent text-primary font-label-md text-label-md font-bold shrink-0">
                            {{ strtoupper($unit->modemModel->type) }}
                        </span>
                    </button>
                @endforeach
            </div>
        @else
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                Tidak ada unit tersedia{{ trim($modemSearch) !== '' ? ' untuk pencarian ini' : '' }}. Hubungi admin untuk menambah stok.
            </p>
        @endif
    @endif

    @error('modemId') <p class="text-error text-body-sm">{{ $message }}</p> @enderror
</div>

        {{-- Material --}}
        <div class="{{ $card }} space-y-3">
            <h2 class="font-title-md text-title-md text-on-surface font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[22px]">inventory_2</span>Material Terpakai
            </h2>

            @foreach ($materials as $i => $item)
                <div wire:key="material-{{ $i }}" class="flex items-center gap-2">
                    <span class="w-6 text-center font-label-md text-label-md text-on-surface-variant shrink-0">{{ $i + 1 }}.</span>
                    <input wire:model="materials.{{ $i }}" type="text" class="{{ $input }}" placeholder="Contoh: Kabel 50 meter">
                    <button type="button" wire:click="removeMaterial({{ $i }})" class="w-10 h-10 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant shrink-0" aria-label="Hapus material">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @endforeach

            @error('materials') <p class="text-error text-body-sm">{{ $message }}</p> @enderror

            <button type="button" wire:click="addMaterial" class="inline-flex items-center gap-1 font-label-md text-label-md text-primary font-bold hover:underline">
                <span class="material-symbols-outlined text-[18px]">add</span>Tambah Material
            </button>
        </div>

        {{-- Titik lokasi --}}
        <div class="{{ $card }} space-y-4">
            <h2 class="font-title-md text-title-md text-on-surface font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[22px]">my_location</span>Titik Lokasi (Tikor) *
            </h2>

            <div
    x-data="{
        loading: false,
        error: '',
        locate() {
            this.error = '';

            if (!window.isSecureContext) {
                this.error = 'GPS diblokir browser karena halaman dibuka lewat HTTP (' + location.host + '). GPS hanya jalan di HTTPS atau localhost. Pakai opsi paste link di bawah, atau buka lewat alamat HTTPS.';
                return;
            }

            if (!navigator.geolocation) {
                this.error = 'Browser ini tidak mendukung GPS. Gunakan opsi paste link di bawah.';
                return;
            }

            this.loading = true;

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.$wire.setGps(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy);
                    this.loading = false;
                },
                (err) => {
                    this.loading = false;

                    if (err.code === 1) {
                        this.error = 'Izin lokasi ditolak untuk situs ini. Ketuk ikon gembok di address bar, pilih Izin, lalu izinkan Lokasi. Kalau tetap gagal, cek Lokasi HP dan izin lokasi aplikasi browser di Pengaturan HP. (kode 1)';
                    } else if (err.code === 2) {
                        this.error = 'Lokasi tidak tersedia. Pastikan Lokasi/GPS HP menyala, lalu coba di tempat terbuka. (kode 2)';
                    } else {
                        this.error = 'Waktu habis saat mencari sinyal GPS. Coba lagi di tempat terbuka. (kode 3)';
                    }
                },
                { enableHighAccuracy: true, timeout: 20000, maximumAge: 0 }
            );
        }
    }"
    class="space-y-2"
>
                <button type="button" x-on:click="locate()" x-bind:disabled="loading" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep disabled:opacity-60 transition-all">
                    <span class="material-symbols-outlined text-[20px]" x-bind:class="loading ? 'animate-spin' : ''" x-text="loading ? 'progress_activity' : 'gps_fixed'">gps_fixed</span>
                    <span x-text="loading ? 'Mengambil lokasi...' : 'Ambil Lokasi Saat Ini (GPS)'">Ambil Lokasi Saat Ini (GPS)</span>
                </button>
                <p x-show="error" x-cloak x-text="error" class="text-error text-body-sm"></p>
            </div>

            <div class="flex items-center gap-3 text-on-surface-variant">
                <div class="h-px flex-1 bg-outline-variant/50"></div>
                <span class="font-body-sm text-body-sm">atau</span>
                <div class="h-px flex-1 bg-outline-variant/50"></div>
            </div>

            <div>
                <label class="{{ $label }}">Paste link Google Maps / ketik koordinat</label>
                <div class="flex flex-col sm:flex-row gap-2">
                    <input wire:model="locationLink" type="text" class="{{ $input }}" placeholder="https://maps.app.goo.gl/... atau -7.08361, 109.31207">
                    <button type="button" wire:click="resolveLink" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full font-label-lg text-label-lg font-bold text-primary bg-surface-accent hover:bg-surface-container whitespace-nowrap transition-colors">
                        <span wire:loading.remove wire:target="resolveLink" class="material-symbols-outlined text-[18px]">link</span>
                        <span wire:loading wire:target="resolveLink" class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>
                        Ambil Koordinat
                    </button>
                </div>
                @error('locationLink') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
            </div>

            @error('latitude') <p class="text-error text-body-sm">{{ $message }}</p> @enderror

            @if ($latitude && $longitude)
                <div class="space-y-3">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-primary text-[22px]">check_circle</span>
                        <div>
                            <p class="font-title-sm text-title-sm text-on-surface font-bold">{{ $latitude }}, {{ $longitude }}</p>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">
                                @if ($locationSource === 'gps')
                                    Sumber: GPS{{ $gpsAccuracy ? ' (akurasi ±' . $gpsAccuracy . ' m)' : '' }}
                                @elseif ($locationSource === 'link')
                                    Sumber: link Google Maps
                                @else
                                    Koordinat tersimpan
                                @endif
                            </p>
                        </div>
                    </div>

                    <div wire:key="map-{{ $latitude }}-{{ $longitude }}" class="w-full h-48 rounded-xl overflow-hidden border border-outline-variant/30">
                        <iframe
                            src="https://www.google.com/maps?q={{ $latitude }},{{ $longitude }}&z=17&output=embed"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Pratinjau titik lokasi"
                        ></iframe>
                    </div>
                </div>
            @endif
        </div>

        {{-- Foto KTP --}}
        <div class="{{ $card }} space-y-3">
            <h2 class="font-title-md text-title-md text-on-surface font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[22px]">badge</span>Foto KTP/Modem {{ $existingKtp ? '' : '*' }}
            </h2>

            @if ($ktpPhoto)
                <div class="relative w-full h-48 rounded-xl overflow-hidden border border-outline-variant/30">
                    <img src="{{ $ktpPhoto->temporaryUrl() }}" class="w-full h-full object-cover" alt="Pratinjau KTP">
                    <button type="button" wire:click="removeKtpPhoto" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-black/60 hover:bg-black/80 text-white flex items-center justify-center" aria-label="Hapus foto">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            @else
                @if ($existingKtp)
                    <p class="flex items-center gap-1.5 font-body-sm text-body-sm text-primary font-semibold">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                         sudah terunggah. Unggah lagi hanya jika ingin menggantinya.
                    </p>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex flex-col items-center justify-center gap-2 h-28 rounded-xl border-2 border-dashed border-primary/40 bg-surface-accent cursor-pointer hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined text-[28px] text-primary">photo_camera</span>
                        <span class="font-label-md text-label-md text-primary font-bold">Ambil Foto</span>
                        <input type="file" wire:model="ktpPhoto" accept="image/*" capture="environment" class="hidden">
                    </label>

                    <label class="flex flex-col items-center justify-center gap-2 h-28 rounded-xl border-2 border-dashed border-outline-variant/50 bg-surface-container-low cursor-pointer hover:bg-surface-container transition-colors">
                        <span class="material-symbols-outlined text-[28px] text-on-surface-variant">photo_library</span>
                        <span class="font-label-md text-label-md text-on-surface-variant font-bold">Pilih dari Galeri</span>
                        <input type="file" wire:model="ktpPhoto" accept="image/*" class="hidden">
                    </label>
                </div>
            @endif

            <div wire:loading wire:target="ktpPhoto" class="flex items-center gap-2 text-body-sm text-on-surface-variant">
                <span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span>
                <span>Mengunggah foto...</span>
            </div>

            @error('ktpPhoto') <p class="text-error text-body-sm">{{ $message }}</p> @enderror

            <p class="font-body-sm text-body-sm text-text-muted">Foto disimpan.</p>
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="submit,ktpPhoto" class="w-full px-6 py-4 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep disabled:opacity-60 shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
            <span wire:loading.remove wire:target="submit" class="material-symbols-outlined text-[20px]">send</span>
            <span wire:loading wire:target="submit" class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
            <span>Kirim Laporan Pemasangan</span>
        </button>
    </form>
</div>