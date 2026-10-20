<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, viewport-fit=cover" name="viewport"/>
<title>{{ $title ?? 'Saka Media - Internet Fiber Ultra Cepat' }}</title>

{{-- SEO dasar --}}
<meta name="description" content="{{ $description ?? 'Internet Fiber Ultra Cepat, Simetris 1:1 Tanpa Batas FUP dari Saka Media untuk Bisnis, Instansi, dan Mitra RT/RW Net di Jawa Tengah.' }}">
<meta name="theme-color" content="#1D4ED8">

{{-- Open Graph (preview saat link dibagikan di WhatsApp, Facebook, Telegram) --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="Saka Media">
<meta property="og:title" content="{{ $ogTitle ?? ($title ?? 'Saka Media - Internet Fiber Ultra Cepat') }}">
<meta property="og:description" content="{{ $ogDescription ?? ($description ?? 'Internet Fiber Ultra Cepat, Simetris 1:1 Tanpa Batas FUP dari Saka Media untuk Bisnis, Instansi, dan Mitra RT/RW Net di Jawa Tengah.') }}">
<meta property="og:image" content="{{ $ogImage ?? asset('images/saka.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:locale" content="id_ID">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $ogTitle ?? ($title ?? 'Saka Media - Internet Fiber Ultra Cepat') }}">
<meta name="twitter:description" content="{{ $ogDescription ?? ($description ?? 'Internet Fiber Ultra Cepat, Simetris 1:1 Tanpa Batas FUP dari Saka Media.') }}">
<meta name="twitter:image" content="{{ $ogImage ?? asset('images/saka.png') }}">

{{-- Favicon --}}
<link rel="icon" type="image/png" href="{{ asset('images/logo-sakamedia.png') }}">
<link rel="shortcut icon" type="image/png" href="{{ asset('images/saka.png') }}">
<link rel="apple-touch-icon" href="{{ asset('images/saka.png') }}">

{{-- Font: preconnect supaya koneksi ke Google Fonts lebih cepat --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

{{-- Inter tetap swap (teks biasa langsung tampil) --}}
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet"/>
{{-- Material Symbols pakai block (jangan tampilkan teks fallback) --}}
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet"/>

<style>
  @layer base{html,body{margin:0;padding:0;}body{overscroll-behavior:none;}main>:first-child{margin-top:0!important;}main>:last-child{margin-bottom:0!important;}}
  ::-webkit-scrollbar{display:none;}

  :root{
    box-sizing:border-box;
    padding-top: env(safe-area-inset-top, 0px);
    padding-bottom: env(safe-area-inset-bottom, 0px);
  }
  html{ scroll-padding-top: env(safe-area-inset-top, 0px); }
  header{ padding-top: env(safe-area-inset-top, 0px); }
  body{ background:#f9f9ff; }

  .material-symbols-outlined{
    font-family: 'Material Symbols Outlined';
    font-weight: normal;
    font-style: normal;
    font-size: 24px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    font-feature-settings: 'liga';
    -webkit-font-feature-settings: 'liga';
    -webkit-font-smoothing: antialiased;

    /* Sembunyikan teks ikon sampai font siap */
    visibility: hidden;
  }
  .fonts-loaded .material-symbols-outlined{ visibility: visible; }

  /* ===== PRELOADER ===== */
  #preloader{position:fixed;inset:0;z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:20px;background:#050B1F;transition:opacity .6s ease,visibility .6s ease}
  #preloader.hide{opacity:0;visibility:hidden}
  #preloader .pl-logo{width:72px;height:72px;object-fit:contain;animation:plPulse 1.4s ease-in-out infinite}
  #preloader .pl-bar{width:160px;height:3px;border-radius:99px;background:rgba(255,255,255,.12);overflow:hidden}
  #preloader .pl-bar::after{content:"";display:block;height:100%;width:40%;border-radius:99px;background:linear-gradient(90deg,#2DD4BF,#00D4FF);animation:plSlide 1s ease-in-out infinite}
  @keyframes plPulse{0%,100%{transform:scale(1);filter:drop-shadow(0 0 0 rgba(0,212,255,0))}50%{transform:scale(1.08);filter:drop-shadow(0 0 18px rgba(0,212,255,.6))}}
  @keyframes plSlide{0%{transform:translateX(-100%)}100%{transform:translateX(350%)}}

  /* ===== ANIMASI MASUK HERO (muncul bertahap setelah halaman siap) ===== */
  .enter{opacity:0}
  .is-ready .enter{animation:enterUp .9s cubic-bezier(.22,1,.36,1) both;animation-delay:calc(var(--d,0) * 1ms)}
  .is-ready .enter-right{animation-name:enterRight}
  @keyframes enterUp{from{opacity:0;transform:translateY(28px);filter:blur(8px)}to{opacity:1;transform:none;filter:blur(0)}}
  @keyframes enterRight{from{opacity:0;transform:translateX(48px) scale(.97);filter:blur(8px)}to{opacity:1;transform:none;filter:blur(0)}}

  /* ===== MUNCUL SAAT SCROLL ===== */
  [data-reveal]{opacity:0;transform:translateY(36px);transition:opacity .8s cubic-bezier(.22,1,.36,1),transform .8s cubic-bezier(.22,1,.36,1);transition-delay:calc(var(--d,0) * 1ms)}
  [data-reveal="left"]{transform:translateX(-48px)}
  [data-reveal="right"]{transform:translateX(48px)}
  [data-reveal="zoom"]{transform:scale(.94)}
  [data-reveal].is-visible{opacity:1;transform:none}

  /* ===== MODAL ===== */
  .modal-fade{animation:modalFade .25s ease-out both}
  .modal-pop{animation:modalPop .45s cubic-bezier(.22,1,.36,1) both}
  @keyframes modalFade{from{opacity:0}to{opacity:1}}
  @keyframes modalPop{from{opacity:0;transform:translateY(48px) scale(.97)}to{opacity:1;transform:none}}

  /* ===== KARTU POPULER ===== */
  .glow-pulse{animation:glowPulse 3s ease-in-out infinite}
  @keyframes glowPulse{0%,100%{box-shadow:0 20px 25px -5px rgba(29,78,216,.15),0 8px 10px -6px rgba(29,78,216,.1)}50%{box-shadow:0 22px 40px -5px rgba(29,78,216,.35),0 8px 16px -6px rgba(29,78,216,.2)}}

  /* ===== PESAN SUKSES (muncul setelah form dikirim) ===== */
  .pop-in{animation:popIn .5s cubic-bezier(.22,1,.36,1) both}
  @keyframes popIn{from{opacity:0;transform:translateY(12px) scale(.98)}to{opacity:1;transform:none}}

  /* ===== EFEK TEKS & BACKGROUND ===== */
  .text-shimmer{background-size:200% auto;animation:shimmer 4s linear infinite}
  @keyframes shimmer{to{background-position:200% center}}

  .streak{position:absolute;left:-20%;height:1px;width:180px;pointer-events:none;z-index:0;
    background:linear-gradient(90deg,transparent,#22D3EE,transparent);opacity:0;animation:streak 5s linear infinite}
  @keyframes streak{0%{transform:translateX(0);opacity:0}10%{opacity:1}90%{opacity:1}100%{transform:translateX(160vw);opacity:0}}

  .kenburns{animation:kenburns 7s ease-out forwards}
  @keyframes kenburns{from{transform:scale(1)}to{transform:scale(1.08)}}

  .cta-pulse{animation:ctaPulse 2.4s ease-out infinite}
  @keyframes ctaPulse{0%{box-shadow:0 4px 20px rgba(220,38,38,.45),0 0 0 0 rgba(220,38,38,.55)}70%{box-shadow:0 4px 20px rgba(220,38,38,.45),0 0 0 18px rgba(220,38,38,0)}100%{box-shadow:0 4px 20px rgba(220,38,38,.45),0 0 0 0 rgba(220,38,38,0)}}

  .float-y{animation:floatY 5s ease-in-out infinite}
  @keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}

  /* ===== HORMATI "REDUCE MOTION" ===== */
  @media (prefers-reduced-motion:reduce){
    #preloader{display:none}
    .enter,[data-reveal]{opacity:1!important;transform:none!important;animation:none!important;transition:none!important;filter:none!important}
    .animate-blob,.streak,.kenburns,.cta-pulse,.float-y,.text-shimmer,.pop-in,.modal-fade,.modal-pop,.glow-pulse{animation:none!important}
  }
</style>

{{-- Kalau JavaScript mati, tetap tampilkan ikon & konten --}}
<noscript>
  <style>
    .material-symbols-outlined{ visibility: visible; }
    #preloader{ display:none; }
    .enter,[data-reveal]{ opacity:1!important; transform:none!important; }
  </style>
</noscript>

{{-- Tampilkan ikon hanya setelah font Material Symbols benar-benar siap --}}
<script>
  (document.fonts && document.fonts.load
    ? document.fonts.load('24px "Material Symbols Outlined"')
    : Promise.resolve()
  ).finally(function () {
    document.documentElement.classList.add('fonts-loaded');
  });
</script>

<script src="https://cdn.tailwindcss.com"></script>
<script id="tailwind-config">
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
  "on-primary-fixed-variant":"#1E40AF","violet-deep":"#172554","surface-container-highest":"#dbe2f9",
  "inverse-surface":"#293041","secondary-fixed":"#CCFBF1","on-tertiary-container":"#36b6fe",
  "primary-fixed":"#DBEAFE","on-secondary-fixed":"#042F2E","gold-light":"#FEE2E2",
  "surface-dim":"#d2daf0","primary-fixed-dim":"#93C5FD","error":"#ba1a1a","gold-dark":"#B91C1C",
  "text-muted":"#475467","secondary":"#0F766E","tertiary-fixed-dim":"#8bceff","surface":"#f9f9ff",
  "error-container":"#ffdad6","inverse-primary":"#93C5FD","surface-container":"#e9edff",
  "on-error-container":"#93000a","secondary-container":"#5EEAD4","surface-container-lowest":"#ffffff",
  "on-tertiary-fixed-variant":"#004b6f","tertiary-fixed":"#c9e6ff","primary":"#1D4ED8",
  "on-background":"#141b2c","tertiary":"#002e46","surface-container-high":"#e0e8ff",
  "on-primary-container":"#BFDBFE","surface-variant":"#dbe2f9","primary-container":"#2563EB",
  "surface-container-low":"#f1f3ff","secondary-fixed-dim":"#2DD4BF","violet-glow":"#3B82F6",
  "surface-tint":"#2563EB","on-primary-fixed":"#0B1F5C","border-subtle":"#EAECF0",
  "surface-subtle":"#F9FAFB","on-surface-variant":"#475569","cyan-speed":"#00D4FF",
  "surface-card":"#FFFFFF","on-tertiary-fixed":"#001e2f","on-secondary-container":"#134E4A",
  "on-surface":"#141b2c","on-error":"#ffffff","on-tertiary":"#ffffff",
  "inverse-on-surface":"#edf0ff","outline":"#64748B","surface-bright":"#f9f9ff",
  "on-secondary":"#ffffff","on-primary":"#ffffff","surface-accent":"#EFF6FF",
  "on-secondary-fixed-variant":"#115E59","tertiary-container":"#004566","outline-variant":"#CBD5E1",
  "background":"#f9f9ff",
  "red-accent":"#DC2626","red-soft":"#FEE2E2","red-deep":"#991B1B"
},
      borderRadius: { "DEFAULT":"0.25rem","lg":"0.5rem","xl":"0.75rem","full":"9999px" },
      spacing: {
        "space-sm":"0.5rem","margin":"2rem","margin-mobile":"1rem","gutter-mobile":"1rem",
        "space-md":"1rem","gutter":"1.5rem","space-xs":"0.25rem","space-xl":"2.5rem","space-lg":"1.5rem"
      },
      fontFamily: {
        "title-sm":["Inter"],"body-lg":["Inter"],"headline-lg-mobile":["Inter"],"badge-speed":["Inter"],
        "display-xl-mobile":["Inter"],"label-lg":["Inter"],"body-md":["Inter"],"body-sm":["Inter"],
        "headline-md":["Inter"],"headline-sm":["Inter"],"label-md":["Inter"],"headline-lg":["Inter"],
        "display-xl":["Inter"],"title-md":["Inter"]
      },
      fontSize: {
        "title-sm":["16px",{lineHeight:"24px",fontWeight:"600"}],
        "body-lg":["16px",{lineHeight:"24px",fontWeight:"400"}],
        "headline-lg-mobile":["26px",{lineHeight:"34px",letterSpacing:"-0.01em",fontWeight:"700"}],
        "badge-speed":["28px",{lineHeight:"32px",letterSpacing:"-0.02em",fontWeight:"800"}],
        "display-xl-mobile":["32px",{lineHeight:"40px",letterSpacing:"-0.02em",fontWeight:"800"}],
        "label-lg":["14px",{lineHeight:"20px",letterSpacing:"0.01em",fontWeight:"600"}],
        "body-md":["14px",{lineHeight:"20px",fontWeight:"400"}],
        "body-sm":["12px",{lineHeight:"18px",fontWeight:"400"}],
        "headline-md":["24px",{lineHeight:"32px",letterSpacing:"-0.01em",fontWeight:"700"}],
        "headline-sm":["20px",{lineHeight:"28px",fontWeight:"600"}],
        "label-md":["12px",{lineHeight:"16px",letterSpacing:"0.02em",fontWeight:"600"}],
        "headline-lg":["36px",{lineHeight:"44px",letterSpacing:"-0.02em",fontWeight:"700"}],
        "display-xl":["48px",{lineHeight:"56px",letterSpacing:"-0.03em",fontWeight:"800"}],
        "title-md":["18px",{lineHeight:"26px",fontWeight:"600"}]
      }
    }
  }
};
</script>

@livewireStyles
</head>
<body class="bg-surface font-body-md text-on-surface antialiased">

    {{-- Preloader --}}
    <div id="preloader" aria-hidden="true">
        <img class="pl-logo" src="{{ asset('images/saka.png') }}" alt="">
        <div class="pl-bar"></div>
    </div>

    {{ $slot }}

@livewireScripts

<script>
(function () {
  var root = document.documentElement;
  var started = false;
  var revealed = new WeakSet(); // elemen yang sudah tampil (supaya tidak hilang saat Livewire render ulang)

  // Hitung naik (dipakai untuk harga)
  function countUp(el) {
    var target = parseInt(el.dataset.count, 10);
    var prefix = el.dataset.prefix || '';
    var dur = 1400, t0 = null;
    function step(t) {
      if (!t0) t0 = t;
      var p = Math.min((t - t0) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 4);
      el.textContent = prefix + Math.round(target * eased).toLocaleString('id-ID');
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }

  // Muncul saat di-scroll (satu observer dipakai ulang; elemen baru dari Livewire ikut terpantau)
  var io = null;
  var seen = new WeakSet();
  function setupReveal() {
    var items = document.querySelectorAll('[data-reveal]:not(.is-visible)');
    if (!('IntersectionObserver' in window)) {
      items.forEach(function (el) { revealed.add(el); el.classList.add('is-visible'); });
      return;
    }
    if (!io) {
      io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) { revealed.add(e.target); e.target.classList.add('is-visible'); io.unobserve(e.target); }
        });
      }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    }
    items.forEach(function (el) {
      if (!seen.has(el)) { seen.add(el); io.observe(el); }
    });
  }

  // Elemen yang baru muncul (kartu produk saat ganti kategori, modal, dll.) otomatis ikut dipantau
  var moFrame;
  new MutationObserver(function () {
    cancelAnimationFrame(moFrame);
    moFrame = requestAnimationFrame(setupReveal);
  }).observe(document.documentElement, { childList: true, subtree: true });

  // Halaman siap: tutup preloader, jalankan animasi hero
  function ready() {
    if (started) return;
    started = true;
    var pre = document.getElementById('preloader');
    if (pre) { pre.classList.add('hide'); setTimeout(function () { pre.remove(); }, 700); }
    root.classList.add('is-ready');
    setTimeout(function () {
      document.querySelectorAll('[data-count]').forEach(countUp);
    }, 600);
  }

  // Tunggu minimal 700ms (biar preloader terlihat halus) + halaman & font selesai
  var minWait = new Promise(function (r) { setTimeout(r, 700); });
  var loaded = new Promise(function (r) {
    if (document.readyState === 'complete') r(); else window.addEventListener('load', r);
  });
  Promise.all([minWait, loaded]).then(ready);
  setTimeout(ready, 3000); // pengaman kalau ada aset yang lambat

  // Livewire: setelah render ulang, kembalikan status "sudah tampil"
  document.addEventListener('livewire:init', function () {
    if (window.Livewire && Livewire.hook) {
      Livewire.hook('morph.updated', function (p) {
        if (p.el && revealed.has(p.el)) p.el.classList.add('is-visible');
      });
    }
  });

  document.addEventListener('DOMContentLoaded', setupReveal);
  document.addEventListener('livewire:navigated', setupReveal);
})();
</script>
</body>
</html>