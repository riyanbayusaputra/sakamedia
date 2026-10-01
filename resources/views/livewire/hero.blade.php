<section
    x-data="{
        slides: @js($this->slides),
        active: 0,
        timer: null,
        start() {
            this.stop();
            this.timer = setInterval(() => this.next(), 5000);
        },
        stop() {
            clearInterval(this.timer);
        },
        next() {
            this.active = (this.active + 1) % this.slides.length;
        },
        prev() {
            this.active = (this.active - 1 + this.slides.length) % this.slides.length;
        },
        goTo(index) {
            this.active = index;
            this.start();
        },
    }"
    x-init="start()"
    class="relative w-full overflow-hidden bg-[#0A1433] text-on-primary"
>
    <div class="absolute inset-0 bg-gradient-to-r from-[#0B1A45] via-[#1E3A8A]/80 to-[#060D24] pointer-events-none z-0"></div>
    <div class="absolute -top-32 -left-32 w-[550px] h-[550px] rounded-full bg-cyan-speed/15 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-10 w-[600px] h-[600px] rounded-full bg-[#3B82F6]/20 blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-10 lg:py-16 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            {{-- Kolom kiri: teks --}}
            <div class="lg:col-span-6 space-y-6">
                <div class="flex flex-wrap items-center gap-2.5">
                    {{-- <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-surface-container-lowest/15 backdrop-blur-md text-secondary-fixed text-label-md font-label-md font-bold tracking-wider uppercase border border-secondary-fixed/30">
                        <span class="material-symbols-outlined text-secondary-fixed text-[18px] animate-pulse">bolt</span><span>PROMO SPESIAL SAKA MEDIA</span>
                    </span> --}}
                    {{-- <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container font-label-md text-label-md font-extrabold shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">local_offer</span><span>DISKON S/D 40% &amp; FREE INSTALASI</span>
                    </span> --}}
                </div>

                <div class="space-y-2">
                    <h1 class="font-display-xl text-display-xl tracking-tight leading-none text-surface-container-lowest">
                        SAKA MEDIA<br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary-fixed via-secondary-container to-cyan-speed">CONNECTING</span> EVERYONE
                    </h1>
                    <p class="font-title-md text-title-md text-secondary-fixed font-semibold">Internet Fiber Ultra Cepat, Simetris 1:1 Tanpa Batas FUP untuk Bisnis &amp; Komunitas</p>
                    <p class="font-body-md text-body-md text-surface-container-highest max-w-xl leading-relaxed">Nikmati internet pita lebar berkecepatan gigabit dengan jalur transmisi serat optik murni dari Saka Media yang didukung lisensi resmi Kominfo dan latensi ultra rendah untuk gaming, streaming, dan produktivitas usaha.</p>
                </div>

                <div class="inline-flex items-center gap-4 p-3 pr-6 rounded-2xl bg-surface-container-lowest/10 backdrop-blur-md border border-surface-container-lowest/15 shadow-inner">
                    <div class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-secondary-container to-secondary-fixed text-on-secondary-fixed font-bold text-center">
                        <span class="block text-[10px] uppercase tracking-wider font-extrabold text-on-secondary-fixed-variant">Mulai Dari</span>
                        <span class="font-headline-sm text-headline-sm font-extrabold leading-none">Rp 100.000</span>
                        <span class="text-[11px] font-medium">/bulan</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 text-cyan-speed font-bold text-label-lg">
                            <span class="material-symbols-outlined text-[18px]">speed</span><span>Kecepatan Hingga 1 Gbps</span>
                        </div>
                        <p class="text-body-sm text-surface-container-highest">Termasuk Wi-Fi 6 Router &amp; Support Prioritas 24 Jam</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-1">
                    <a class="inline-flex items-center gap-2 px-8 py-4 rounded-full font-label-lg text-label-lg font-extrabold text-on-secondary-fixed bg-secondary-container hover:bg-secondary-fixed shadow-[0_4px_20px_rgba(254,198,92,0.4)] hover:shadow-xl transition-all transform hover:-translate-y-0.5" href="#produk-pilihan">
                        <span class="material-symbols-outlined text-[22px]">bolt</span><span>Berlangganan Sekarang</span>
                    </a>
                    {{-- <a class="inline-flex items-center gap-2 px-6 py-4 rounded-full font-label-lg text-label-lg font-bold text-on-primary bg-surface-container-lowest/15 hover:bg-surface-container-lowest/25 backdrop-blur-md border border-surface-container-lowest/20 transition-all" href="#cakupan">
                        <span class="material-symbols-outlined text-[20px]">verified</span><span>Lihat Promo Bulan Ini</span>
                    </a> --}}
                </div>
            </div>

            {{-- Kolom kanan: kartu slider --}}
            <div class="lg:col-span-6 relative" @mouseenter="stop()" @mouseleave="start()">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-2 border-surface-container-lowest/20 bg-[#0F1D4D]/90 backdrop-blur-md p-3 group">

                    {{-- Area gambar slide (pembungkus ini yang memberi tinggi kartu) --}}
                    <div class="relative w-full aspect-[16/10] rounded-2xl overflow-hidden">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div
                                x-show="active === index"
                                x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                class="absolute inset-0 flex items-end bg-gradient-to-br from-violet-deep to-primary"
                                :style="slide.image ? `background-image:url('${slide.image}'); background-size:cover; background-position:center;` : ''"
                            >
                                <a :href="slide.link" class="absolute inset-0 z-10" :aria-label="slide.title"></a>
                                <div class="absolute inset-0 bg-gradient-to-t from-[#0A1433]/90 via-transparent to-transparent flex items-end p-5 w-full pointer-events-none">
                                    <div class="flex items-center justify-between w-full">
                                        <div>
                                            <span class="inline-block px-2.5 py-0.5 rounded bg-secondary-container text-on-secondary-container font-label-md text-label-md font-bold mb-1" x-text="slide.badge"></span>
                                            <p class="font-title-sm text-title-sm text-surface-container-lowest font-bold" x-text="slide.title"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Kontrol: titik indikator + tombol panah --}}
                    <div class="pt-4 pb-2 px-3 flex items-center justify-between text-on-primary">
                        <div class="flex items-center gap-2">
                            <template x-for="(slide, index) in slides" :key="'dot-' + index">
                                <button
                                    @click="goTo(index)"
                                    :aria-label="'Ke banner ' + (index + 1)"
                                    class="h-2.5 rounded-full transition-all cursor-pointer"
                                    :class="active === index ? 'w-7 bg-secondary-container' : 'w-2.5 bg-surface-container-lowest/40 hover:bg-surface-container-lowest/70'"
                                ></button>
                            </template>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                @click="prev(); start();"
                                aria-label="Banner Sebelumnya"
                                class="w-8 h-8 rounded-full bg-surface-container-lowest/15 hover:bg-surface-container-lowest/30 flex items-center justify-center text-surface-container-lowest transition-colors"
                            >
                                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                            </button>
                            <button
                                @click="next(); start();"
                                aria-label="Banner Selanjutnya"
                                class="w-8 h-8 rounded-full bg-surface-container-lowest/15 hover:bg-surface-container-lowest/30 flex items-center justify-center text-surface-container-lowest transition-colors"
                            >
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Baris badge keunggulan --}}
        <div class="mt-10 pt-6 border-t border-surface-container-lowest/15 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 text-center sm:text-left">
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-surface-container-lowest/5 backdrop-blur-sm">
                <span class="material-symbols-outlined text-secondary-fixed text-[20px]">verified</span><span class="font-label-md text-label-md text-surface-container-lowest font-semibold">100% Fiber Optic</span>
            </div>
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-surface-container-lowest/5 backdrop-blur-sm">
                <span class="material-symbols-outlined text-cyan-speed text-[20px]">sync_alt</span><span class="font-label-md text-label-md text-surface-container-lowest font-semibold">Rasio 1:1 Simetris</span>
            </div>
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-surface-container-lowest/5 backdrop-blur-sm">
                <span class="material-symbols-outlined text-secondary-fixed text-[20px]">all_inclusive</span><span class="font-label-md text-label-md text-surface-container-lowest font-semibold">Tanpa Batas FUP</span>
            </div>
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-surface-container-lowest/5 backdrop-blur-sm">
                <span class="material-symbols-outlined text-cyan-speed text-[20px]">security</span><span class="font-label-md text-label-md text-surface-container-lowest font-semibold">SLA 99.5% Uptime</span>
            </div>
            <div class="col-span-2 sm:col-span-1 flex items-center justify-center sm:justify-start gap-2.5 p-2 rounded-xl bg-surface-container-lowest/5 backdrop-blur-sm">
                <span class="material-symbols-outlined text-secondary-fixed text-[20px]">router</span><span class="font-label-md text-label-md text-surface-container-lowest font-semibold">Free Router Wi-Fi 6</span>
            </div>
        </div>
    </div>
</section>