<header class="fixed top-0 left-0 right-0 z-50 bg-surface-container-lowest/95 backdrop-blur-md shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="bg-surface-container-low hidden md:block">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 h-10 flex items-center justify-between text-label-md font-label-md">
            <div class="flex items-center gap-6">
                <span class="text-on-surface-variant font-medium">Segmen:</span>
                <a class="text-on-surface-variant hover:text-primary transition-colors" href="#">Bisnis &amp; Korporat</a>
                <span class="text-outline-variant">|</span>
                <a class="text-on-surface-variant hover:text-primary transition-colors" href="#">RT/RW Net</a>
                <span class="text-outline-variant">|</span>
                <a class="text-on-surface-variant hover:text-primary transition-colors" href="#">Instansi</a>
            </div>
            <div class="flex items-center gap-6">
                <a class="flex items-center gap-1 text-on-surface-variant hover:text-primary transition-colors" href="#">
                    <span class="material-symbols-outlined text-[16px]">location_on</span><span>Cek Lokasi / Cakupan Jaringan</span>
                </a>
                <a class="flex items-center gap-1 text-on-surface-variant hover:text-primary transition-colors" href="#">
                    <span class="material-symbols-outlined text-[16px]">support_agent</span><span>Bantuan: +62 284 3286924</span>
                </a>
                <a class="flex items-center gap-1 text-on-surface-variant hover:text-primary transition-colors" href="#">
                    <span class="material-symbols-outlined text-[16px]">lock</span><span>Login Mitra</span>
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-12 h-20 flex items-center justify-between gap-6">
      <a href="{{ url('/') }}" class="flex items-center shrink-0">
    <img src="{{ asset('images/logo-sakamedia.png') }}" alt="Saka Media Logo" class="h-9 sm:h-10 w-auto object-contain">
</a>

        <nav class="hidden md:flex items-center gap-6 font-label-lg text-label-lg">
            <a aria-current="page" class="transition-colors py-2 text-primary font-bold" href="#">Home</a>
            <a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="#produk-pilihan">Produk &amp; Layanan</a>
            <a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="#">Promosi</a>
            <a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="#">Tentang Kami</a>
            <a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="#">Berita &amp; Event</a>
            <a class="text-on-surface-variant hover:text-on-surface transition-colors py-2" href="#hubungi-kami">Hubungi Kami</a>
        </nav>

        <div class="flex items-center gap-3">
            <a class="hidden sm:flex items-center gap-1.5 px-4 py-2 rounded-full font-label-lg text-label-lg text-primary bg-surface-container-high hover:bg-surface-container-highest transition-colors" href="#cakupan">
                <span class="material-symbols-outlined text-[18px]">radar</span><span>Cek Lokasi Jaringan</span>
            </a>
            <a class="hidden md:flex items-center gap-1.5 px-5 py-2.5 rounded-full font-label-lg text-label-lg text-on-primary bg-primary hover:bg-violet-deep shadow-[0_4px_16px_-2px_rgba(105,33,112,0.4)] transition-all" href="#produk-pilihan">
                <span class="material-symbols-outlined text-[18px]">bolt</span><span>Berlangganan Sekarang</span>
            </a>
            <div class="hidden md:flex w-8 h-8 rounded-full bg-primary items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-on-primary text-[18px]">person</span>
            </div>

            <!-- Tombol Hamburger: hanya tampil di bawah md (mobile) -->
            <button id="navbar-toggle" aria-label="Buka menu" aria-expanded="false" aria-controls="mobile-menu"
                class="md:hidden flex items-center justify-center w-10 h-10 rounded-full text-primary hover:bg-surface-container-high transition-colors">
                <span id="navbar-toggle-icon" class="material-symbols-outlined text-[26px]">menu</span>
            </button>
        </div>
    </div>

    <!-- Panel menu mobile -->
    <div id="mobile-menu"
        class="md:hidden max-h-0 overflow-hidden transition-[max-height] duration-300 ease-in-out bg-surface-container-lowest border-t border-outline-variant/50">
        <nav class="flex flex-col px-6 py-4 font-label-lg text-label-lg">
            <a aria-current="page" class="py-3 text-primary font-bold" href="#">Home</a>
            <a class="py-3 text-on-surface-variant hover:text-on-surface transition-colors" href="#produk-pilihan">Produk &amp; Layanan</a>
            <a class="py-3 text-on-surface-variant hover:text-on-surface transition-colors" href="#">Promosi</a>
            <a class="py-3 text-on-surface-variant hover:text-on-surface transition-colors" href="#">Tentang Kami</a>
            <a class="py-3 text-on-surface-variant hover:text-on-surface transition-colors" href="#">Berita &amp; Event</a>
            <a class="py-3 text-on-surface-variant hover:text-on-surface transition-colors" href="#hubungi-kami">Hubungi Kami</a>

            <div class="h-px bg-outline-variant/50 my-2"></div>

            <a class="flex items-center gap-2 py-3 text-on-surface-variant" href="#">
                <span class="material-symbols-outlined text-[18px]">location_on</span>Cek Lokasi / Cakupan Jaringan
            </a>
            <a class="flex items-center gap-2 py-3 text-on-surface-variant" href="#">
                <span class="material-symbols-outlined text-[18px]">support_agent</span>Bantuan: +62 284 3286924
            </a>
            <a class="flex items-center gap-2 py-3 text-on-surface-variant" href="#">
                <span class="material-symbols-outlined text-[18px]">lock</span>Login Mitra
            </a>

            <a class="mt-3 flex items-center justify-center gap-1.5 px-5 py-3 rounded-full font-label-lg text-label-lg text-on-primary bg-primary hover:bg-violet-deep transition-all" href="#produk-pilihan">
                <span class="material-symbols-outlined text-[18px]">bolt</span><span>Berlangganan Sekarang</span>
            </a>
        </nav>
    </div>
</header>

<script>
    (function () {
        const toggleBtn = document.getElementById('navbar-toggle');
        const toggleIcon = document.getElementById('navbar-toggle-icon');
        const menu = document.getElementById('mobile-menu');

        toggleBtn.addEventListener('click', function () {
            const isOpen = toggleBtn.getAttribute('aria-expanded') === 'true';

            if (isOpen) {
                menu.style.maxHeight = '0px';
                toggleIcon.textContent = 'menu';
                toggleBtn.setAttribute('aria-expanded', 'false');
                toggleBtn.setAttribute('aria-label', 'Buka menu');
            } else {
                menu.style.maxHeight = menu.scrollHeight + 'px';
                toggleIcon.textContent = 'close';
                toggleBtn.setAttribute('aria-expanded', 'true');
                toggleBtn.setAttribute('aria-label', 'Tutup menu');
            }
        });

        menu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                menu.style.maxHeight = '0px';
                toggleIcon.textContent = 'menu';
                toggleBtn.setAttribute('aria-expanded', 'false');
            });
        });

        // Reset menu kalau layar di-resize melewati breakpoint md
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) {
                menu.style.maxHeight = '0px';
                toggleIcon.textContent = 'menu';
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
        });
    })();
</script>