<section class="w-full py-16 bg-surface-subtle" id="produk-pilihan">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="px-3.5 py-1.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-md text-label-md font-bold uppercase tracking-wider">Katalog ISP</span>
                <h2 class="font-headline-lg text-headline-lg text-on-surface mt-2">Produk &amp; Layanan Saka Media</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant">Berkomitmen untuk selalu memberikan layanan internet terbaik bagi Indonesia.</p>
            </div>

            <div class="flex flex-wrap gap-2 p-1.5 rounded-full bg-surface-container">
                @foreach ($categories as $key => $label)
                    <button
                        wire:click="setCategory('{{ $key }}')"
                        @class([
                            'px-4 py-2 rounded-full font-label-md text-label-md transition-colors',
                            'font-bold bg-primary text-on-primary shadow-sm' => $activeCategory === $key,
                            'font-semibold text-on-surface-variant hover:text-on-surface' => $activeCategory !== $key,
                        ])
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch" wire:loading.class="opacity-50">
            @forelse ($this->products as $product)
                <div wire:key="{{ $product->slug }}" class="rounded-3xl bg-surface-container-lowest p-6 shadow-sm hover:shadow-md border {{ $product->is_popular ? 'border-2 border-primary shadow-xl transform md:-translate-y-2' : 'border-outline-variant/30' }} flex flex-col justify-between relative transition-all">

                    @if ($product->is_popular)
                        <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-md text-label-md font-extrabold uppercase tracking-wider shadow-md whitespace-nowrap">PALING POPULER</div>
                    @endif

                    <div class="space-y-4 {{ $product->is_popular ? 'pt-1' : '' }}">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-md text-label-md font-semibold">{{ $product->badge }}</span>
                                <span class="material-symbols-outlined text-primary text-[22px]">{{ $product->icon }}</span>
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-extrabold mt-3">{{ $product->name }}</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $product->description }}</p>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-surface-container-low flex items-center justify-between">
                            <span class="font-label-md text-label-md text-on-surface-variant font-semibold">Kecepatan</span>
                            <div class="flex items-baseline gap-1">
                                <span class="font-badge-speed text-badge-speed text-primary font-extrabold">{{ $product->speed }}</span>
                                <span class="font-title-sm text-title-sm text-on-surface font-bold">{{ $product->unit }}</span>
                            </div>
                        </div>

                        <div class="pt-1">
                            @if ($product->old_price)
                                <div class="flex items-center gap-2">
                                    <span class="font-body-sm text-body-sm text-text-muted line-through">{{ $product->old_price }}</span>
                                    <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-secondary-fixed text-on-secondary-fixed">{{ $product->discount }}</span>
                                </div>
                            @endif
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="font-headline-md text-headline-md text-on-surface font-extrabold">{{ $product->price }}</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">/bulan</span>
                            </div>
                        </div>

                        <ul class="pt-2 space-y-2.5 font-body-sm text-body-sm text-on-surface border-t border-outline-variant/30">
                            @foreach ($product->features as $feature)
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-primary text-[18px] shrink-0 mt-0.5">check_circle</span>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-6 pt-4">
    @auth
        <button
            type="button"
            wire:click="openModal({{ $product->id }}, '{{ addslashes($product->name) }}')"
            class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-full font-label-lg text-label-lg font-bold {{ $product->is_popular ? 'text-on-secondary-fixed bg-secondary-container hover:bg-secondary-fixed shadow-md hover:shadow-lg' : 'text-primary bg-surface-accent hover:bg-surface-container' }} transition-all"
        >
            <span>Pilih Paket</span><span class="material-symbols-outlined text-[16px]">arrow_forward</span>
        </button>
    @else
        <a
            href="{{ route('login') }}"
            class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-full font-label-lg text-label-lg font-bold {{ $product->is_popular ? 'text-on-secondary-fixed bg-secondary-container hover:bg-secondary-fixed shadow-md hover:shadow-lg' : 'text-primary bg-surface-accent hover:bg-surface-container' }} transition-all"
        >
            <span class="material-symbols-outlined text-[16px]"></span><span>Pilih Paket</span>
        </a>
    @endauth
</div>
                </div>
            @empty
                <p class="col-span-full text-center text-on-surface-variant py-10">Belum ada paket di kategori ini.</p>
            @endforelse
        </div>

        <div class="mt-8 pt-4 text-center">
            <p class="font-body-sm text-body-sm text-on-surface-variant">*Harga belum termasuk PPN 11%. Gratis biaya instalasi untuk pembayaran 3 bulan di muka. Syarat &amp; ketentuan berlaku.</p>
        </div>
    </div>

    @if ($showModal)
        <div
            wire:click="closeModal"
            class="fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
        >
            <div
                wire:click.stop
                class="w-full max-w-lg bg-surface-container-lowest rounded-3xl shadow-2xl max-h-[90vh] overflow-y-auto"
            >
                <div class="p-6 lg:p-8">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <span class="text-body-sm font-body-sm text-primary font-semibold uppercase tracking-wider">Formulir Pendaftaran</span>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-extrabold mt-1">{{ $selectedProductName }}</h3>
                        </div>
                        <button
                            type="button"
                            wire:click="closeModal"
                            class="w-9 h-9 rounded-full bg-surface-container hover:bg-surface-container-high flex items-center justify-center text-on-surface-variant shrink-0"
                            aria-label="Tutup"
                        >
                            <span class="material-symbols-outlined text-[20px]">close</span>
                        </button>
                    </div>

                    @if ($subSubmitted)
                        <div class="py-8 text-center space-y-3">
                            <div class="w-14 h-14 rounded-full bg-surface-accent text-primary flex items-center justify-center mx-auto">
                                <span class="material-symbols-outlined text-[32px]">check_circle</span>
                            </div>
                            <h4 class="font-title-md text-title-md text-on-surface font-bold">Pendaftaran Berhasil Dikirim!</h4>
                            <p class="font-body-md text-body-md text-on-surface-variant">Tim kami akan menghubungi Anda untuk jadwal survei &amp; instalasi paket <strong>{{ $selectedProductName }}</strong> dalam 1x24 jam.</p>
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="mt-2 inline-flex items-center gap-2 px-6 py-3 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep transition-all"
                            >
                                Tutup
                            </button>
                        </div>
                    @else
                        <form wire:submit="submitSubscription" class="space-y-4">
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Nama Pelanggan *</label>
<input wire:model="subName" type="text" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="Contoh: Budi Santoso">
                                @error('subName') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Alamat Email *</label>
                                <input wire:model="subEmail" type="email" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="nama@email.com">
                                @error('subEmail') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">No. Telepon / WhatsApp *</label>
                                <input wire:model="subPhone" type="tel" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="0812-xxxx-xxxx">
                                @error('subPhone') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Alamat Lengkap Pemasangan *</label>
                                <textarea wire:model="subAddress" rows="3" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kabupaten..."></textarea>
                                @error('subAddress') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Foto KTP (opsional)</label>

                                @if ($subPhoto)
                                    <div class="relative w-full h-40 rounded-xl overflow-hidden border border-outline-variant/30">
                                        <img src="{{ $subPhoto->temporaryUrl() }}" class="w-full h-full object-cover" alt="Preview foto lokasi">
                                        <button
                                            type="button"
                                            wire:click="removePhoto"
                                            class="absolute top-2 right-2 w-8 h-8 rounded-full bg-black/60 hover:bg-black/80 text-white flex items-center justify-center"
                                            aria-label="Hapus foto"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">close</span>
                                        </button>
                                    </div>
                                @else
                                    <label class="flex flex-col items-center justify-center gap-2 w-full h-32 rounded-xl border-2 border-dashed border-outline-variant/50 bg-surface-container-low cursor-pointer hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[28px] text-on-surface-variant">add_a_photo</span>
                                        <span class="font-body-sm text-body-sm text-on-surface-variant">Klik untuk upload foto lokasi</span>
                                        <input type="file" wire:model="subPhoto" accept="image/*" class="hidden">
                                    </label>
                                @endif

                                <div wire:loading wire:target="subPhoto" class="mt-2 flex items-center gap-2 text-body-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span>
                                    <span>Mengunggah foto...</span>
                                </div>

                                @error('subPhoto') <p class="text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block font-label-md text-label-md text-on-surface font-semibold mb-1">Catatan Tambahan (opsional)</label>
                                <textarea wire:model="subNotes" rows="2" class="w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface outline-none focus:ring-2 focus:ring-primary/20" placeholder="Patokan lokasi, waktu survei yang diinginkan, dll"></textarea>
                            </div>

                            <button
                                type="submit"
                                class="w-full px-6 py-3.5 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2"
                            >
                                <span wire:loading.remove wire:target="submitSubscription" class="material-symbols-outlined text-[20px]">send</span>
                                <span wire:loading wire:target="submitSubscription" class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
                                <span>Daftar &amp; Lanjutkan Pemasangan</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif
</section>