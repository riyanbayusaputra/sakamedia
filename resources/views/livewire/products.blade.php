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
                        <a href="#hubungi-kami" class="w-full inline-flex items-center justify-center gap-2 py-3 px-4 rounded-full font-label-lg text-label-lg font-bold {{ $product->is_popular ? 'text-on-secondary-fixed bg-secondary-container hover:bg-secondary-fixed shadow-md hover:shadow-lg' : 'text-primary bg-surface-accent hover:bg-surface-container' }} transition-all">
                            <span>Pilih Paket</span><span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
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
</section>