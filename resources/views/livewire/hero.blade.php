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
    class="relative w-full overflow-hidden bg-[#050B1F] text-on-primary"
>
    {{-- Animasi blob --}}
    <style>
        @keyframes blob {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%      { transform: translate(40px, -30px) scale(1.1); }
            66%      { transform: translate(-30px, 30px) scale(0.95); }
        }
        .animate-blob { animation: blob 14s ease-in-out infinite; }
    </style>

    {{-- ===== BACKGROUND ===== --}}
    {{-- Base gradient --}}
    <div class="absolute inset-0 bg-gradient-to-br from-[#050B1F] via-[#0B1A45] to-[#1E3A8A] pointer-events-none z-0"></div>

    {{-- Mesh gradient (radial bertumpuk) --}}
    <div class="absolute inset-0 pointer-events-none z-0 opacity-90"
         style="background:
            radial-gradient(60% 50% at 10% 10%, rgba(34,211,238,.28) 0%, transparent 60%),
            radial-gradient(50% 45% at 90% 20%, rgba(59,130,246,.35) 0%, transparent 60%),
            radial-gradient(55% 50% at 80% 100%, rgba(139,92,246,.30) 0%, transparent 60%),
            radial-gradient(40% 40% at 5% 95%, rgba(220,38,38,.20) 0%, transparent 60%);">
    </div>

    {{-- Blob bergerak pelan --}}
    <div class="absolute -top-32 -left-32 w-[550px] h-[550px] rounded-full bg-cyan-speed/20 blur-3xl pointer-events-none z-0 animate-blob"></div>
    <div class="absolute bottom-0 right-10 w-[600px] h-[600px] rounded-full bg-[#3B82F6]/25 blur-3xl pointer-events-none z-0 animate-blob [animation-delay:4s]"></div>

    {{-- Efek paket data fiber --}}
    <span class="streak" style="top:18%;animation-delay:0s"></span>
    <span class="streak" style="top:38%;animation-delay:1.6s;width:240px"></span>
    <span class="streak" style="top:62%;animation-delay:3.1s"></span>
    <span class="streak" style="top:82%;animation-delay:4.2s;width:140px"></span>

    {{-- Grid tipis ala tech/network --}}
    <div class="absolute inset-0 pointer-events-none z-0 opacity-[0.07]"
         style="background-image:
            linear-gradient(to right, #fff 1px, transparent 1px),
            linear-gradient(to bottom, #fff 1px, transparent 1px);
            background-size: 48px 48px;
            mask-image: radial-gradient(ellipse at center, #000 30%, transparent 75%);
            -webkit-mask-image: radial-gradient(ellipse at center, #000 30%, transparent 75%);">
    </div>

    {{-- Fade ke bawah biar menyatu dengan section berikutnya --}}
    <div class="absolute bottom-0 inset-x-0 h-24 bg-gradient-to-t from-[#060D24] to-transparent pointer-events-none z-0"></div>

    {{-- ===== KONTEN ===== --}}
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-10 lg:py-16 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            {{-- Kolom kiri: teks --}}
            <div class="lg:col-span-6 space-y-6">
                <div class="space-y-2">
                    <h1 class="enter font-display-xl text-display-xl tracking-tight leading-none text-surface-container-lowest" style="--d:100">
                        SAKA MEDIA<br/>
                        <span class="text-shimmer text-transparent bg-clip-text bg-gradient-to-r from-secondary-fixed via-cyan-speed to-secondary-fixed drop-shadow-[0_0_20px_rgba(34,211,238,0.45)]">CONNECTING</span> EVERYONE
                    </h1>
                    <p class="enter font-title-md text-title-md text-secondary-fixed font-semibold" style="--d:250">Internet Fiber Ultra Cepat, Simetris 1:1 Tanpa Batas FUP untuk Bisnis &amp; Komunitas</p>
                    <p class="enter font-body-md text-body-md text-surface-container-highest max-w-xl leading-relaxed" style="--d:400">Nikmati internet pita lebar berkecepatan gigabit dengan jalur transmisi serat optik murni dari Saka Media yang didukung lisensi resmi Kominfo dan latensi ultra rendah untuk gaming, streaming, dan produktivitas usaha.</p>
                </div>

                {{-- Kartu harga --}}
                <div class="enter" style="--d:550">
                    <div class="float-y inline-flex items-center gap-4 p-3 pr-6 rounded-2xl bg-surface-container-lowest/10 backdrop-blur-md border border-surface-container-lowest/15 shadow-inner">
                        <div class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-secondary-container to-secondary-fixed text-on-secondary-fixed font-bold text-center">
                            <span class="block text-[10px] uppercase tracking-wider font-extrabold text-on-secondary-fixed-variant">Mulai Dari</span>
                            <span class="font-headline-sm text-headline-sm font-extrabold leading-none" data-count="100000" data-prefix="Rp ">Rp 100.000</span>
                            <span class="text-[11px] font-medium">/bulan</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 text-cyan-speed font-bold text-label-lg">
                                <span class="material-symbols-outlined text-[18px]">speed</span><span>Kecepatan Hingga 1 Gbps</span>
                            </div>
                            <p class="text-body-sm text-surface-container-highest">Termasuk Wi-Fi 6 Router &amp; Support Prioritas 24 Jam</p>
                        </div>
                    </div>
                </div>

                {{-- CTA merah --}}
                <div class="enter flex flex-wrap items-center gap-4 pt-1" style="--d:700">
                    <a class="cta-pulse inline-flex items-center gap-2 px-8 py-4 rounded-full font-label-lg text-label-lg font-extrabold text-white bg-red-accent hover:bg-red-deep shadow-[0_4px_20px_rgba(220,38,38,0.45)] hover:shadow-xl transition-all transform hover:-translate-y-0.5" href="#produk-pilihan">
                        <span class="material-symbols-outlined text-[22px]"></span><span>Berlangganan Sekarang</span>
                    </a>
                </div>
            </div>

            {{-- Kolom kanan: kartu slider --}}
            <div class="enter enter-right lg:col-span-6 relative" style="--d:400" @mouseenter="stop()" @mouseleave="start()">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-2 border-surface-container-lowest/20 bg-[#0F1D4D]/90 backdrop-blur-md p-3 group">

                    {{-- Area gambar slide --}}
                    <div class="relative w-full aspect-[16/10] rounded-2xl overflow-hidden">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div
                                x-show="active === index"
                                x-transition:enter="transition ease-out duration-700"
                                x-transition:enter-start="opacity-0 scale-105"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-500"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="absolute inset-0 flex items-end bg-gradient-to-br from-violet-deep to-primary overflow-hidden"
                            >
                                {{-- Layer gambar (dipisah supaya zoom tidak ikut menggeser teks) --}}
                                <div class="absolute inset-0 bg-cover bg-center"
                                     :class="active === index ? 'kenburns' : ''"
                                     :style="slide.image ? `background-image:url('${slide.image}')` : ''"></div>

                                <a :href="slide.link" class="absolute inset-0 z-10" :aria-label="slide.title"></a>

                                <div class="absolute inset-0 bg-gradient-to-t from-[#0A1433]/90 via-transparent to-transparent flex items-end p-5 w-full pointer-events-none">
                                    <div class="flex items-center justify-between w-full">
                                        <div>
                                            {{-- Badge slide merah --}}
                                            <span class="inline-block px-2.5 py-0.5 rounded bg-red-accent text-white font-label-md text-label-md font-bold mb-1 shadow-sm" x-text="slide.badge"></span>
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

        {{-- Baris badge keunggulan (muncul bertahap saat terlihat) --}}
        <div class="mt-10 pt-6 border-t border-surface-container-lowest/15 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 text-center sm:text-left">
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-surface-container-lowest/5 backdrop-blur-sm" data-reveal style="--d:0">
                <span class="material-symbols-outlined text-secondary-fixed text-[20px]">verified</span><span class="font-label-md text-label-md text-surface-container-lowest font-semibold">100% Fiber Optic</span>
            </div>
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-surface-container-lowest/5 backdrop-blur-sm" data-reveal style="--d:100">
                <span class="material-symbols-outlined text-cyan-speed text-[20px]">sync_alt</span><span class="font-label-md text-label-md text-surface-container-lowest font-semibold">Rasio 1:1 Simetris</span>
            </div>
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-surface-container-lowest/5 backdrop-blur-sm" data-reveal style="--d:200">
                <span class="material-symbols-outlined text-red-accent text-[20px]">all_inclusive</span><span class="font-label-md text-label-md text-surface-container-lowest font-semibold">Tanpa Batas FUP</span>
            </div>
            <div class="flex items-center gap-2.5 p-2 rounded-xl bg-surface-container-lowest/5 backdrop-blur-sm" data-reveal style="--d:300">
                <span class="material-symbols-outlined text-cyan-speed text-[20px]">security</span><span class="font-label-md text-label-md text-surface-container-lowest font-semibold">SLA 99.5% Uptime</span>
            </div>
            <div class="col-span-2 sm:col-span-1 flex items-center justify-center sm:justify-start gap-2.5 p-2 rounded-xl bg-surface-container-lowest/5 backdrop-blur-sm" data-reveal style="--d:400">
                <span class="material-symbols-outlined text-secondary-fixed text-[20px]">router</span><span class="font-label-md text-label-md text-surface-container-lowest font-semibold">Free Router Wi-Fi 6</span>
            </div>
        </div>
    </div>
</section>