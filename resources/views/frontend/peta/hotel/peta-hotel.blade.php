<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Peta Lokasi & Hotel — HUT Ke-26 APKASI & HUT Ke-80 Deli Serdang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Peta lokasi venue acara dan rekomendasi hotel untuk delegasi HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang." />
    <link rel="shortcut icon" href="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/03_APKASI_Official Logo_Transparent.png') }}" />

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" />

    <!-- Metronic -->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <!-- MapBox GL JS v3 -->
    <link href="https://cdn.jsdelivr.net/npm/mapbox-gl@3.24.0/dist/mapbox-gl.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/mapbox-gl@3.24.0/dist/mapbox-gl.js"></script>

    <style>
        :root {
            --apkasi-green: #2B543A;
            --apkasi-green-light: #447B5A;
            --apkasi-accent: #D4AF37;
            --apkasi-dark: #173423;
            --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: #fbfdfb;
            color: #2F3E35;
            overflow-x: hidden;
        }
        h1,h2,h3,h4,h5,h6,.display-font { font-family: 'Outfit', sans-serif !important; font-weight: 700; }

        /* ===== Header (identik dgn landing) ===== */
        .glass-header {
            position: fixed; top: 20px; left: 50%; transform: translateX(-50%);
            width: 90%; max-width: 1200px;
            background: rgba(255,255,255,.78); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.4); border-radius: 50px; z-index: 1000;
            padding: 10px 24px; transition: all .3s ease; box-shadow: 0 10px 30px rgba(0,0,0,.05);
        }
        .glass-header.scrolled {
            top: 0; width: 100%; max-width: 100%; border-radius: 0;
            border-left: 0; border-right: 0; border-top: 0;
            background: rgba(255,255,255,.92); box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }
        .nav-link-custom {
            font-size: .95rem; font-weight: 600; color: #3e5045 !important;
            padding: 8px 16px; border-radius: 20px; transition: all .2s ease; text-decoration: none;
        }
        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--apkasi-green) !important; background: rgba(43,84,58,.06);
        }

        /* ===== Page header (layout beda: ringkas, rata kiri) ===== */
        .page-header {
            position: relative; padding: 150px 0 64px;
            background: linear-gradient(135deg, #173423 0%, #2b543a 60%, #34684a 100%);
            color: #fff; overflow: hidden;
        }
        .page-header::after {
            content: ''; position: absolute; right: -80px; bottom: -120px;
            width: 380px; height: 380px; border-radius: 50%;
            background: radial-gradient(circle, rgba(212,175,55,.22), transparent 70%);
        }
        .page-header::before {
            content: ''; position: absolute; left: -120px; top: -120px;
            width: 320px; height: 320px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.06), transparent 70%);
        }
        .ph-inner { position: relative; z-index: 2; }
        .crumb { font-size: .8rem; color: rgba(255,255,255,.6); margin-bottom: 14px; }
        .crumb a { color: rgba(255,255,255,.6); text-decoration: none; }
        .crumb a:hover { color: var(--apkasi-accent); }
        .ph-badge {
            display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;
            background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18);
            color: #fff; font-size: .78rem; font-weight: 600; padding: 7px 16px; border-radius: 30px;
            backdrop-filter: blur(6px);
        }
        .ph-badge i { color: var(--apkasi-accent); }
        .page-title { font-size: 2.8rem; font-weight: 800; letter-spacing: -.02em; margin-bottom: 12px; line-height: 1.1; }
        .page-title span { background: linear-gradient(45deg,#FFE07D,#D4AF37); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; }
        .page-desc { color: rgba(255,255,255,.82); max-width: 640px; line-height: 1.6; margin-bottom: 26px; }

        /* stat pills */
        .stat-row { display: flex; flex-wrap: wrap; gap: 12px; }
        .stat-pill {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.14);
            border-radius: 16px; padding: 12px 18px; backdrop-filter: blur(6px); min-width: 0;
        }
        .stat-ico {
            width: 40px; height: 40px; min-width: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            background: rgba(212,175,55,.16); color: var(--apkasi-accent); font-size: 1.05rem;
        }
        .stat-num { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.15rem; line-height: 1; color: #fff; }
        .stat-lbl { font-size: .74rem; color: rgba(255,255,255,.7); margin-top: 3px; }
        @media (max-width: 991.98px){ .page-title{ font-size: 2.1rem; } .page-header{ padding: 130px 0 48px; } }

        /* ===== Toolbar (search + filter) ===== */
        .toolbar { margin-top: -36px; position: relative; z-index: 5; }
        .toolbar-card {
            background: #fff; border: 1px solid #eaf2eb; border-radius: 20px;
            box-shadow: 0 18px 40px rgba(43,84,58,.08); padding: 16px 18px;
            display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
        }
        .search-box { position: relative; flex: 1; min-width: 220px; }
        .search-box i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #9bb3a2; }
        .search-box input {
            width: 100%; border: 1px solid #e3ece5; background: #f8fbf9; border-radius: 30px;
            padding: 11px 16px 11px 42px; font-size: .9rem; color: #2F3E35; transition: all .2s ease;
        }
        .search-box input:focus { outline: none; border-color: var(--apkasi-green-light); background: #fff; box-shadow: 0 0 0 4px rgba(43,84,58,.07); }
        .filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; }
        .filter-tab {
            border: 1px solid #e3ece5; background: #fff; color: #3e5045;
            font-weight: 600; font-size: .85rem; padding: 9px 18px; border-radius: 30px;
            cursor: pointer; transition: all .2s ease;
        }
        .filter-tab:hover { border-color: rgba(43,84,58,.3); }
        .filter-tab.active { background: var(--apkasi-green); border-color: var(--apkasi-green); color: #fff; }
        .filter-tab .cnt { opacity: .6; font-weight: 500; margin-left: 4px; }
        .filter-tab.active .cnt { opacity: .85; }

        /* ===== List ===== */
        .list-head { display: flex; align-items: baseline; justify-content: space-between; gap: 10px; margin-bottom: 14px; }
        .list-head h2 { font-size: 1.25rem; font-weight: 800; color: #1f2e25; margin: 0; }
        .list-count { font-size: .82rem; color: #6b7c70; font-weight: 600; }
        .place-list { display: flex; flex-direction: column; gap: 14px; max-height: 74vh; overflow-y: auto; padding-right: 6px; }
        .place-list::-webkit-scrollbar { width: 8px; }
        .place-list::-webkit-scrollbar-thumb { background: #c9d8cd; border-radius: 99px; }

        .place-card {
            display: flex; gap: 14px; padding: 14px; border-radius: 18px;
            border: 1px solid #eaf2eb; background: #fff; cursor: pointer;
            transition: all .25s cubic-bezier(.165,.84,.44,1); position: relative; overflow: hidden;
            animation: cardIn .45s ease both;
        }
        @keyframes cardIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .place-card::before {
            content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px;
            background: var(--apkasi-green); opacity: 0; transition: opacity .25s ease;
        }
        .place-card:hover, .place-card.is-active {
            transform: translateY(-3px); box-shadow: 0 16px 32px rgba(43,84,58,.10); border-color: rgba(43,84,58,.18);
        }
        .place-card:hover::before, .place-card.is-active::before { opacity: 1; }
        .place-card.cat-hotel::before { background: var(--apkasi-accent); }

        .place-thumb {
            width: 104px; min-width: 104px; height: 104px; border-radius: 14px; object-fit: cover;
            background: #f0f5f1; display: flex; align-items: center; justify-content: center; color: #9bb3a2;
        }
        .place-body { flex: 1; min-width: 0; }
        .place-chip {
            display: inline-block; font-size: .65rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .06em; padding: 3px 10px; border-radius: 20px; margin-bottom: 6px;
            background: rgba(43,84,58,.08); color: var(--apkasi-green);
        }
        .place-chip.hotel { background: rgba(212,175,55,.16); color: #9a7d16; }
        .place-name { font-size: 1.02rem; font-weight: 700; color: #1f2e25; margin-bottom: 4px; line-height: 1.25; }
        .place-addr { font-size: .82rem; color: #6b7c70; line-height: 1.4; display: flex; gap: 6px; align-items: flex-start; }
        .place-addr i { color: #9bb3a2; margin-top: 2px; }
        .place-desc { font-size: .8rem; color: #56685c; line-height: 1.45; margin-top: 6px; }
        .place-meta { display: flex; align-items: center; gap: 14px; margin-top: 10px; font-size: .78rem; color: #5d6f63; flex-wrap: wrap; }
        .place-rating { color: #b9931f; font-weight: 700; }
        .place-price { display: inline-flex; align-items: center; gap: 5px; font-weight: 600; }
        .place-actions { margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap; }
        .mini-btn {
            font-size: .76rem; font-weight: 600; padding: 7px 13px; border-radius: 20px;
            border: 1px solid #e3ece5; background: #fff; color: var(--apkasi-green); text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px; transition: all .2s ease; cursor: pointer;
        }
        .mini-btn:hover { background: var(--apkasi-green); color: #fff; border-color: var(--apkasi-green); }
        .mini-btn.gmaps:hover { background: var(--apkasi-accent); border-color: var(--apkasi-accent); color: #173423; }
        .mini-btn.tel:hover { background: var(--apkasi-green-light); border-color: var(--apkasi-green-light); color: #fff; }

        /* ===== Map ===== */
        .map-wrap { position: sticky; top: 90px; border-radius: 22px; overflow: hidden; border: 1px solid #e6efe8; box-shadow: 0 20px 50px rgba(43,84,58,.10); }
        #map { width: 100%; height: 78vh; min-height: 480px; background: #eef3ef; }
        @media (max-width: 991.98px){ #map{ height: 56vh; min-height: 360px; } .map-wrap{ position: static; } .place-list{ max-height: none; } }

        .map-legend {
            position: absolute; bottom: 14px; left: 14px; z-index: 5;
            background: rgba(255,255,255,.92); backdrop-filter: blur(8px);
            border-radius: 12px; padding: 10px 14px; font-size: .78rem; box-shadow: 0 6px 18px rgba(0,0,0,.12);
        }
        .map-legend div { display: flex; align-items: center; gap: 8px; margin: 3px 0; font-weight: 600; color: #3e5045; }
        .dot { width: 12px; height: 12px; border-radius: 50%; }
        .dot.venue { background: var(--apkasi-green); }
        .dot.hotel { background: var(--apkasi-accent); }

        .map-fit {
            position: absolute; top: 14px; left: 14px; z-index: 5; border: 0;
            background: rgba(255,255,255,.92); backdrop-filter: blur(8px); color: var(--apkasi-green);
            font-size: .78rem; font-weight: 700; padding: 9px 14px; border-radius: 30px; cursor: pointer;
            box-shadow: 0 6px 18px rgba(0,0,0,.12); display: inline-flex; align-items: center; gap: 6px; transition: all .2s ease;
        }
        .map-fit:hover { background: var(--apkasi-green); color: #fff; }

        .map-disabled {
            display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;
            height: 100%; padding: 40px; color: #6b7c70; gap: 10px;
        }
        .map-disabled i { font-size: 2.6rem; color: #c2d3c7; }

        /* marker */
        .pin {
            width: 30px; height: 30px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg);
            border: 2px solid #fff; box-shadow: 0 3px 8px rgba(0,0,0,.3); cursor: pointer;
            display: flex; align-items: center; justify-content: center; transition: transform .2s ease;
        }
        .pin.venue { background: var(--apkasi-green); }
        .pin.hotel { background: var(--apkasi-accent); }
        .pin i { transform: rotate(45deg); color: #fff; font-size: 13px; }
        .pin.hotel i { color: #173423; }
        .pin.bounce { transform: rotate(-45deg) scale(1.28); z-index: 3; }

        .mapboxgl-popup-content { border-radius: 14px; padding: 14px 16px; font-family: 'Plus Jakarta Sans', sans-serif; box-shadow: 0 10px 30px rgba(0,0,0,.18); }
        .pop-name { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .98rem; color: #1f2e25; margin-bottom: 4px; }
        .pop-addr { font-size: .8rem; color: #6b7c70; margin-bottom: 8px; }
        .pop-link { font-size: .8rem; font-weight: 700; color: var(--apkasi-green); text-decoration: none; }

        .empty-note { text-align: center; padding: 40px 10px; color: #8a9b8f; font-size: .9rem; }

        /* ===== CTA bawah ===== */
        .cta-card {
            background: linear-gradient(135deg, #173423, #2b543a); color: #fff; border-radius: 24px;
            padding: 38px 40px; position: relative; overflow: hidden;
        }
        .cta-card::after {
            content: ''; position: absolute; right: -60px; top: -60px; width: 220px; height: 220px; border-radius: 50%;
            background: radial-gradient(circle, rgba(212,175,55,.22), transparent 70%);
        }
        .cta-card .btn-gold {
            background: linear-gradient(45deg,#FFE07D,#D4AF37); color: #173423; border: 0;
            font-weight: 700; padding: 11px 26px; border-radius: 30px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
        }
    </style>
</head>

<body>

    <!-- Header (identik dgn landing, link diarahkan balik ke section home) -->
    <header class="glass-header d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="d-flex align-items-center gap-3 text-decoration-none">
            <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/03_APKASI_Official Logo_Transparent.png') }}" alt="Logo APKASI" style="height: 38px;" />
            <div class="vr h-25px d-none d-sm-block"></div>
            <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/05_HUT  Apkasi Tahun 2026.png') }}" alt="Logo HUT APKASI 2026" style="height: 38px;" class="d-none d-sm-block" />
        </a>

        <nav class="d-none d-md-flex align-items-center gap-2">
            <a href="{{ route('home') }}#home" class="nav-link-custom">Beranda</a>
            <a href="{{ route('home') }}#about" class="nav-link-custom">Tentang</a>
            <a href="{{ route('home') }}#agenda" class="nav-link-custom">Agenda</a>
            <a href="{{ route('peta-hotel') }}" class="nav-link-custom active">Peta & Hotel</a>
            <a href="{{ route('guide') }}" class="nav-link-custom">Panduan</a>
        </nav>

        <div>
            <a href="{{ url('/admin/login') }}" class="btn btn-sm btn-primary px-5 py-2 rounded-pill" style="background-color: var(--apkasi-green); border-color: var(--apkasi-green);">
                Area Admin
            </a>
        </div>
    </header>

    @php
        $places   = collect($places);
        $venueCnt = $places->where('category', 'venue')->count();
        $hotelCnt = $places->where('category', 'hotel')->count();
    @endphp

    <!-- Page Header -->
    <section class="page-header">
        <div class="container ph-inner">
            <div class="crumb">
                <a href="{{ route('home') }}">Beranda</a> &nbsp;/&nbsp; Peta Lokasi & Hotel
            </div>
            <div class="ph-badge">
                <i class="ki-duotone ki-geolocation fs-6"><span class="path1"></span><span class="path2"></span></i>
                Kabupaten Deli Serdang, Sumatera Utara
            </div>
            <h1 class="page-title">Peta Lokasi <span>& Hotel</span></h1>
            <p class="page-desc">
                Temukan lokasi venue rangkaian acara dan rekomendasi penginapan terdekat untuk delegasi.
                Klik kartu untuk menyorot titik pada peta, atau buka langsung ke Google Maps untuk navigasi.
            </p>

            <div class="stat-row">
                <div class="stat-pill">
                    <div class="stat-ico"><i class="ki-duotone ki-geolocation"><span class="path1"></span><span class="path2"></span></i></div>
                    <div><div class="stat-num">{{ $venueCnt }}</div><div class="stat-lbl">Lokasi Acara</div></div>
                </div>
                <div class="stat-pill">
                    <div class="stat-ico"><i class="ki-duotone ki-home-2"><span class="path1"></span><span class="path2"></span></i></div>
                    <div><div class="stat-num">{{ $hotelCnt }}</div><div class="stat-lbl">Hotel Rekomendasi</div></div>
                </div>
                <div class="stat-pill">
                    <div class="stat-ico"><i class="ki-duotone ki-calendar"><span class="path1"></span><span class="path2"></span></i></div>
                    <div><div class="stat-num">1&ndash;3 Juli</div><div class="stat-lbl">Rangkaian Acara 2026</div></div>
                </div>
                <div class="stat-pill">
                    <div class="stat-ico"><i class="ki-duotone ki-airplane"><span class="path1"></span><span class="path2"></span></i></div>
                    <div><div class="stat-num">± 20&ndash;30'</div><div class="stat-lbl">Dari Bandara Kualanamu</div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Toolbar + Map + List -->
    <section class="pb-10">
        <div class="container">

            <!-- Toolbar -->
            <div class="toolbar">
                <div class="toolbar-card">
                    <div class="search-box">
                        <i class="ki-duotone ki-magnifier fs-4"><span class="path1"></span><span class="path2"></span></i>
                        <input type="text" id="searchInput" placeholder="Cari nama lokasi atau hotel..." autocomplete="off" />
                    </div>
                    <div class="filter-tabs" id="filterTabs">
                        <button class="filter-tab active" data-cat="all">Semua</button>
                        <button class="filter-tab" data-cat="venue">Lokasi Acara</button>
                        <button class="filter-tab" data-cat="hotel">Hotel</button>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <!-- List -->
                <div class="col-lg-5 order-2 order-lg-1">
                    <div class="list-head">
                        <h2>Daftar Tempat</h2>
                        <span class="list-count" id="listCount"></span>
                    </div>
                    <div class="place-list" id="placeList"></div>
                </div>

                <!-- Map -->
                <div class="col-lg-7 order-1 order-lg-2">
                    <div class="map-wrap">
                        <div id="map"></div>
                        <button class="map-fit" id="mapFit" type="button">
                            <i class="ki-duotone ki-maximize fs-6"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            Tampilkan semua
                        </button>
                        <div class="map-legend">
                            <div><span class="dot venue"></span> Lokasi Acara / Venue</div>
                            <div><span class="dot hotel"></span> Hotel & Penginapan</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA: hubungkan dgn halaman Panduan -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="cta-card d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                        <div style="position: relative; z-index: 2;">
                            <h3 class="text-white fw-bold mb-2">Butuh info transportasi & destinasi wisata?</h3>
                            <p class="mb-0" style="color: rgba(255,255,255,.78); max-width: 560px;">
                                Lihat panduan lengkap delegasi: rekomendasi rental kendaraan, destinasi wisata Deli Serdang,
                                serta detail tiap venue acara.
                            </p>
                        </div>
                        <a href="{{ route('guide') }}" class="btn-gold flex-shrink-0">
                            Buka Panduan Lengkap
                            <i class="ki-duotone ki-arrow-right fs-5"><span class="path1"></span><span class="path2"></span></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Footer (sama dgn landing) -->
    <footer class="py-12 text-center" style="background-color: #173423; color: rgba(255,255,255,.7); border-top: 5px solid var(--apkasi-accent);">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center gap-4 mb-6">
                <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/03_APKASI_Official Logo_Transparent.png') }}" alt="Logo APKASI" style="height: 45px;" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Logo_Deli_Serdang.png" alt="Logo Deli Serdang" style="height: 45px;" />
            </div>
            <h5 class="text-white fw-bold mb-2">HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang</h5>
            <p class="fs-7 text-white text-opacity-50 mb-6">Sekretariat APKASI & Dinas Kominfo Kabupaten Deli Serdang, Sumatera Utara.</p>
            <div class="separator separator-dashed border-white border-opacity-10 mb-6"></div>
            <p class="fs-8 text-white text-opacity-40 mb-0">&copy; 2026 Pemerintah Kabupaten Deli Serdang & APKASI. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- JS -->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    <script>
        // header shrink
        window.addEventListener('scroll', function () {
            const h = document.querySelector('.glass-header');
            if (window.scrollY > 50) h.classList.add('scrolled'); else h.classList.remove('scrolled');
        });

        // ====== DATA dari controller ======
        const PLACES = @json($places->values());
        const MAP_CENTER = @json($center);   // [lng, lat]
        const MAPBOX_TOKEN = @json($mapboxToken);

        let activeCat = 'all';
        let searchTerm = '';
        const markers = {};   // id -> {marker, el}
        const cards = {};      // id -> card element (yang sedang tampil)
        let map = null;

        function norm(s) { return (s || '').toString().toLowerCase(); }
        function digits(s) { return (s || '').toString().replace(/[^0-9+]/g, ''); }
        function gmapsUrl(p) { return p.maps_url || ('https://www.google.com/maps/search/?api=1&query=' + p.lat + ',' + p.lng); }

        function visiblePlaces() {
            return PLACES.filter(function (p) {
                const catOk = activeCat === 'all' || p.category === activeCat;
                const q = searchTerm.trim();
                const qOk = !q || norm(p.name).includes(q) || norm(p.address).includes(q);
                return catOk && qOk;
            });
        }

        function popupHtml(p) {
            const isHotel = p.category === 'hotel';
            return '<div class="pop-name">' + p.name + '</div>' +
                '<div class="pop-addr">' + (p.address || '') + '</div>' +
                '<a class="pop-link" href="' + gmapsUrl(p) + '" target="_blank" rel="noopener">Buka di Google Maps &rarr;</a>';
        }

        function buildMarkers() {
            PLACES.forEach(function (p) {
                const el = document.createElement('div');
                el.className = 'pin ' + (p.category === 'hotel' ? 'hotel' : 'venue');
                el.innerHTML = '<i class="ki-duotone ' + (p.category === 'hotel' ? 'ki-home-2' : 'ki-geolocation') + '"><span class="path1"></span><span class="path2"></span></i>';

                const popup = new mapboxgl.Popup({ offset: 26, closeButton: false }).setHTML(popupHtml(p));
                const marker = new mapboxgl.Marker({ element: el, anchor: 'bottom' })
                    .setLngLat([p.lng, p.lat]).setPopup(popup).addTo(map);

                el.addEventListener('click', function () { focusPlace(p.id, false); });
                markers[p.id] = { marker, el };
            });
        }

        function fitToVisible() {
            if (!map) return;
            const pts = visiblePlaces();
            if (!pts.length) return;
            if (pts.length === 1) { map.flyTo({ center: [pts[0].lng, pts[0].lat], zoom: 14, duration: 700 }); return; }
            const b = new mapboxgl.LngLatBounds();
            pts.forEach(function (p) { b.extend([p.lng, p.lat]); });
            map.fitBounds(b, { padding: 70, maxZoom: 15, duration: 700 });
        }

        function focusPlace(id, fromCard) {
            const p = PLACES.find(function (x) { return x.id === id; });
            if (!p) return;
            if (map) {
                map.flyTo({ center: [p.lng, p.lat], zoom: 15, duration: 800 });
                Object.values(markers).forEach(function (m) { m.el.classList.remove('bounce'); });
                if (markers[id]) { markers[id].el.classList.add('bounce'); markers[id].marker.togglePopup(); }
            }
            Object.values(cards).forEach(function (c) { c.classList.remove('is-active'); });
            if (cards[id]) {
                cards[id].classList.add('is-active');
                if (fromCard !== true) cards[id].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        function renderList() {
            const list = document.getElementById('placeList');
            list.innerHTML = '';
            Object.keys(cards).forEach(function (k) { delete cards[k]; });
            const items = visiblePlaces();

            document.getElementById('listCount').textContent = 'Menampilkan ' + items.length + ' tempat';

            if (!items.length) {
                list.innerHTML = '<div class="empty-note">Tidak ada lokasi yang cocok dengan pencarian/filter ini.</div>';
                return;
            }

            items.forEach(function (p, idx) {
                const isHotel = p.category === 'hotel';
                const card = document.createElement('div');
                card.className = 'place-card ' + (isHotel ? 'cat-hotel' : '');
                card.style.animationDelay = (idx * 0.04) + 's';

                const thumb = p.image
                    ? '<img class="place-thumb" src="' + p.image + '" alt="" loading="lazy">'
                    : '<div class="place-thumb"><i class="ki-duotone ' + (isHotel ? 'ki-home-2' : 'ki-geolocation') + ' fs-2x"><span class="path1"></span><span class="path2"></span></i></div>';

                const rating = p.rating ? '<span class="place-rating">&#9733; ' + p.rating + '</span>' : '';
                const price = p.price_range ? '<span class="place-price"><i class="ki-duotone ki-star fs-8"><span class="path1"></span></i>' + p.price_range + '</span>' : '';
                const desc = p.description ? '<div class="place-desc">' + p.description + '</div>' : '';
                const telBtn = (isHotel && p.phone)
                    ? '<a class="mini-btn tel" href="tel:' + digits(p.phone) + '"><i class="ki-duotone ki-phone fs-7"><span class="path1"></span><span class="path2"></span></i>Telepon</a>'
                    : '';

                card.innerHTML =
                    thumb +
                    '<div class="place-body">' +
                        '<span class="place-chip ' + (isHotel ? 'hotel' : '') + '">' + (isHotel ? 'Hotel' : 'Lokasi Acara') + '</span>' +
                        '<div class="place-name">' + p.name + '</div>' +
                        '<div class="place-addr"><i class="ki-duotone ki-geolocation fs-7"><span class="path1"></span><span class="path2"></span></i><span>' + (p.address || '') + '</span></div>' +
                        desc +
                        '<div class="place-meta">' + rating + price + '</div>' +
                        '<div class="place-actions">' +
                            '<button class="mini-btn map-btn"><i class="ki-duotone ki-map fs-7"><span class="path1"></span><span class="path2"></span></i>Lihat di Peta</button>' +
                            '<a class="mini-btn gmaps" href="' + gmapsUrl(p) + '" target="_blank" rel="noopener"><i class="ki-duotone ki-send fs-7"><span class="path1"></span><span class="path2"></span></i>Rute</a>' +
                            telBtn +
                        '</div>' +
                    '</div>';

                card.querySelector('.map-btn').addEventListener('click', function (e) { e.stopPropagation(); focusPlace(p.id, true); });
                card.addEventListener('click', function () { focusPlace(p.id, true); });
                cards[p.id] = card;
                list.appendChild(card);
            });
        }

        function applyMarkerVisibility() {
            const ids = new Set(visiblePlaces().map(function (p) { return p.id; }));
            Object.entries(markers).forEach(function (entry) {
                entry[1].el.style.display = ids.has(Number(entry[0])) || ids.has(entry[0]) ? '' : 'none';
            });
        }

        function refresh(fit) {
            applyMarkerVisibility();
            renderList();
            if (fit) fitToVisible();
        }

        // ===== Tab counts (total per kategori) =====
        (function () {
            const v = PLACES.filter(function (p) { return p.category === 'venue'; }).length;
            const h = PLACES.filter(function (p) { return p.category === 'hotel'; }).length;
            document.querySelector('[data-cat="all"]').innerHTML = 'Semua <span class="cnt">' + PLACES.length + '</span>';
            document.querySelector('[data-cat="venue"]').innerHTML = 'Lokasi Acara <span class="cnt">' + v + '</span>';
            document.querySelector('[data-cat="hotel"]').innerHTML = 'Hotel <span class="cnt">' + h + '</span>';
        })();

        // ===== Filter tabs =====
        document.querySelectorAll('.filter-tab').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.filter-tab').forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                activeCat = btn.dataset.cat;
                refresh(true);
            });
        });

        // ===== Search =====
        document.getElementById('searchInput').addEventListener('input', function (e) {
            searchTerm = norm(e.target.value);
            refresh(false);
        });

        // ===== Fit-all button =====
        document.getElementById('mapFit').addEventListener('click', function () { fitToVisible(); });

        // ===== Init =====
        if (MAPBOX_TOKEN) {
            mapboxgl.accessToken = MAPBOX_TOKEN;
            map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/light-v11',
                center: MAP_CENTER,
                zoom: 11,
                attributionControl: false,
            });
            map.addControl(new mapboxgl.NavigationControl({ showCompass: false }), 'top-right');
            map.addControl(new mapboxgl.AttributionControl({ compact: true }));
            map.on('load', function () { buildMarkers(); refresh(true); });
        } else {
            // Token Mapbox belum diisi: tampilkan pesan, daftar tetap berfungsi.
            document.getElementById('map').innerHTML =
                '<div class="map-disabled">' +
                '<i class="ki-duotone ki-map"><span class="path1"></span><span class="path2"></span></i>' +
                '<div><strong>Peta belum aktif.</strong><br>Setel <code>MAPBOX_TOKEN</code> pada file .env untuk menampilkan peta.</div>' +
                '</div>';
            document.getElementById('mapFit').style.display = 'none';
            renderList();
        }
    </script>
</body>
</html>
