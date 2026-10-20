@php
    $inputClass = 'w-full px-4 py-3 rounded-xl bg-surface-container-low text-on-surface border border-outline-variant/40 outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition';
    $labelClass = 'block font-label-md text-label-md text-on-surface font-semibold mb-1';
@endphp

<section class="w-full py-16 bg-surface-subtle" id="produk-pilihan">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div data-reveal>
                <span class="px-3.5 py-1.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-md text-label-md font-bold uppercase tracking-wider">Katalog ISP</span>
                <h2 class="font-headline-lg text-headline-lg text-on-surface mt-2">Produk &amp; Layanan Saka Media</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant">Berkomitmen untuk selalu memberikan layanan internet terbaik bagi Indonesia.</p>
            </div>

            {{-- Tab kategori: bisa digeser mendatar di HP --}}
            <div class="w-full md:w-auto overflow-x-auto" data-reveal="right" style="--d:150">
                <div class="inline-flex gap-2 p-1.5 rounded-full bg-surface-container whitespace-nowrap">
                    @foreach ($categories as $key => $label)
                        <button
                            type="button"
                            wire:click="setCategory(@js($key))"
                            @class([
                                'px-4 py-2 rounded-full font-label-md text-label-md transition-all duration-300 active:scale-95',
                                'font-bold bg-primary text-on-primary shadow-sm' => $activeCategory === $key,
                                'font-semibold text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high' => $activeCategory !== $key,
                            ])
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Redup hanya saat ganti kategori --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch transition-opacity duration-300" wire:loading.class="opacity-50" wire:target="setCategory">
            @forelse ($this->products as $product)
                @php
                    $btnClass = $product->is_popular
                        ? 'text-on-secondary-fixed bg-secondary-container hover:bg-secondary-fixed shadow-md hover:shadow-lg'
                        : 'text-primary bg-surface-accent hover:bg-surface-container';
                @endphp

                {{-- Wrapper luar = animasi muncul (bertahap), kartu dalam = efek hover --}}
                <div wire:key="{{ $product->slug }}" data-reveal style="--d:{{ ($loop->index % 4) * 120 }}" class="h-full">
                    <div
                        @class([
                            'h-full rounded-3xl bg-surface-container-lowest p-6 flex flex-col justify-between relative transition-all duration-300',
                            'glow-pulse border-2 border-primary shadow-xl md:-translate-y-2 hover:md:-translate-y-3' => $product->is_popular,
                            'border border-outline-variant/30 shadow-sm hover:shadow-xl hover:-translate-y-1.5' => ! $product->is_popular,
                        ])
                    >
                        @if ($product->is_popular)
                            <div class="float-y absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-md text-label-md font-extrabold uppercase tracking-wider shadow-md whitespace-nowrap">PALING POPULER</div>
                        @endif

                        <div @class(['space-y-4', 'pt-1' => $product->is_popular])>
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 rounded-full bg-surface-container text-on-surface-variant font-label-md text-label-md font-semibold">{{ $product->badge }}</span>
                                    <span class="material-symbols-outlined text-primary text-[22px]">{{ $product->icon }}</span>
                                </div>
                                <h3 class="font-headline-sm text-headline-sm text-on-surface font-extrabold mt-3">{{ $product->name }}</h3>
                                <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">{{ $product->description }}</p>
                            </div>

                            {{-- Kotak kecepatan: gradien untuk paket populer --}}
                            <div @class([
                                'p-3.5 rounded-2xl flex items-center justify-between',
                                'bg-gradient-to-r from-violet-deep to-primary' => $product->is_popular,
                                'bg-surface-container-low' => ! $product->is_popular,
                            ])>
                                <span @class([
                                    'font-label-md text-label-md font-semibold',
                                    'text-surface-container-highest' => $product->is_popular,
                                    'text-on-surface-variant' => ! $product->is_popular,
                                ])>Kecepatan</span>
                                <div class="flex items-baseline gap-1">
                                    <span @class([
                                        'font-badge-speed text-badge-speed font-extrabold',
                                        'text-secondary-fixed' => $product->is_popular,
                                        'text-primary' => ! $product->is_popular,
                                    ])>{{ $product->speed }}</span>
                                    <span @class([
                                        'font-title-sm text-title-sm font-bold',
                                        'text-on-primary' => $product->is_popular,
                                        'text-on-surface' => ! $product->is_popular,
                                    ])>{{ $product->unit }}</span>
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
                                    wire:click="openModal({{ $product->id }}, @js($product->name))"
                                    class="group w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-full font-label-lg text-label-lg font-bold {{ $btnClass }} active:scale-95 transition-all duration-300"
                                >
                                    <span>Pilih Paket</span><span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                                </button>
                            @else
                                <a
                                    href="{{ route('login') }}"
                                    class="group w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-full font-label-lg text-label-lg font-bold {{ $btnClass }} active:scale-95 transition-all duration-300"
                                >
                                    <span>Pilih Paket</span><span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform duration-300">arrow_forward</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-on-surface-variant py-10">Belum ada paket di kategori ini.</p>
            @endforelse
        </div>

        <div class="mt-8 pt-4 text-center" data-reveal>
            {{-- <p class="font-body-sm text-body-sm text-on-surface-variant">*Harga belum termasuk PPN 11%. Gratis biaya instalasi untuk pembayaran 3 bulan di muka. Syarat &amp; ketentuan berlaku.</p> --}}
        </div>
    </div>

    {{-- Modal pendaftaran --}}
    @if ($showModal)
        <div
            x-data="{
                init() { document.body.classList.add('overflow-hidden') },
                destroy() { document.body.classList.remove('overflow-hidden') },
            }"
            x-on:keydown.escape.window="$wire.closeModal()"
            wire:click="closeModal"
            class="modal-fade fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm flex items-end sm:items-center justify-center sm:p-4"
            role="dialog"
            aria-modal="true"
            aria-labelledby="modal-title"
        >
            <div
                wire:click.stop
                class="modal-pop w-full sm:max-w-lg bg-surface-container-lowest rounded-t-3xl sm:rounded-3xl shadow-2xl max-h-[92vh] overflow-y-auto"
            >
                {{-- Pegangan geser (hanya di HP) --}}
                <div class="sm:hidden mx-auto mt-3 h-1.5 w-12 rounded-full bg-outline-variant/60"></div>

                {{-- Header modal: tetap terlihat saat form discroll --}}
                <div class="sticky top-0 z-10 bg-surface-container-lowest px-6 pt-4 sm:pt-5 pb-4 border-b border-outline-variant/30 flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <span class="text-body-sm font-body-sm text-primary font-semibold uppercase tracking-wider">Formulir Pendaftaran</span>
                        <h3 id="modal-title" class="font-headline-sm text-headline-sm text-on-surface font-extrabold mt-0.5 truncate">{{ $selectedProductName }}</h3>
                    </div>
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="w-9 h-9 rounded-full bg-surface-container hover:bg-surface-container-high hover:rotate-90 transition-all duration-300 flex items-center justify-center text-on-surface-variant shrink-0"
                        aria-label="Tutup"
                    >
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                </div>

                <div class="p-6">
                    @if ($subSubmitted)
                        <div class="pop-in py-6 text-center space-y-3">
                            <div class="w-14 h-14 rounded-full bg-surface-accent text-primary flex items-center justify-center mx-auto">
                                <span class="material-symbols-outlined text-[32px]">check_circle</span>
                            </div>
                            <h4 class="font-title-md text-title-md text-on-surface font-bold">Pendaftaran Berhasil Dikirim!</h4>
                            <p class="font-body-md text-body-md text-on-surface-variant">Tim kami akan menghubungi Anda untuk jadwal survei &amp; instalasi paket <strong>{{ $selectedProductName }}</strong> dalam 1x24 jam.</p>
                            <button
                                type="button"
                                wire:click="closeModal"
                                class="mt-2 inline-flex items-center gap-2 px-6 py-3 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep active:scale-95 transition-all duration-300"
                            >
                                Tutup
                            </button>
                        </div>
                    @else
                        <form wire:submit="submitSubscription" class="space-y-4">
                            <div>
                                <label class="{{ $labelClass }}">Nama Pelanggan *</label>
                                <input wire:model="subName" type="text" class="{{ $inputClass }}" placeholder="Contoh: Budi Santoso">
                                @error('subName') <p class="pop-in text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">No. Telepon / WhatsApp *</label>
                                <input wire:model="subPhone" type="tel" class="{{ $inputClass }}" placeholder="0812-xxxx-xxxx">
                                @error('subPhone') <p class="pop-in text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">Alamat Lengkap Pemasangan *</label>
                                <textarea wire:model="subAddress" rows="3" class="{{ $inputClass }}" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kabupaten..."></textarea>
                                @error('subAddress') <p class="pop-in text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">Foto KTP/Modem (opsional)</label>

                                @if ($subPhoto)
                                    <div class="pop-in relative w-full h-40 rounded-xl overflow-hidden border border-outline-variant/30">
                                        <img src="{{ $subPhoto->temporaryUrl() }}" class="w-full h-full object-cover" alt="Pratinjau foto">
                                        <button
                                            type="button"
                                            wire:click="removePhoto"
                                            class="absolute top-2 right-2 w-8 h-8 rounded-full bg-black/60 hover:bg-black/80 text-white flex items-center justify-center transition-colors"
                                            aria-label="Hapus foto"
                                        >
                                            <span class="material-symbols-outlined text-[18px]">close</span>
                                        </button>
                                    </div>
                                @else
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="flex flex-col items-center justify-center gap-2 h-28 rounded-xl border-2 border-dashed border-primary/40 bg-surface-accent cursor-pointer hover:bg-surface-container hover:border-primary hover:-translate-y-0.5 transition-all duration-300">
                                            <span class="material-symbols-outlined text-[28px] text-primary">photo_camera</span>
                                            <span class="font-label-md text-label-md text-primary font-bold">Ambil Foto</span>
                                            <input type="file" wire:model="subPhoto" accept="image/*" capture="environment" class="hidden">
                                        </label>

                                        <label class="flex flex-col items-center justify-center gap-2 h-28 rounded-xl border-2 border-dashed border-outline-variant/50 bg-surface-container-low cursor-pointer hover:bg-surface-container hover:-translate-y-0.5 transition-all duration-300">
                                            <span class="material-symbols-outlined text-[28px] text-on-surface-variant">photo_library</span>
                                            <span class="font-label-md text-label-md text-on-surface-variant font-bold">Pilih dari Galeri</span>
                                            <input type="file" wire:model="subPhoto" accept="image/*" class="hidden">
                                        </label>
                                    </div>
                                @endif

                                <div wire:loading wire:target="subPhoto" class="mt-2 flex items-center gap-2 text-body-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[16px] animate-spin">progress_activity</span>
                                    <span>Mengunggah foto...</span>
                                </div>

                                @error('subPhoto') <p class="pop-in text-error text-body-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="{{ $labelClass }}">Catatan Tambahan (opsional)</label>
                                <textarea wire:model="subNotes" rows="2" class="{{ $inputClass }}" placeholder="Patokan lokasi, waktu survei yang diinginkan, dll"></textarea>
                            </div>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:target="submitSubscription,subPhoto"
                                class="w-full px-6 py-3.5 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-primary hover:bg-violet-deep hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-60 shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2"
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