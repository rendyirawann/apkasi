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
        <div class="absolute inset-0 w-full h-full">
            <img src="{{ asset('logos/hero-bg.png') }}" alt="" class="absolute inset-0 w-full h-full object-cover" style="animation: kenburns 25s ease-in-out infinite alternate" />
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
        </style>

        <div class="absolute inset-0 bg-gradient-to-b from-apkasi-dark/40 via-apkasi-dark/15 to-apkasi-dark/70 pointer-events-none"></div>

        <div class="relative z-10 flex-1 flex flex-col items-center justify-center text-center px-5 sm:px-8 pt-28 sm:pt-32 pb-32 sm:pb-36">
            @if ($heroLogos->count())
            <div class="flex items-center justify-center gap-6 sm:gap-10 md:gap-14 mb-8 sm:mb-10">
                @foreach ($heroLogos as $logo)
                    <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" class="h-28 sm:h-32 md:h-40 lg:h-48 w-auto object-contain drop-shadow-xl" />
                @endforeach
            </div>
            @endif

            <div class="max-w-5xl">
                <h1 class="font-display font-bold text-white text-[1.65rem] sm:text-3xl md:text-[2.75rem] lg:text-[3.5rem] xl:text-[4rem] tracking-tight">
                    {{ $g('lp_hero_headline', 'Bersinergi Membangun Daerah') }}
                </h1>
                <p class="font-display font-bold text-apkasi-accent text-[1.65rem] sm:text-3xl md:text-[2.75rem] lg:text-[3.5rem] xl:text-[4rem] tracking-tight mt-2 sm:mt-4 md:mt-5">
                    {{ $g('lp_hero_accent', 'Memperkuat Otonomi Indonesia') }}
                </p>
            </div>

            <p class="mt-4 sm:mt-6 text-white/75 text-sm sm:text-base md:text-lg leading-relaxed max-w-lg font-normal">
                {{ $g('lp_hero_subtitle', 'HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang') }}
                <br class="hidden sm:block" />
                {{ $eventRangeText }}
            </p>

            <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mt-5 sm:mt-6">
                <i data-lucide="map-pin" class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-apkasi-goldlt"></i>
                <span class="text-white/90 text-[11px] sm:text-xs font-medium tracking-wide">{{ $g('lp_hero_location', 'Kabupaten Deli Serdang, Sumatera Utara') }}</span>
            </div>

            {{-- Countdown --}}
            <div class="mt-7 sm:mt-9 flex items-center gap-2 sm:gap-3">
                @foreach (['days' => 'Hari', 'hours' => 'Jam', 'mins' => 'Menit', 'secs' => 'Detik'] as $key => $label)
                    @if (!$loop->first)
                        <span class="text-white/30 text-xl sm:text-2xl font-light">:</span>
                    @endif
                    <div class="flex flex-col items-center bg-white/10 backdrop-blur-sm border border-white/15 rounded-xl sm:rounded-2xl px-3 sm:px-5 py-2.5 sm:py-3.5 min-w-[60px] sm:min-w-[76px]">
                        <span id="cd-{{ $key }}" class="text-2xl sm:text-3xl md:text-4xl font-bold leading-none tabular-nums text-apkasi-gold" style="letter-spacing:-0.03em">00</span>
                        <span class="mt-1 text-[9px] sm:text-[10px] font-semibold uppercase tracking-[0.12em] text-white/55">{{ $label }}</span>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 sm:mt-10 flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
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
                        <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" class="h-12 sm:h-14 w-auto object-contain" />
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
                            <img src="{{ asset($g('lp_bupati_foto', 'assets/apkasi/z_04_LOGO-LOGO APKASI/BUPATI.png')) }}" alt="{{ $g('lp_bupati_nama', 'Bupati') }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" />
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
                            <img src="{{ asset($g('lp_wabup_foto', 'assets/apkasi/z_04_LOGO-LOGO APKASI/WABUPATI.png')) }}" alt="{{ $g('lp_wabup_nama', 'Wakil Bupati') }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" />
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
                        <img src="{{ $img('lp_about_image', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=900&q=80') }}" alt="{{ $g('lp_about_heading', 'Tentang Event') }}" class="w-full h-full object-cover" />
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
                                <img src="{{ asset($dayImages[$loop->index % count($dayImages)]) }}" alt="" class="w-full h-full object-contain" />
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
                        <img src="{{ $img('lp_poi_image', 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=700&q=80') }}" alt="{{ $g('lp_poi_heading2', 'Putri Otonomi Indonesia') }}" class="w-full h-full object-cover" />
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
                            <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" class="h-10 brightness-0 invert" />
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
                    <h4 class="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">Sekretariat</h4>
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
                        <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" class="h-7 opacity-50" />
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

    // refresh ikon utk konten yg baru
    if (window.lucide) lucide.createIcons();
</script>
@endpush
