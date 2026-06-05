@extends('frontend.layouts.apkasi')

@section('title', 'HUT Ke-26 APKASI & Deli Serdang Ke-80 — 1-3 Juli 2026')

@php
    // $agenda dikirim dari HomeController@index (dari tabel rundown).
    $dayIcons = ['star', 'users', 'tree-pine', 'calendar'];
    // Logo bergantian utk ikon header tiap hari di section Agenda.
    $dayImages = ['assets/apkasi/logo-ds.png', 'assets/apkasi/z_04_LOGO-LOGO APKASI/01_APKASI_Official Logo alt.png'];
    // Helper baca konten landing dari settings ($appSettings di-share global)
    $g = fn($k, $d = '') => ($appSettings[$k] ?? $d);
    // Helper URL gambar: link http(s) dipakai apa adanya, selainnya lewat asset()
    $img = function ($k, $d = '') use ($g) {
        $v = $g($k, $d);
        return \Illuminate\Support\Str::startsWith($v, ['http://', 'https://']) ? $v : asset($v);
    };
@endphp

@section('content')
@include('frontend.partials.splash')

{{-- ═══════════ INTRO HERO (scroll-jack 3 tahap) — auto tampil maks 1x / 2 jam per IP; tetap bisa dibuka via scroll mentok ke atas ═══════════ --}}
<style>
    #intro{position:fixed;inset:0;z-index:9000;overflow:hidden;background:#0a1f15;transform:translateY(0);transition:transform 1s cubic-bezier(.76,0,.24,1);will-change:transform}
    #intro.is-hidden{transform:translateY(-100%)}
    .intro-scene{position:absolute;inset:0;overflow:hidden;background:radial-gradient(130% 130% at 50% 26%,#123d28 0%,#0a2417 45%,#06140c 82%);opacity:0;transform:scale(1.06);transition:opacity 1.1s ease,transform 9s ease}
    .intro-scene.is-active{opacity:1;transform:scale(1)}
    .blob{position:absolute;border-radius:50%;filter:blur(62px);opacity:.82;mix-blend-mode:screen;will-change:transform;pointer-events:none}
    .b-red{background:radial-gradient(circle,#ff5043 0%,rgba(255,80,67,0) 70%)}
    .b-grn{background:radial-gradient(circle,#62d44f 0%,rgba(98,212,79,0) 70%)}
    .b-org{background:radial-gradient(circle,#ffa620 0%,rgba(255,166,32,0) 70%)}
    .b-blu{background:radial-gradient(circle,#2ba6ec 0%,rgba(43,166,236,0) 70%)}
    @keyframes bf1{from{transform:translate(0,0) scale(1)}to{transform:translate(7vw,6vh) scale(1.18)}}
    @keyframes bf2{from{transform:translate(0,0) scale(1)}to{transform:translate(-8vw,-5vh) scale(1.12)}}
    @keyframes bf3{from{transform:translate(0,0) scale(1)}to{transform:translate(6vw,-7vh) scale(1.2)}}
    .bf1{animation:bf1 15s ease-in-out infinite alternate}.bf2{animation:bf2 18s ease-in-out infinite alternate}.bf3{animation:bf3 21s ease-in-out infinite alternate}
    @media(prefers-reduced-motion:reduce){.blob{animation:none!important}}
    .intro-veil{position:absolute;inset:0;background:linear-gradient(180deg,rgba(7,18,12,.62) 0%,rgba(7,18,12,.34) 38%,rgba(7,18,12,.4) 60%,rgba(7,18,12,.8) 100%)}
    .intro-leaves{position:absolute;inset:0;overflow:hidden;z-index:2;pointer-events:none}
    .leaf{position:absolute;top:-16vh;width:var(--sz);height:calc(var(--sz) * 1.4);background:url('{{ asset('assets/apkasi/intro/leaf.svg') }}') center/contain no-repeat;opacity:.68;filter:drop-shadow(0 4px 7px rgba(0,0,0,.25));animation:leafFall var(--dur) linear var(--delay) infinite;will-change:transform}
    .leaf.gold{filter:sepia(.7) saturate(1.7) hue-rotate(-14deg) drop-shadow(0 4px 7px rgba(0,0,0,.28))}
    @keyframes leafFall{0%{transform:translateY(-16vh) translateX(0) rotate(0)}25%{transform:translateY(16vh) translateX(var(--sway)) rotate(120deg)}50%{transform:translateY(46vh) translateX(calc(var(--sway) * -1)) rotate(230deg)}75%{transform:translateY(78vh) translateX(var(--sway)) rotate(330deg)}100%{transform:translateY(122vh) translateX(0) rotate(420deg)}}
    .intro-logos{position:absolute;top:9%;left:50%;transform:translateX(-50%);z-index:3;display:flex;flex-direction:column;align-items:center;gap:12px;pointer-events:none}
    .il-ds{height:clamp(66px,9vw,116px);width:auto;filter:drop-shadow(0 6px 18px rgba(0,0,0,.5))}
    .il-row{display:flex;align-items:center;gap:clamp(18px,4vw,46px)}
    .il-hut{height:clamp(54px,6.8vw,88px);width:auto;filter:drop-shadow(0 6px 18px rgba(0,0,0,.45))}
    @media(prefers-reduced-motion:reduce){.leaf{display:none}}
    @media(max-width:640px){.intro-logos{top:6%;gap:10px}}
    .intro-wrap{position:relative;z-index:3;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:24px;color:#fff;font-family:'Plus Jakarta Sans',sans-serif}
    .intro-cap{position:absolute;top:36%;left:50%;width:min(820px,92vw);padding:0 12px;opacity:0;transform:translate(-50%,34px);transition:opacity .7s ease,transform .8s cubic-bezier(.2,.8,.2,1);pointer-events:none}
    .intro-cap.is-active{opacity:1;transform:translate(-50%,0);pointer-events:auto}
    .intro-badge{display:inline-flex;align-items:center;gap:8px;background:rgba(212,175,55,.16);border:1px solid rgba(212,175,55,.5);color:#FFE07D;font-size:clamp(11px,1.4vw,14px);font-weight:700;letter-spacing:.14em;text-transform:uppercase;padding:8px 18px;border-radius:99px;margin-bottom:22px}
    .intro-cap h2{font-family:'Outfit','Plus Jakarta Sans',sans-serif;font-weight:800;font-size:clamp(2.1rem,6.5vw,4.6rem);line-height:1.04;letter-spacing:-.02em;margin:0;text-shadow:0 6px 30px rgba(0,0,0,.45)}
    .intro-cap .accent{color:#85AB8B}
    .intro-cap p{margin:18px auto 0;max-width:620px;font-size:clamp(.95rem,1.9vw,1.25rem);line-height:1.6;color:rgba(255,255,255,.82)}
    .intro-enter{margin-top:32px;display:inline-flex;align-items:center;gap:10px;background:linear-gradient(90deg,#D4AF37,#FFE07D);color:#1f2a1d;font-weight:800;font-size:clamp(.9rem,1.6vw,1.05rem);padding:14px 34px;border-radius:99px;border:0;cursor:pointer;box-shadow:0 12px 34px rgba(212,175,55,.35);transition:transform .25s ease,box-shadow .25s ease}
    .intro-enter:hover{transform:translateY(-2px);box-shadow:0 16px 40px rgba(212,175,55,.5)}
    .intro-dots{position:absolute;left:50%;bottom:104px;transform:translateX(-50%);z-index:4;display:flex;gap:10px}
    .intro-dot{width:9px;height:9px;border-radius:99px;background:rgba(255,255,255,.32);transition:all .4s ease}
    .intro-dot.is-active{width:30px;background:#D4AF37}
    .intro-hint{position:absolute;left:50%;bottom:42px;transform:translateX(-50%);z-index:4;display:flex;flex-direction:column;align-items:center;gap:6px;color:rgba(255,255,255,.7);font-size:12px;font-weight:600;letter-spacing:.08em;text-transform:uppercase}
    .intro-hint i{animation:introBounce 1.8s ease-in-out infinite}
    @keyframes introBounce{0%,100%{transform:translateY(0)}50%{transform:translateY(7px)}}
    .intro-skip{position:absolute;top:24px;right:24px;z-index:4;display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.22);color:#fff;font-size:12px;font-weight:600;letter-spacing:.06em;padding:8px 16px;border-radius:99px;cursor:pointer;backdrop-filter:blur(6px);transition:background .25s ease}
    .intro-skip:hover{background:rgba(255,255,255,.22)}
    .intro-mark{position:absolute;top:24px;left:28px;z-index:4;display:flex;align-items:center;gap:10px;opacity:.92}
    .intro-mark img{height:34px;width:auto}
    @media(max-width:640px){.intro-dots{bottom:92px}.intro-hint{bottom:34px}}
    @media(prefers-reduced-motion:reduce){.intro-scene{transition:opacity .4s ease}.intro-scene.is-active{transform:none}#intro{transition:transform .5s ease}}
</style>
<section id="intro" aria-label="Pembuka" data-auto="{{ ! empty($showIntro) ? '1' : '0' }}" class="{{ ! empty($showIntro) ? '' : 'is-hidden' }}">
    <div class="intro-scene is-active" data-stage="0">
        <span class="blob b-red bf1" style="width:60vw;height:60vw;left:-10%;top:-14%"></span>
        <span class="blob b-org bf2" style="width:50vw;height:50vw;right:-8%;top:0"></span>
        <span class="blob b-grn bf3" style="width:44vw;height:44vw;left:14%;bottom:-16%"></span>
        <span class="blob b-blu bf2" style="width:36vw;height:36vw;right:16%;bottom:-12%"></span>
    </div>
    <div class="intro-scene" data-stage="1">
        <span class="blob b-blu bf1" style="width:58vw;height:58vw;left:-8%;top:-10%"></span>
        <span class="blob b-grn bf2" style="width:52vw;height:52vw;right:-10%;top:6%"></span>
        <span class="blob b-org bf3" style="width:40vw;height:40vw;left:20%;bottom:-14%"></span>
        <span class="blob b-red bf1" style="width:34vw;height:34vw;right:18%;bottom:-10%"></span>
    </div>
    <div class="intro-scene" data-stage="2">
        <span class="blob b-red bf2" style="width:46vw;height:46vw;left:-6%;top:-10%"></span>
        <span class="blob b-org bf1" style="width:44vw;height:44vw;right:-4%;top:-6%"></span>
        <span class="blob b-grn bf3" style="width:48vw;height:48vw;left:8%;bottom:-18%"></span>
        <span class="blob b-blu bf2" style="width:46vw;height:46vw;right:6%;bottom:-16%"></span>
        <span class="blob b-org bf3" style="width:30vw;height:30vw;left:42%;top:30%"></span>
    </div>
    <div class="intro-veil"></div>

    <div class="intro-leaves" aria-hidden="true">
        <span class="leaf" style="left:9%;--sz:28px;--dur:15s;--delay:-1s;--sway:30px"></span>
        <span class="leaf gold" style="left:25%;--sz:22px;--dur:18s;--delay:-7s;--sway:22px"></span>
        <span class="leaf" style="left:43%;--sz:30px;--dur:14s;--delay:-3s;--sway:32px"></span>
        <span class="leaf gold" style="left:61%;--sz:24px;--dur:17s;--delay:-10s;--sway:24px"></span>
        <span class="leaf" style="left:78%;--sz:28px;--dur:15.5s;--delay:-5s;--sway:28px"></span>
        <span class="leaf gold" style="left:91%;--sz:20px;--dur:19s;--delay:-12s;--sway:18px"></span>
        <span class="leaf" style="left:35%;--sz:18px;--dur:20s;--delay:-14s;--sway:16px"></span>
    </div>

    <div class="intro-logos">
        <img class="il-ds" src="{{ asset('logos/logo_ds.webp') }}" alt="Deli Serdang" />
        <div class="il-row">
            <img class="il-hut" src="{{ asset('logos/hutds80.png') }}" alt="HUT Ke-80 Deli Serdang" />
            <img class="il-hut" src="{{ asset('logos/logo_hut26.webp') }}" alt="HUT Ke-26 APKASI" />
        </div>
    </div>

    <button type="button" id="intro-skip" class="intro-skip"><i data-lucide="x" class="w-3.5 h-3.5"></i> Lewati</button>

    <div class="intro-wrap">
        <div class="intro-cap is-active" data-stage="0">
            <span class="intro-badge"><i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Portal Resmi 2026</span>
            <h2>Selamat Datang</h2>
            <p>Di rangkaian perayaan <strong>HUT Ke-26 APKASI</strong> &amp; <strong>HUT Ke-80 Kabupaten Deli Serdang</strong>.</p>
        </div>
        <div class="intro-cap" data-stage="1">
            <span class="intro-badge"><i data-lucide="handshake" class="w-3.5 h-3.5"></i> Sinergi Daerah</span>
            <h2>Bersinergi <span class="accent">Membangun Daerah</span></h2>
            <p>Memperkuat otonomi Indonesia bersama pemerintah kabupaten se-Nusantara.</p>
        </div>
        <div class="intro-cap" data-stage="2">
            <span class="intro-badge"><i data-lucide="calendar-days" class="w-3.5 h-3.5"></i> 1 – 3 Juli 2026</span>
            <h2>Kabupaten <span class="accent">Deli Serdang</span></h2>
            <p>Tuan rumah perayaan akbar di Sumatera Utara. Mari mulai pengalamannya.</p>
            <button type="button" id="intro-enter" class="intro-enter">Masuk ke Beranda <i data-lucide="arrow-down" class="w-4 h-4"></i></button>
        </div>
    </div>

    <div class="intro-dots">
        <span class="intro-dot is-active" data-stage="0"></span>
        <span class="intro-dot" data-stage="1"></span>
        <span class="intro-dot" data-stage="2"></span>
    </div>
    <div class="intro-hint" id="intro-hint"><span id="intro-hint-text">Gulir untuk lanjut</span><i data-lucide="chevrons-down" class="w-5 h-5"></i></div>
</section>
<noscript><style>#intro{display:none!important}</style></noscript>

<div class="min-h-screen bg-apkasi-cream">

    {{-- ═══════════ NAVBAR ═══════════ --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 py-3 sm:py-4">
        <div id="nav-inner" class="mx-auto flex items-center justify-between transition-all duration-500 max-w-[1400px] mx-4 sm:mx-6 lg:mx-auto px-4 sm:px-6 bg-white/70 backdrop-blur-md rounded-full shadow-sm border border-white/60 py-2">
            <a href="{{ route('home') }}" class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                @foreach ($navbarLogos as $logo)
                    <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" class="h-8 sm:h-9 w-auto object-contain" />
                @endforeach
            </a>

            <div class="hidden lg:flex items-center gap-1 absolute left-1/2 -translate-x-1/2">
                <a href="#tentang" class="text-sm px-4 py-2 rounded-full transition-colors duration-200 font-semibold text-apkasi-dark">Tentang</a>
                <a href="#agenda" class="text-sm px-4 py-2 rounded-full transition-colors duration-200 font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5">Agenda</a>
                <a href="#poi" class="text-sm px-4 py-2 rounded-full transition-colors duration-200 font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5">Putri Otonomi</a>
                <a href="{{ route('guide') }}" class="text-sm px-4 py-2 rounded-full transition-colors duration-200 font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5">Panduan</a>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <a href="{{ route('peta-hotel') }}" class="hidden md:inline-flex items-center gap-1.5 text-sm font-medium text-apkasi-body hover:text-apkasi-dark transition-colors">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Peta & Hotel
                </a>
                <a href="https://portal.deliserdangkab.go.id/" target="_blank" rel="noopener" class="hidden sm:inline-flex items-center gap-1.5 bg-apkasi-dark hover:bg-apkasi-hover text-white text-sm font-semibold px-5 py-2 rounded-full transition-colors">
                    <i data-lucide="globe" class="w-3.5 h-3.5"></i> Portal DS
                </a>
                <button id="menu-btn" class="lg:hidden relative flex items-center justify-center w-9 h-9 rounded-full bg-apkasi-dark/5 hover:bg-apkasi-dark/10 text-apkasi-dark transition-all duration-300" aria-label="Menu">
                    <span class="menu-ico absolute"><i data-lucide="menu" class="w-4 h-4"></i></span>
                    <span class="x-ico absolute hidden"><i data-lucide="x" class="w-4 h-4"></i></span>
                </button>
            </div>
        </div>
    </nav>

    {{-- ═══════════ MOBILE OVERLAY + DRAWER ═══════════ --}}
    <div id="mobile-overlay" class="lg:hidden fixed inset-0 z-40 transition-opacity duration-300 opacity-0 pointer-events-none">
        <div class="absolute inset-0 bg-apkasi-dark/40 backdrop-blur-sm"></div>
    </div>
    <div id="mobile-drawer" class="lg:hidden fixed top-0 right-0 bottom-0 z-40 w-[82%] max-w-sm bg-white/95 backdrop-blur-xl shadow-2xl transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] translate-x-full">
        <div class="flex flex-col h-full pt-20 px-6 pb-8">
            <div class="flex items-center gap-3 mb-8 pb-6 border-b border-apkasi-leaf">
                <img src="{{ asset('logos/apkasi-logo.png') }}" alt="APKASI" class="h-10" />
                <img src="{{ asset('logos/hut-apkasi.png') }}" alt="HUT" class="h-10" />
            </div>
            <div class="flex flex-col gap-1">
                <a href="#tentang" class="m-link text-xl font-semibold text-apkasi-dark py-3 border-b border-apkasi-dark/5">Tentang</a>
                <a href="#agenda" class="m-link text-xl font-semibold text-apkasi-dark py-3 border-b border-apkasi-dark/5">Agenda</a>
                <a href="#poi" class="m-link text-xl font-semibold text-apkasi-dark py-3 border-b border-apkasi-dark/5">Putri Otonomi</a>
                <a href="{{ route('guide') }}" class="m-link text-xl font-semibold text-apkasi-dark py-3 border-b border-apkasi-dark/5">Panduan</a>
            </div>
            <div class="mt-8 flex flex-col gap-3">
                <a href="{{ route('peta-hotel') }}" class="flex items-center gap-2 text-sm font-medium text-apkasi-body">
                    <i data-lucide="map-pin" class="w-4 h-4"></i> Peta Lokasi & Hotel
                </a>
                <a href="https://portal.deliserdangkab.go.id/" target="_blank" rel="noopener" class="mt-2 bg-apkasi-dark hover:bg-apkasi-hover text-white text-sm font-semibold px-5 py-3 rounded-full transition-colors text-center">
                    Portal DS
                </a>
            </div>
        </div>
    </div>

    {{-- ═══════════ HERO ═══════════ --}}
    <section id="home" class="relative w-full min-h-[100svh] overflow-hidden flex flex-col">
        {{-- Latar belakang hero: gambar (default, kenburns) atau video (mute, loop) — diatur di /admin → Landing → Hero --}}
        <div class="absolute inset-0 w-full h-full bg-apkasi-dark">
            @if ($g('lp_hero_bg_type', 'image') === 'video')
                <video class="absolute inset-0 w-full h-full object-cover" autoplay muted loop playsinline preload="auto"
                       style="filter: blur(5px); opacity: .55; transform: scale(1.08);"
                       poster="{{ $img('lp_hero_bg_image', 'logos/hero-bg.png') }}">
                    <source src="{{ $img('lp_hero_bg_video', 'assets/apkasi/mars-hero.mp4') }}" type="video/mp4" />
                </video>
            @else
                <img src="{{ $img('lp_hero_bg_image', 'logos/hero-bg.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover" style="animation: kenburns 25s ease-in-out infinite alternate" />
            @endif
        </div>

        <div class="absolute inset-0 overflow-hidden pointer-events-none" style="mix-blend-mode: overlay">
            <div class="hero-splash splash-red"></div>
            <div class="hero-splash splash-blue"></div>
            <div class="hero-splash splash-green"></div>
            <div class="hero-splash splash-orange"></div>
        </div>
        <style>
            @keyframes kenburns { 0% { transform: scale(1) translate(0,0); } 100% { transform: scale(1.1) translate(-1%,-0.8%); } }
            .hero-splash { position:absolute; border-radius:50%; filter:blur(80px); opacity:.45; will-change:transform,opacity; }
            .splash-red { width:45vw; height:45vw; background:radial-gradient(circle, rgba(220,30,30,.8), rgba(180,10,10,.3) 60%, transparent 80%); top:-10%; left:-10%; animation:splashFloat1 14s ease-in-out infinite alternate, splashPulse 8s ease-in-out infinite; }
            .splash-blue { width:40vw; height:40vw; background:radial-gradient(circle, rgba(0,100,255,.8), rgba(0,150,255,.3) 60%, transparent 80%); bottom:-8%; left:15%; animation:splashFloat2 16s ease-in-out infinite alternate, splashPulse 10s ease-in-out infinite 2s; }
            .splash-green { width:38vw; height:38vw; background:radial-gradient(circle, rgba(20,180,60,.8), rgba(0,150,50,.3) 60%, transparent 80%); top:5%; right:-8%; animation:splashFloat3 18s ease-in-out infinite alternate, splashPulse 9s ease-in-out infinite 4s; }
            .splash-orange { width:35vw; height:35vw; background:radial-gradient(circle, rgba(255,150,0,.8), rgba(240,120,0,.3) 60%, transparent 80%); bottom:-5%; right:-5%; animation:splashFloat4 15s ease-in-out infinite alternate, splashPulse 7s ease-in-out infinite 1s; }
            @keyframes splashFloat1 { 0%{transform:translate(0,0) scale(1);} 100%{transform:translate(12vw,8vh) scale(1.15);} }
            @keyframes splashFloat2 { 0%{transform:translate(0,0) scale(1);} 100%{transform:translate(8vw,-10vh) scale(1.1);} }
            @keyframes splashFloat3 { 0%{transform:translate(0,0) scale(1);} 100%{transform:translate(-10vw,6vh) scale(1.2);} }
            @keyframes splashFloat4 { 0%{transform:translate(0,0) scale(1);} 100%{transform:translate(-6vw,-8vh) scale(1.1);} }
            @keyframes splashPulse { 0%,100%{opacity:.35;} 50%{opacity:.55;} }
            @keyframes mascotFloat { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-10px);} }
            /* Teks hero: putih + outline hitam tipis (stroke di belakang fill agar huruf tetap penuh) */
            .hero-title { color:#fff; line-height: 1.2; paint-order: stroke fill; -webkit-text-stroke: 1.6px rgba(0,0,0,.55); text-shadow: 0 2px 12px rgba(0,0,0,.35); }
            /* Baris aksen: hijau apkasi + outline putih tipis */
            .hero-accent { color:#85AB8B; line-height: 1.2; paint-order: stroke fill; -webkit-text-stroke: 1.4px rgba(255,255,255,.9); text-shadow: 0 2px 12px rgba(0,0,0,.3); }
            .hero-subtitle { color:#fff; paint-order: stroke fill; -webkit-text-stroke: .6px rgba(0,0,0,.6); text-shadow: 0 1px 5px rgba(0,0,0,.45); }
            @media (max-width: 640px){ .hero-title { -webkit-text-stroke-width: 1px; } .hero-accent { -webkit-text-stroke-width: 1px; } }
            /* Headline berganti versi A↔B (cross-fade) — kedua versi (putih+hijau) ditumpuk di sel grid yg sama */
            #hlTop { display: grid; place-items: center; }
            #hlTop > * { grid-area: 1 / 1; margin: 0; transition: opacity .7s ease; }
            .hl-a:not(.is-on), .hl-b:not(.is-on) { opacity: 0; pointer-events: none; }
        </style>

        <div class="absolute inset-0 bg-gradient-to-b from-apkasi-dark/40 via-apkasi-dark/15 to-apkasi-dark/70 pointer-events-none"></div>

        <div class="relative z-10 flex-1 flex flex-col items-center justify-center text-center px-5 sm:px-8 pt-24 sm:pt-28 pb-28 sm:pb-32">
            @php
                $leaderImg   = $g('lp_hero_leaders_img');
                $showLeaders = ($g('lp_hero_leaders_enabled', '1') !== '0') && $leaderImg;
                $firstLogo   = $heroLogosV1->count() ? 'v1' : ($heroLogosV2->count() ? 'v2' : ($heroLogosV3->count() ? 'v3' : null));
                $hasLogos    = $heroLogosV1->count() || $heroLogosV2->count() || $heroLogosV3->count();
            @endphp

            {{-- ═══ ATAS TENGAH: headline lengkap (PUTIH + HIJAU jadi satu) bergantian versi A ⇄ B ═══ --}}
            <div id="hlTop" class="w-full max-w-4xl mx-auto">
                {{-- Versi A --}}
                <div class="hl-a is-on">
                    <div class="font-display font-bold hero-title text-[1.7rem] sm:text-4xl md:text-[2.75rem] lg:text-[3rem] xl:text-[3.4rem] tracking-tight">
                        {{ $g('lp_hero_leaders_headline', 'Selamat Datang di Deli Serdang') }}
                    </div>
                    <p class="font-display font-bold hero-accent text-base sm:text-xl md:text-2xl lg:text-[1.95rem] leading-snug tracking-tight mt-2 sm:mt-3">
                        {{ $g('lp_hero_leaders_accent', 'Para Delegasi dan Pimpinan Kabupaten Se-Nusantara') }}
                    </p>
                </div>
                {{-- Versi B --}}
                <div class="hl-b">
                    <h1 class="font-display font-bold hero-title text-[1.7rem] sm:text-4xl md:text-[2.75rem] lg:text-[3rem] xl:text-[3.4rem] tracking-tight">
                        {{ $g('lp_hero_headline', 'Bersinergi Membangun Daerah') }}
                    </h1>
                    <p class="font-display font-bold hero-accent text-base sm:text-xl md:text-2xl lg:text-[1.95rem] leading-snug tracking-tight mt-2 sm:mt-3">
                        {{ $g('lp_hero_accent', 'Memperkuat Otonomi Indonesia') }}
                    </p>
                </div>
            </div>

            {{-- ═══ TENGAH: KIRI foto Bupati & Wakil (statis, besar) | KANAN logo carousel 3D (gaya PES) ═══ --}}
            <div class="w-full max-w-[1400px] mx-auto grid lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-10 items-center my-4 sm:my-6">
                {{-- KIRI: gambar gabungan Bupati & Wakil — statis (tidak berganti), full width --}}
                <div class="flex items-center justify-center order-1 min-w-0">
                    @if ($showLeaders)
                        <img src="{{ $img('lp_hero_leaders_img') }}" alt="Bupati &amp; Wakil Bupati Deli Serdang"
                             class="w-full max-w-[42rem] lg:max-w-none max-h-[42vh] sm:max-h-[50vh] md:max-h-[54vh] object-contain"
                             style="filter: drop-shadow(0 16px 30px rgba(0,0,0,.55))" />
                    @endif
                </div>
                {{-- KANAN: logo carousel, dimiringkan 3D (perspektif) --}}
                <div class="hero-3d flex items-center justify-center order-2 min-w-0">
                    @if ($hasLogos)
                    <style>
                        /* perspective() ditaruh DI DALAM transform tiap slide agar 3D-nya kena (bukan di parent/cucu) */
                        .hero-lslide { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; gap: clamp(1rem, 3vw, 3rem); opacity: 0; transform: perspective(820px) rotateY(-25deg) rotateX(7deg) scale(.88); transition: opacity 1s ease, transform 1.1s cubic-bezier(.2,.8,.2,1); pointer-events: none; transform-origin: 60% center; }
                        .hero-lslide.is-on { opacity: 1; transform: perspective(820px) rotateY(-25deg) rotateX(7deg) scale(1); }
                        .hero-lslide img { max-width: 46vw; filter: drop-shadow(18px 20px 22px rgba(0,0,0,.6)); }
                        @media (min-width:1024px){ .hero-lslide img { max-width: 27vw; } }
                    </style>
                    <div class="hero-logos relative w-full flex items-center justify-center min-h-[200px] sm:min-h-[250px] md:min-h-[300px] lg:min-h-[350px]">
                        @if ($heroLogosV1->count())
                        <div class="hero-lslide {{ $firstLogo === 'v1' ? 'is-on' : '' }}">
                            @foreach ($heroLogosV1 as $logo)
                                <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" class="{{ str_contains($logo->gambar, 'hutds80') ? 'h-40 sm:h-48 md:h-60 lg:h-72' : 'h-32 sm:h-40 md:h-48 lg:h-60' }} w-auto object-contain" />
                            @endforeach
                        </div>
                        @endif
                        @if ($heroLogosV2->count())
                        <div class="hero-lslide {{ $firstLogo === 'v2' ? 'is-on' : '' }}">
                            @foreach ($heroLogosV2 as $logo)
                                <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" class="{{ str_contains($logo->gambar, 'hutds80') ? 'h-40 sm:h-48 md:h-60 lg:h-72' : 'h-32 sm:h-40 md:h-48 lg:h-60' }} w-auto object-contain" />
                            @endforeach
                        </div>
                        @endif
                        @if ($heroLogosV3->count())
                        <div class="hero-lslide {{ $firstLogo === 'v3' ? 'is-on' : '' }}">
                            @foreach ($heroLogosV3 as $logo)
                                <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" class="{{ str_contains($logo->gambar, 'hutds80') ? 'h-40 sm:h-48 md:h-60 lg:h-72' : 'h-32 sm:h-40 md:h-48 lg:h-60' }} w-auto object-contain" />
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            {{-- ═══ BAWAH TENGAH: sub judul, lokasi, countdown, tombol ═══ --}}
            <p class="mt-5 sm:mt-7 hero-subtitle text-sm sm:text-base md:text-lg leading-relaxed max-w-xl font-semibold">
                {{ $g('lp_hero_subtitle', 'HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang') }}
                <br class="hidden sm:block" />
                {{ $eventRangeText }}
            </p>

            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mt-4 sm:mt-5">
                <i data-lucide="map-pin" class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-apkasi-goldlt"></i>
                <span class="text-white/90 text-[11px] sm:text-xs font-medium tracking-wide">{{ $g('lp_hero_location', 'Kabupaten Deli Serdang, Sumatera Utara') }}</span>
            </div>

            {{-- Countdown --}}
            <div class="mt-6 sm:mt-8 flex items-center justify-center gap-2 sm:gap-3">
                @foreach (['days' => 'Hari', 'hours' => 'Jam', 'mins' => 'Menit', 'secs' => 'Detik'] as $key => $label)
                    @if (!$loop->first)
                        <span class="text-white/30 text-xl sm:text-2xl font-light">:</span>
                    @endif
                    <div class="flex flex-col items-center bg-white/10 backdrop-blur-sm border border-white/15 rounded-xl sm:rounded-2xl px-3 sm:px-5 py-2.5 sm:py-3.5 min-w-[58px] sm:min-w-[72px]">
                        <span id="cd-{{ $key }}" class="text-2xl sm:text-3xl md:text-4xl font-bold leading-none tabular-nums text-apkasi-gold" style="letter-spacing:-0.03em">00</span>
                        <span class="mt-1 text-[9px] sm:text-[10px] font-semibold uppercase tracking-[0.12em] text-white/55">{{ $label }}</span>
                    </div>
                @endforeach
            </div>

            <div class="mt-7 sm:mt-9 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                <a href="#agenda" class="w-full sm:w-auto bg-apkasi-gold hover:bg-apkasi-goldlt text-apkasi-dark text-sm font-bold px-7 py-3 sm:py-3.5 rounded-full transition-colors shadow-lg text-center">
                    Lihat Jadwal Agenda
                </a>
                <a href="{{ route('peta-hotel') }}" class="w-full sm:w-auto border-2 border-white/40 hover:border-white/70 text-white text-sm font-semibold px-7 py-3 sm:py-3.5 rounded-full transition-colors text-center">
                    Peta Lokasi & Hotel
                </a>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 z-10 px-5 sm:px-8 md:px-10 pb-5 sm:pb-7 flex items-end justify-between">
            <div class="max-w-xs hidden sm:block">
                <div class="flex items-center gap-2 text-white/85 mb-2">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    <span class="text-xs font-semibold tracking-wide">{{ $g('lp_hero_tagline_title', 'Portal Resmi HUT APKASI 2026') }}</span>
                </div>
                <p class="text-white/60 text-[11px] leading-relaxed">
                    {{ $g('lp_hero_tagline_desc', 'Informasi agenda, panduan delegasi, akomodasi, dan peta lokasi selama rangkaian kegiatan di Deli Serdang.') }}
                </p>
            </div>
            <div class="flex items-center gap-2 text-white/70 text-xs ml-auto">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                <span class="font-medium">{{ $eventRangeText }}</span>
                <span class="text-white/40">·</span>
                <span class="text-white/50">{{ $g('lp_hero_footer_place', 'Deli Serdang') }}</span>
            </div>
        </div>

        <img src="{{ asset('logos/maskot.png') }}" alt="Maskot APKASI" class="absolute z-20 right-4 sm:right-8 md:right-12 bottom-16 sm:bottom-20 h-28 sm:h-36 md:h-44 lg:h-52 w-auto object-contain drop-shadow-2xl" style="animation: mascotFloat 3.5s ease-in-out infinite" />
    </section>

    {{-- ═══════════ LOGO PARTNERS ═══════════ --}}
    <div class="py-8 sm:py-10 bg-white border-b border-apkasi-leaf">
        <div class="max-w-[1400px] mx-auto px-5 sm:px-8">
            <p class="text-[10px] sm:text-xs text-apkasi-body/60 font-semibold uppercase tracking-[0.15em] text-center mb-6">{{ $g('lp_partners_title', 'Kolaborasi Penyelenggara') }}</p>
            <div class="flex items-center justify-center gap-6 sm:gap-10 md:gap-14 flex-wrap">
                @foreach ($partnerLogos as $logo)
                    <div class="flex items-center justify-center px-2 py-1 opacity-80 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0">
                        <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" loading="lazy" decoding="async" class="h-12 sm:h-14 w-auto object-contain" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══════════ BUPATI & WAKIL BUPATI ═══════════ --}}
    <section id="pimpinan" class="py-16 sm:py-20 md:py-28 bg-gradient-to-b from-white to-apkasi-cream">
        <div data-reveal class="max-w-[1400px] mx-auto px-5 sm:px-8 transition-all duration-700 opacity-0 translate-y-8">
            <div class="text-center mb-10 sm:mb-14">
                <span class="inline-flex items-center gap-1.5 bg-apkasi-heading/8 text-apkasi-heading text-xs sm:text-sm font-semibold tracking-wide uppercase px-4 py-1.5 rounded-full mb-4">{{ $g('lp_pimpinan_badge', 'Pimpinan Daerah Tuan Rumah') }}</span>
                <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-apkasi-dark leading-tight mb-3">{{ $g('lp_pimpinan_heading', 'Kabupaten Deli Serdang') }}</h2>
                <p class="text-apkasi-body text-sm sm:text-base max-w-md mx-auto">{{ $g('lp_pimpinan_sub', 'Menyambut seluruh delegasi APKASI dalam rangkaian peringatan HUT Ke-80 Kabupaten Deli Serdang.') }}</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-8 sm:gap-12 md:gap-20">
                <div class="group flex flex-col items-center text-center max-w-xs">
                    <div class="relative mb-5">
                        <div class="w-44 h-44 sm:w-52 sm:h-52 md:w-60 md:h-60 rounded-full overflow-hidden border-4 border-apkasi-gold/30 shadow-xl group-hover:border-apkasi-gold transition-colors duration-500">
                            <img src="{{ asset($g('lp_bupati_foto', 'assets/apkasi/z_04_LOGO-LOGO APKASI/BUPATI.png')) }}" alt="{{ $g('lp_bupati_nama', 'Bupati') }}" loading="lazy" decoding="async" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" />
                        </div>
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-gradient-to-r from-apkasi-gold to-[#c5a028] text-apkasi-dark text-[10px] sm:text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full shadow-md whitespace-nowrap">Bupati</div>
                    </div>
                    <h3 class="font-display text-lg sm:text-xl font-bold text-apkasi-dark mt-2">{{ $g('lp_bupati_nama', 'H. Ali Yusuf Siregar, S.Sos.') }}</h3>
                    <p class="text-apkasi-body text-xs sm:text-sm mt-1">{{ $g('lp_bupati_jabatan', 'Bupati Deli Serdang') }}<br/>{{ $g('lp_bupati_periode', 'Periode 2024–2029') }}</p>
                    <div class="mt-3 w-10 h-0.5 bg-apkasi-gold/40 rounded-full group-hover:w-16 transition-all duration-500"></div>
                </div>

                <div class="hidden sm:block w-px h-48 bg-gradient-to-b from-transparent via-apkasi-dark/15 to-transparent"></div>

                <div class="group flex flex-col items-center text-center max-w-xs">
                    <div class="relative mb-5">
                        <div class="w-44 h-44 sm:w-52 sm:h-52 md:w-60 md:h-60 rounded-full overflow-hidden border-4 border-apkasi-gold/30 shadow-xl group-hover:border-apkasi-gold transition-colors duration-500">
                            <img src="{{ asset($g('lp_wabup_foto', 'assets/apkasi/z_04_LOGO-LOGO APKASI/WABUPATI.png')) }}" alt="{{ $g('lp_wabup_nama', 'Wakil Bupati') }}" loading="lazy" decoding="async" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" />
                        </div>
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-gradient-to-r from-apkasi-gold to-[#c5a028] text-apkasi-dark text-[10px] sm:text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full shadow-md whitespace-nowrap">Wakil Bupati</div>
                    </div>
                    <h3 class="font-display text-lg sm:text-xl font-bold text-apkasi-dark mt-2">{{ $g('lp_wabup_nama', 'HM. Yusuf Siregar, S.E.') }}</h3>
                    <p class="text-apkasi-body text-xs sm:text-sm mt-1">{{ $g('lp_wabup_jabatan', 'Wakil Bupati Deli Serdang') }}<br/>{{ $g('lp_wabup_periode', 'Periode 2024–2029') }}</p>
                    <div class="mt-3 w-10 h-0.5 bg-apkasi-gold/40 rounded-full group-hover:w-16 transition-all duration-500"></div>
                </div>
            </div>

            <div class="mt-12 sm:mt-16 max-w-2xl mx-auto text-center">
                <blockquote class="relative">
                    <span class="absolute -top-4 -left-2 text-5xl sm:text-6xl text-apkasi-gold/20 font-serif leading-none">&ldquo;</span>
                    <p class="text-apkasi-heading text-sm sm:text-base md:text-lg leading-relaxed italic font-medium px-6">
                        {{ $g('lp_pimpinan_quote', 'Kami sangat bangga menjadi tuan rumah HUT APKASI Ke-26. Deli Serdang siap menyambut seluruh Bupati dan perwakilan kabupaten se-Indonesia untuk bersinergi membangun daerah.') }}
                    </p>
                    <span class="absolute -bottom-6 -right-2 text-5xl sm:text-6xl text-apkasi-gold/20 font-serif leading-none rotate-180">&ldquo;</span>
                </blockquote>
                <p class="mt-6 text-xs sm:text-sm text-apkasi-body font-semibold">&mdash; {{ $g('lp_pimpinan_quote_author', 'Bupati Deli Serdang') }}</p>
            </div>
        </div>
    </section>

    {{-- ═══════════ ABOUT ═══════════ --}}
    <section id="tentang" class="py-16 sm:py-20 md:py-28 bg-white">
        <div data-reveal class="max-w-[1400px] mx-auto px-5 sm:px-8 transition-all duration-700 opacity-0 translate-y-8">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <div>
                    <span class="inline-flex items-center gap-1.5 bg-apkasi-heading/8 text-apkasi-heading text-xs sm:text-sm font-semibold tracking-wide uppercase px-4 py-1.5 rounded-full mb-4">{{ $g('lp_about_badge', 'Tentang Event') }}</span>
                    <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-apkasi-dark leading-tight mb-5">{{ $g('lp_about_heading', 'Dua Hari Jadi Besar, Satu Tekad Bersinergi') }}</h2>
                    <p class="text-apkasi-body text-sm sm:text-base leading-relaxed mb-4">
                        {{ $g('lp_about_p1', 'Rangkaian ini memperingati HUT APKASI (Asosiasi Pemerintah Kabupaten Seluruh Indonesia) Ke-26 sekaligus HUT Kabupaten Deli Serdang Ke-80, dengan tema besar:') }}
                    </p>
                    <blockquote class="border-l-4 border-apkasi-gold pl-4 sm:pl-5 my-5 sm:my-6">
                        <p class="text-apkasi-heading font-semibold text-base sm:text-lg italic leading-relaxed">{{ $g('lp_about_quote', '"Penguatan Sinergi Antar Pemerintah Kabupaten Dalam Mendukung Pembangunan Daerah dan Otonomi Daerah."') }}</p>
                    </blockquote>
                    <p class="text-apkasi-body text-sm sm:text-base leading-relaxed mb-6">
                        {{ $g('lp_about_p2', 'Pemerintah Kabupaten Deli Serdang, Sumatera Utara, menyambut perwakilan dari seluruh pemerintah kabupaten di Indonesia untuk membahas strategi pembiayaan alternatif, kemandirian ekonomi lokal, dan peran perempuan dalam pemberantasan stunting.') }}
                    </p>

                    @php
                        $aboutStats = [
                            ['users', $g('lp_about_stat1_label', '400+ Delegasi'), $g('lp_about_stat1_sub', 'Bupati se-Indonesia')],
                            ['building-2', $g('lp_about_stat2_label', '3 Venue Utama'), $g('lp_about_stat2_sub', 'Deli Serdang')],
                            ['trophy', $g('lp_about_stat3_label', 'Grand Final POI'), $g('lp_about_stat3_sub', 'Putri Otonomi 2026')],
                            ['heart', $g('lp_about_stat4_label', 'Women Program'), $g('lp_about_stat4_sub', 'UMKM & Stunting')],
                        ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach ($aboutStats as $s)
                            <div class="bg-apkasi-leaf/50 rounded-xl p-3 sm:p-4 text-center">
                                <i data-lucide="{{ $s[0] }}" class="w-5 h-5 text-apkasi-heading mx-auto mb-2"></i>
                                <p class="text-xs sm:text-sm font-bold text-apkasi-dark leading-tight">{{ $s[1] }}</p>
                                <p class="text-[10px] sm:text-xs text-apkasi-body mt-0.5">{{ $s[2] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg aspect-[4/3]">
                        <img src="{{ $img('lp_about_image', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=900&q=80') }}" alt="{{ $g('lp_about_heading', 'Tentang Event') }}" loading="lazy" decoding="async" class="w-full h-full object-cover" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-apkasi-heading rounded-xl sm:rounded-2xl p-4 sm:p-5 text-white">
                            <i data-lucide="calendar" class="w-5 h-5 text-apkasi-accent mb-2"></i>
                            <p class="text-sm font-bold">{{ $eventRangeText }}</p>
                            <p class="text-xs text-white/70 mt-0.5">Rangkaian acara</p>
                        </div>
                        <div class="bg-apkasi-dark rounded-xl sm:rounded-2xl p-4 sm:p-5 text-white">
                            <i data-lucide="map-pin" class="w-5 h-5 text-apkasi-gold mb-2"></i>
                            <p class="text-sm font-bold">{{ $g('lp_hero_footer_place', 'Deli Serdang') }}</p>
                            <p class="text-xs text-white/70 mt-0.5">Sumatera Utara</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ AGENDA ═══════════ --}}
    <section id="agenda" class="py-16 sm:py-20 md:py-28 bg-gradient-to-b from-apkasi-cream to-apkasi-leaf/30">
        <div data-reveal class="max-w-[1400px] mx-auto px-5 sm:px-8 transition-all duration-700 opacity-0 translate-y-8">
            <div class="text-center mb-12 sm:mb-16">
                <span class="inline-flex items-center gap-1.5 bg-apkasi-heading/8 text-apkasi-heading text-xs sm:text-sm font-semibold tracking-wide uppercase px-4 py-1.5 rounded-full mb-4">Jadwal Acara</span>
                <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-apkasi-dark leading-tight mb-3">Rangkaian Acara 1 – 3 Juli 2026</h2>
                <p class="text-apkasi-body text-sm sm:text-base max-w-lg mx-auto">Informasi waktu, tempat, dan detail kegiatan selama rangkaian hari jadi berlangsung.</p>
            </div>

            <div class="space-y-6 sm:space-y-8">
                @forelse ($agenda as $day)
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-apkasi-leaf overflow-hidden">
                        <div class="flex items-center gap-3 sm:gap-4 px-5 sm:px-7 py-4 sm:py-5 bg-apkasi-dark text-white">
                            <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-white flex items-center justify-center shrink-0 p-1.5 shadow-sm">
                                <img src="{{ asset($dayImages[$loop->index % count($dayImages)]) }}" alt="" loading="lazy" decoding="async" class="w-full h-full object-contain" />
                            </div>
                            <div>
                                <p class="text-xs font-bold tracking-wide text-apkasi-accent uppercase">{{ $day->label ?: 'Hari ' . $loop->iteration }}</p>
                                <p class="text-sm sm:text-base font-semibold">{{ $day->tanggal_format }}</p>
                            </div>
                        </div>
                        <div class="divide-y divide-apkasi-leaf">
                            @forelse ($day->kegiatan as $ev)
                                <div class="flex flex-col sm:flex-row gap-3 sm:gap-6 px-5 sm:px-7 py-4 sm:py-5 hover:bg-apkasi-leaf/20 transition-colors">
                                    <div class="flex items-center gap-2 sm:w-44 shrink-0">
                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-apkasi-heading shrink-0"></i>
                                        <span class="text-xs sm:text-sm font-semibold text-apkasi-heading whitespace-nowrap">{{ $ev->waktu }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm sm:text-base font-bold text-apkasi-dark leading-snug mb-1">{{ $ev->kegiatan }}</h4>
                                        @if ($ev->rincian)
                                            <p class="text-xs sm:text-sm text-apkasi-body leading-relaxed">{{ $ev->rincian }}</p>
                                        @endif
                                        @if ($ev->lokasi)
                                            <div class="flex items-center gap-1.5 mt-2 text-apkasi-heading">
                                                <i data-lucide="map-pin" class="w-3 h-3"></i>
                                                <span class="text-xs font-semibold">{{ $ev->lokasi }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="px-5 sm:px-7 py-4 text-sm text-apkasi-body/60">Belum ada kegiatan.</div>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-sm text-apkasi-body/60">Rundown belum tersedia.</div>
                @endforelse
            </div>

            <div class="text-center mt-8 sm:mt-10">
                <a href="{{ route('peta-hotel') }}" class="inline-flex items-center gap-2 bg-apkasi-heading hover:bg-apkasi-cta text-white text-sm font-semibold px-7 py-3.5 rounded-full transition-colors shadow-md">
                    Lihat Peta Lokasi & Hotel <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════ POI ═══════════ --}}
    <section id="poi" class="py-16 sm:py-20 md:py-28 bg-white">
        <div data-reveal class="max-w-[1400px] mx-auto px-5 sm:px-8 transition-all duration-700 opacity-0 translate-y-8">
            <div class="bg-gradient-to-br from-apkasi-dark via-[#223d2c] to-apkasi-cta rounded-2xl sm:rounded-3xl overflow-hidden shadow-xl">
                <div class="grid lg:grid-cols-5 gap-0">
                    <div class="lg:col-span-3 p-7 sm:p-10 md:p-14 flex flex-col justify-center">
                        <span class="inline-flex items-center gap-1.5 bg-apkasi-gold/20 text-apkasi-gold text-xs font-bold tracking-wider uppercase px-3 py-1 rounded-full w-fit mb-5">
                            <i data-lucide="trophy" class="w-3 h-3"></i> {{ $g('lp_poi_badge', 'Special Event') }}
                        </span>
                        <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-white leading-tight mb-4">
                            {{ $g('lp_poi_heading1', 'Malam Grand Final') }}<br />
                            <span class="text-apkasi-accent">{{ $g('lp_poi_heading2', 'Putri Otonomi Indonesia') }}</span> {{ $g('lp_poi_heading3', '2026') }}
                        </h2>
                        <p class="text-white/75 text-sm sm:text-base leading-relaxed mb-6 max-w-lg">
                            {{ $g('lp_poi_desc', 'Ajang bergengsi pemilihan duta otonomi daerah dari seluruh kabupaten di Indonesia. Malam penobatan puncak dilaksanakan Kamis, 2 Juli 2026 di Graha Bhineka.') }}
                        </p>
                        @php
                            $poiInfo = [
                                ['trophy', $g('lp_poi_info1_title', 'Penobatan Juara'), $g('lp_poi_info1_sub', 'Duta Otonomi Nasional')],
                                ['users', $g('lp_poi_info2_title', '400+ Kepala Daerah'), $g('lp_poi_info2_sub', 'Bupati & Tokoh Nasional')],
                                ['calendar', $g('lp_poi_info3_title', 'Kamis, 2 Juli 2026'), $g('lp_poi_info3_sub', '18.30 – 22.00 WIB')],
                                ['map-pin', $g('lp_poi_info4_title', 'Graha Bhineka'), $g('lp_poi_info4_sub', 'Deli Serdang')],
                            ];
                        @endphp
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            @foreach ($poiInfo as $item)
                                <div class="flex items-start gap-3">
                                    <i data-lucide="{{ $item[0] }}" class="w-4 h-4 text-apkasi-gold mt-0.5 shrink-0"></i>
                                    <div>
                                        <p class="text-white text-sm font-semibold">{{ $item[1] }}</p>
                                        <p class="text-white/55 text-xs">{{ $item[2] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="lg:col-span-2 relative min-h-[280px] sm:min-h-[340px]">
                        <img src="{{ $img('lp_poi_image', 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=700&q=80') }}" alt="{{ $g('lp_poi_heading2', 'Putri Otonomi Indonesia') }}" loading="lazy" decoding="async" class="w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-gradient-to-r from-apkasi-dark/50 via-transparent to-transparent lg:block hidden"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════ FAQ ═══════════ --}}
    <section id="faq" class="py-16 sm:py-20 md:py-28 bg-gradient-to-b from-apkasi-cream to-apkasi-leaf/20">
        <div data-reveal class="max-w-5xl mx-auto px-5 sm:px-8 transition-all duration-700 opacity-0 translate-y-8">
            <div class="text-center mb-10 sm:mb-14">
                <span class="inline-flex items-center gap-1.5 bg-apkasi-heading/8 text-apkasi-heading text-xs sm:text-sm font-semibold tracking-wide uppercase px-4 py-1.5 rounded-full mb-4">Panduan Delegasi</span>
                <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-apkasi-dark leading-tight mb-3">Informasi Penting</h2>
                <p class="text-apkasi-body text-sm sm:text-base max-w-md mx-auto">Pertanyaan umum seputar registrasi, dresscode, transportasi, dan logistik selama event.</p>
            </div>

            <style>
                .faq-answer ul { list-style: disc; padding-left: 1.25rem; margin: .25rem 0; }
                .faq-answer ol { list-style: decimal; padding-left: 1.25rem; margin: .25rem 0; }
                .faq-answer li { margin: .15rem 0; }
                .faq-answer a { color: #336443; text-decoration: underline; }
                .faq-answer strong { font-weight: 700; color: #1f2a1d; }
                .faq-answer em { font-style: italic; }
                .faq-answer p { margin: .35rem 0; }
                .faq-answer p:first-child { margin-top: 0; }
                .faq-answer p:last-child { margin-bottom: 0; }
            </style>
            <div class="space-y-3">
                @forelse ($faqs as $f)
                    <div class="bg-white rounded-xl sm:rounded-2xl border border-apkasi-leaf overflow-hidden transition-shadow hover:shadow-sm">
                        <button type="button" class="faq-btn w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-4 sm:py-5 text-left" data-faq="{{ $loop->index }}">
                            <span class="text-sm sm:text-base font-semibold text-apkasi-dark leading-snug">{{ $f->pertanyaan }}</span>
                            <i data-lucide="chevron-down" class="faq-chev w-4 h-4 sm:w-5 sm:h-5 text-apkasi-body shrink-0 transition-transform duration-300"></i>
                        </button>
                        <div class="faq-panel transition-all duration-300 ease-in-out overflow-hidden max-h-0 opacity-0">
                            <div class="faq-answer px-5 sm:px-6 pb-5 text-sm text-apkasi-body leading-relaxed">{!! $f->jawaban !!}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-sm text-apkasi-body/60">Belum ada informasi.</div>
                @endforelse
            </div>

            <div class="mt-10 sm:mt-12 bg-apkasi-dark rounded-2xl sm:rounded-3xl p-6 sm:p-8 text-center text-white">
                <h3 class="font-display text-lg sm:text-xl font-bold mb-2">Butuh Informasi Lebih Lengkap?</h3>
                <p class="text-white/65 text-sm mb-5 max-w-md mx-auto">Akses panduan lengkap peta lokasi event, rekomendasi hotel, destinasi wisata Deli Serdang, dan kontak rental mobil.</p>
                <a href="{{ route('guide') }}" class="inline-flex items-center gap-2 bg-apkasi-gold hover:bg-apkasi-goldlt text-apkasi-dark text-sm font-bold px-7 py-3 rounded-full transition-colors">
                    Buka Panduan Lengkap <i data-lucide="external-link" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════ FOOTER ═══════════ --}}
    <footer class="bg-apkasi-dark border-t-4 border-apkasi-gold">
        <div class="max-w-[1400px] mx-auto px-5 sm:px-8 py-10 sm:py-14">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 mb-10">
                <div class="sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        @foreach ($footerBrandLogos as $logo)
                            <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" loading="lazy" decoding="async" class="h-10 brightness-0 invert" />
                        @endforeach
                    </div>
                    <p class="text-white/50 text-xs leading-relaxed max-w-xs">{{ $g('lp_footer_tagline', 'Asosiasi Pemerintah Kabupaten Seluruh Indonesia. Memperkuat otonomi daerah untuk Indonesia Maju.') }}</p>
                </div>
                <div>
                    <h4 class="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">Navigasi</h4>
                    <div class="space-y-2.5">
                        <a href="#tentang" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Tentang</a>
                        <a href="#agenda" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Agenda</a>
                        <a href="#poi" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Putri Otonomi</a>
                        <a href="{{ route('guide') }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Panduan</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">Panduan</h4>
                    <div class="space-y-2.5">
                        <a href="{{ route('peta-hotel') }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Peta Lokasi Event</a>
                        <a href="{{ route('peta-hotel') }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Rekomendasi Hotel</a>
                        <a href="{{ route('guide') }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Destinasi Wisata</a>
                        <a href="{{ route('guide') }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Rental Mobil</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">Pemerintahan</h4>
                    <p class="text-sm text-white/50 leading-relaxed mb-3">{{ $g('lp_footer_sekretariat', 'Dinas Kominfo Kabupaten Deli Serdang, Sumatera Utara') }}</p>
                    <a href="https://portal.deliserdangkab.go.id/" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-apkasi-gold hover:text-apkasi-goldlt transition-colors">
                        <i data-lucide="globe" class="w-3.5 h-3.5"></i> Portal DS
                    </a>
                </div>
            </div>

            <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-[11px] text-white/30">{{ $g('lp_footer_copyright', '© 2026 Pemerintah Kabupaten Deli Serdang & APKASI. All rights reserved.') }}</p>
                <div class="flex items-center gap-2">
                    @foreach ($footerSideLogos as $logo)
                        <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" loading="lazy" decoding="async" class="h-7 opacity-50" />
                    @endforeach
                </div>
            </div>
        </div>
    </footer>
</div>
@endsection

@push('scripts')
<script>
    // ── Countdown ──
    var EVENT_DATE = new Date('{{ $countdownTarget }}').getTime();
    function pad(n) { return String(n).padStart(2, '0'); }
    function tickCountdown() {
        var d = EVENT_DATE - Date.now();
        if (d < 0) d = 0;
        document.getElementById('cd-days').textContent = pad(Math.floor(d / 864e5));
        document.getElementById('cd-hours').textContent = pad(Math.floor((d / 36e5) % 24));
        document.getElementById('cd-mins').textContent = pad(Math.floor((d / 6e4) % 60));
        document.getElementById('cd-secs').textContent = pad(Math.floor((d / 1e3) % 60));
    }
    tickCountdown();
    setInterval(tickCountdown, 1000);

    // ── Navbar scroll shrink ──
    var navbar = document.getElementById('navbar');
    var navInner = document.getElementById('nav-inner');
    var NAV_BASE = 'fixed top-0 left-0 right-0 z-50 transition-all duration-500';
    var INNER_BASE = 'mx-auto flex items-center justify-between transition-all duration-500 py-2';
    var INNER_TOP = 'max-w-[1400px] mx-4 sm:mx-6 lg:mx-auto px-4 sm:px-6 bg-white/70 backdrop-blur-md rounded-full shadow-sm border border-white/60';
    var INNER_SCR = 'max-w-[95%] xl:max-w-[90%] px-4 sm:px-6 bg-white shadow-lg border border-gray-100 rounded-2xl';
    function onScroll() {
        if (window.scrollY > 40) {
            navbar.className = NAV_BASE + ' py-1.5 sm:py-2';
            navInner.className = INNER_BASE + ' ' + INNER_SCR;
        } else {
            navbar.className = NAV_BASE + ' py-3 sm:py-4';
            navInner.className = INNER_BASE + ' ' + INNER_TOP;
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });

    // ── Mobile menu ──
    var menuOpen = false;
    var menuBtn = document.getElementById('menu-btn');
    var drawer = document.getElementById('mobile-drawer');
    var overlay = document.getElementById('mobile-overlay');
    var menuIco = document.querySelector('#menu-btn .menu-ico');
    var xIco = document.querySelector('#menu-btn .x-ico');
    function setMenu(open) {
        menuOpen = open;
        drawer.classList.toggle('translate-x-full', !open);
        overlay.classList.toggle('opacity-0', !open);
        overlay.classList.toggle('pointer-events-none', !open);
        overlay.classList.toggle('opacity-100', open);
        overlay.classList.toggle('pointer-events-auto', open);
        menuIco.classList.toggle('hidden', open);
        xIco.classList.toggle('hidden', !open);
        document.body.style.overflow = open ? 'hidden' : '';
    }
    menuBtn.addEventListener('click', function () { setMenu(!menuOpen); });
    overlay.addEventListener('click', function () { setMenu(false); });
    document.querySelectorAll('#mobile-drawer .m-link').forEach(function (a) {
        a.addEventListener('click', function () { setMenu(false); });
    });

    // ── Scroll reveal ──
    var reveals = document.querySelectorAll('[data-reveal]');
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) {
                e.target.classList.remove('opacity-0', 'translate-y-8');
                e.target.classList.add('opacity-100', 'translate-y-0');
                io.unobserve(e.target);
            }
        });
    }, { threshold: 0.15 });
    reveals.forEach(function (el) { io.observe(el); });

    // ── FAQ accordion ──
    document.querySelectorAll('.faq-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var card = btn.parentElement;
            var panel = card.querySelector('.faq-panel');
            var chev = btn.querySelector('.faq-chev');
            var open = panel.classList.contains('max-h-60');
            // tutup semua
            document.querySelectorAll('.faq-panel').forEach(function (p) { p.classList.remove('max-h-60', 'opacity-100'); p.classList.add('max-h-0', 'opacity-0'); });
            document.querySelectorAll('.faq-chev').forEach(function (c) { c.classList.remove('rotate-180'); });
            if (!open) {
                panel.classList.remove('max-h-0', 'opacity-0');
                panel.classList.add('max-h-60', 'opacity-100');
                chev.classList.add('rotate-180');
            }
        });
    });

    // ── Hero: carousel logo (kanan, 3D) + alternator headline putih(atas)/hijau(tengah) versi A⇄B ──
    (function () {
        var slides = Array.prototype.slice.call(document.querySelectorAll('.hero-logos .hero-lslide'));
        var hlA = Array.prototype.slice.call(document.querySelectorAll('.hl-a'));
        var hlB = Array.prototype.slice.call(document.querySelectorAll('.hl-b'));
        var started = false, li = 0, logoTimer = null, hlTimer = null, showB = false;

        function stepLogo() {
            if (slides.length < 2) return;
            slides[li].classList.remove('is-on');
            li = (li + 1) % slides.length;
            slides[li].classList.add('is-on');
        }
        function stepHeadline() {
            if (!hlA.length || !hlB.length) return;
            showB = !showB;
            hlA.forEach(function (el) { el.classList.toggle('is-on', !showB); });
            hlB.forEach(function (el) { el.classList.toggle('is-on', showB); });
        }
        function start() {
            if (started) return; started = true;
            if (slides.length >= 2) logoTimer = setInterval(stepLogo, 3000);     // logo berganti 3 dtk
            if (hlA.length && hlB.length) hlTimer = setInterval(stepHeadline, 4500); // teks putih+hijau berganti 4.5 dtk
        }
        // Mulai HANYA setelah loader & intro selesai (Bupati/Wakil tetap tampil statis sejak awal).
        var introEl = document.getElementById('intro');
        var introWillShow = introEl && introEl.dataset.auto === '1' && !introEl.classList.contains('is-hidden');
        window.addEventListener('apkasi:entered', start, { once: true });
        if (!introWillShow) setTimeout(start, 12000); // fallback bila tak ada intro auto
    })();

    // refresh ikon utk konten yg baru
    if (window.lucide) lucide.createIcons();
</script>
@endpush

@push('scripts')
<script>
    // ═══════════ INTRO HERO — scroll-jack 3 tahap + re-trigger ═══════════
    (function () {
        var overlay = document.getElementById('intro');
        if (!overlay) return;

        // Jika menuju section tertentu (mis. /#poi), lewati intro (samakan dgn splash)
        if (window.location.hash && window.location.hash.length > 1) {
            if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
            return;
        }

        var scenes  = overlay.querySelectorAll('.intro-scene');
        var caps    = overlay.querySelectorAll('.intro-cap');
        var dots    = overlay.querySelectorAll('.intro-dot');
        var hintTxt = document.getElementById('intro-hint-text');
        var LAST    = scenes.length - 1;
        var stage = 0, open = true, busy = false, upAccum = 0;

        function lock()   { document.documentElement.style.overflow = 'hidden'; document.body.style.overflow = 'hidden'; }
        function unlock() { document.documentElement.style.overflow = '';        document.body.style.overflow = ''; }

        function render() {
            for (var i = 0; i < scenes.length; i++) {
                scenes[i].classList.toggle('is-active', i === stage);
                caps[i].classList.toggle('is-active', i === stage);
                dots[i].classList.toggle('is-active', i === stage);
            }
            if (hintTxt) hintTxt.textContent = (stage === LAST) ? 'Gulir lagi untuk masuk' : 'Gulir untuk lanjut';
        }
        function cooldown(ms) { busy = true; setTimeout(function () { busy = false; }, ms || 950); }

        function step(dir) {
            if (busy) return;
            if (dir > 0) {
                if (stage < LAST) { stage++; render(); cooldown(); }
                else { closeIntro(); }
            } else if (dir < 0) {
                if (stage > 0) { stage--; render(); cooldown(); }
            }
        }

        var _entered = false;
        function enterLanding() {            // sinyal: user mulai melihat landing (hero) → carousel hero boleh jalan
            if (_entered) return; _entered = true;
            try { window.dispatchEvent(new Event('apkasi:entered')); } catch (e) {}
        }

        function closeIntro() {
            if (!open) return;
            open = false; busy = true; upAccum = 0;
            overlay.classList.add('is-hidden');
            enterLanding();
            setTimeout(function () {
                unlock();
                window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
                busy = false;
            }, 1000);
        }
        function reopenIntro() {
            if (open) return;
            open = true; upAccum = 0;
            window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
            lock();
            stage = LAST;            // datang dari atas landing → mulai dari tahap terakhir
            render();
            overlay.classList.remove('is-hidden');
            cooldown(1000);
        }

        // ── Wheel ──
        window.addEventListener('wheel', function (e) {
            if (open) {
                e.preventDefault();
                if (Math.abs(e.deltaY) < 8) return;
                step(e.deltaY > 0 ? 1 : -1);
            } else if (window.scrollY <= 0 && e.deltaY < 0) {
                upAccum += -e.deltaY;
                if (upAccum > 240) reopenIntro();
            } else {
                upAccum = 0;
            }
        }, { passive: false });

        // ── Touch ──
        var ty = 0;
        window.addEventListener('touchstart', function (e) { ty = e.touches[0].clientY; }, { passive: true });
        window.addEventListener('touchmove', function (e) {
            var dy = ty - e.touches[0].clientY;     // dy>0 = geser ke atas (≈ scroll down)
            if (open) {
                e.preventDefault();
                if (Math.abs(dy) > 45) { step(dy > 0 ? 1 : -1); ty = e.touches[0].clientY; }
            } else if (window.scrollY <= 0 && dy < -70) {
                reopenIntro();
            }
        }, { passive: false });

        // ── Keyboard ──
        window.addEventListener('keydown', function (e) {
            if (!open) return;
            if (e.key === 'ArrowDown' || e.key === 'PageDown' || e.key === ' ') { e.preventDefault(); step(1); }
            else if (e.key === 'ArrowUp' || e.key === 'PageUp') { e.preventDefault(); step(-1); }
            else if (e.key === 'Escape') { closeIntro(); }
        });

        // ── Tombol ──
        var skip = document.getElementById('intro-skip');
        if (skip) skip.addEventListener('click', closeIntro);
        var enter = document.getElementById('intro-enter');
        if (enter) enter.addEventListener('click', closeIntro);

        // ── Aktifkan setelah splash hilang (atau langsung bila tak ada splash) ──
        function activate() { lock(); stage = 0; render(); }
        function waitSplash() {
            if (!document.getElementById('splash')) { activate(); return; }
            requestAnimationFrame(waitSplash);
        }

        if (overlay.dataset.auto === '0') {
            // Intro di-suppress (sudah tampil < 2 jam): JANGAN auto-muncul & jangan kunci scroll.
            // Tetap bisa dibuka manual dengan scroll mentok ke paling atas (reopenIntro).
            open = false;
            overlay.classList.add('is-hidden');
            render();
            // Hero tampil di balik splash → kirim sinyal "masuk landing" begitu splash hilang.
            (function waitSplashThenEnter() {
                if (!document.getElementById('splash')) { enterLanding(); return; }
                requestAnimationFrame(waitSplashThenEnter);
            })();
        } else {
            lock();        // kunci dari awal (di belakang splash)
            waitSplash();
        }
    })();
</script>
@endpush
