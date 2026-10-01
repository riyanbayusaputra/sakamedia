<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0, viewport-fit=cover" name="viewport"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet"/>
<title>{{ $title ?? 'Saka Media - Internet Fiber Ultra Cepat' }}</title>

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
  }
</style>

<script src="https://cdn.tailwindcss.com"></script>
<script id="tailwind-config">
tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
  "on-primary-fixed-variant":"#1E40AF","violet-deep":"#172554","surface-container-highest":"#dbe2f9",
  "inverse-surface":"#293041","secondary-fixed":"#CCFBF1","on-tertiary-container":"#36b6fe",
  "primary-fixed":"#DBEAFE","on-secondary-fixed":"#042F2E","gold-light":"#CCFBF1",
  "surface-dim":"#d2daf0","primary-fixed-dim":"#93C5FD","error":"#ba1a1a","gold-dark":"#0F766E",
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
  "background":"#f9f9ff"
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

    {{ $slot }}

@livewireScripts
</body>
</html>