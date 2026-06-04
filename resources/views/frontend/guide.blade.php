<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Panduan Delegasi: Peta & Akomodasi — APKASI Deli Serdang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/03_APKASI_Official Logo_Transparent.png') }}" />
    
    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" />
    
    <!-- Metronic Global Stylesheets -->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />

    <style>
        :root {
            --apkasi-green: #2B543A;
            --apkasi-green-light: #447B5A;
            --apkasi-accent: #D4AF37; /* Gold */
            --bs-body-font-family: 'Plus Jakarta Sans', sans-serif;
            --bs-font-sans-serif: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: #f6faf7;
            color: #2F3E35;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif !important;
            font-weight: 700;
        }

        /* Floating glassmorphic header */
        .glass-header {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 50px;
            z-index: 1000;
            padding: 10px 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .nav-link-custom {
            font-size: 0.95rem;
            font-weight: 600;
            color: #3e5045 !important;
            padding: 8px 16px;
            border-radius: 20px;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover, .nav-link-custom.active {
            color: var(--apkasi-green) !important;
            background: rgba(43, 84, 58, 0.06);
        }

        /* Main Content Padding */
        .main-content {
            padding-top: 130px;
            padding-bottom: 80px;
        }

        /* Title section */
        .page-title-section {
            background: linear-gradient(135deg, #173423 0%, #2b543a 100%);
            border-radius: 24px;
            padding: 48px;
            color: #fff;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(43, 84, 58, 0.15);
        }

        /* Custom Tabs Styling */
        .guide-tabs {
            border: none;
            background: #fff;
            padding: 8px;
            border-radius: 50px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            margin-bottom: 30px;
            display: inline-flex;
            width: 100%;
            justify-content: space-around;
        }

        @media (max-width: 767.98px) {
            .guide-tabs {
                flex-direction: column;
                border-radius: 20px;
                padding: 12px;
                gap: 6px;
            }
        }

        .guide-tabs .nav-link {
            border: none !important;
            border-radius: 40px;
            font-weight: 700;
            color: #556b5e !important;
            padding: 12px 24px;
            transition: all 0.2s ease;
            text-align: center;
        }

        .guide-tabs .nav-link.active {
            background: var(--apkasi-green) !important;
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(43, 84, 58, 0.2);
        }

        /* Venue Map Cards */
        .venue-selector-card {
            cursor: pointer;
            border: 1px solid #e2ece5;
            border-radius: 16px;
            background: #fff;
            transition: all 0.25s ease;
        }

        .venue-selector-card.active {
            border-color: var(--apkasi-green);
            background: rgba(43, 84, 58, 0.02);
            box-shadow: 0 8px 24px rgba(43, 84, 58, 0.05);
        }

        .venue-selector-card.active .venue-num {
            background: var(--apkasi-green);
            color: #fff;
        }

        .venue-num {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(43, 84, 58, 0.08);
            color: var(--apkasi-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        /* Guide cards (Wisata / Hotel) */
        .guide-card {
            border: 1px solid #e5ede7;
            border-radius: 20px;
            background: #fff;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%;
            display: flex;
            flex-column: column;
        }

        .guide-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 30px rgba(43, 84, 58, 0.06);
            border-color: rgba(43, 84, 58, 0.15);
        }

        .guide-card-img-wrapper {
            height: 200px;
            overflow: hidden;
            position: relative;
        }

        .guide-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .guide-card:hover .guide-card-img {
            transform: scale(1.08);
        }

        .guide-card-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        /* Map frame wrapper */
        .map-frame-wrapper {
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e2ece5;
            box-shadow: 0 8px 30px rgba(0,0,0,0.02);
            height: 480px;
        }

        @media (max-width: 991.98px) {
            .map-frame-wrapper {
                height: 350px;
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Header / Navigation Bar -->
    <header class="glass-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/03_APKASI_Official Logo_Transparent.png') }}" alt="Logo APKASI" style="height: 38px;" />
            </a>
            <div class="vr h-25px d-none d-sm-block"></div>
            <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/05_HUT  Apkasi Tahun 2026.png') }}" alt="Logo HUT APKASI" style="height: 38px;" class="d-none d-sm-block" />
        </div>
        
        <nav class="d-none d-md-flex align-items-center gap-2">
            <a href="{{ route('home') }}" class="nav-link-custom">Beranda</a>
            <a href="{{ route('home') }}#about" class="nav-link-custom">Tentang</a>
            <a href="{{ route('home') }}#agenda" class="nav-link-custom">Agenda</a>
            <a href="{{ route('home') }}#poi" class="nav-link-custom">Putri Otonomi</a>
            <a href="{{ route('guide') }}" class="nav-link-custom active">Panduan Peta & Hotel</a>
        </nav>

        <div>
            <a href="{{ url('/admin/login') }}" class="btn btn-sm btn-primary px-5 py-2.5 rounded-pill" style="background-color: var(--apkasi-green); border-color: var(--apkasi-green);">
                <i class="ki-duotone ki-user-square fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Area Admin
            </a>
        </div>
    </header>

    <main class="container main-content">
        <!-- Page Title Banner -->
        <div class="page-title-section text-center text-md-start">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-8">
                    <span class="badge badge-light-success text-success fw-bold px-4 py-2 rounded-pill fs-7 text-uppercase mb-3">Panduan Delegasi</span>
                    <h1 class="text-white mb-2 fs-1">Panduan Akomodasi & Peta Lokasi</h1>
                    <p class="text-white text-opacity-80 fs-6 mb-0">Temukan lokasi event resmi, destinasi pariwisata unggulan, rekomendasi hotel, dan fasilitas sewa mobil di Kabupaten Deli Serdang.</p>
                </div>
                <div class="col-md-3 text-md-end mt-6 mt-md-0">
                    <a href="{{ route('home') }}" class="btn btn-warning px-6 py-3 rounded-pill fw-bold" style="background-color: var(--apkasi-accent); border-color: var(--apkasi-accent); color: #173423;">
                        <i class="ki-duotone ki-left fs-5 me-1"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="text-center">
            <ul class="nav nav-tabs guide-tabs" id="guideTabs" role="tablist">
                <li class="nav-item flex-grow-1" role="presentation">
                    <button class="nav-link w-100 active" id="event-tab" data-bs-toggle="tab" data-bs-target="#event-pane" type="button" role="tab" aria-controls="event-pane" aria-selected="true">
                        <i class="ki-duotone ki-map fs-4 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        1. Peta Lokasi Event
                    </button>
                </li>
                <li class="nav-item flex-grow-1" role="presentation">
                    <button class="nav-link w-100" id="wisata-tab" data-bs-toggle="tab" data-bs-target="#wisata-pane" type="button" role="tab" aria-controls="wisata-pane" aria-selected="false">
                        <i class="ki-duotone ki-geolocation fs-4 me-2"><span class="path1"></span><span class="path2"></span></i>
                        2. Destinasi Wisata
                    </button>
                </li>
                <li class="nav-item flex-grow-1" role="presentation">
                    <button class="nav-link w-100" id="hotel-tab" data-bs-toggle="tab" data-bs-target="#hotel-pane" type="button" role="tab" aria-controls="hotel-pane" aria-selected="false">
                        <i class="ki-duotone ki-home fs-4 me-2"><span class="path1"></span><span class="path2"></span></i>
                        3. Hotel & Akomodasi
                    </button>
                </li>
                <li class="nav-item flex-grow-1" role="presentation">
                    <button class="nav-link w-100" id="car-tab" data-bs-toggle="tab" data-bs-target="#car-pane" type="button" role="tab" aria-controls="car-pane" aria-selected="false">
                        <i class="ki-duotone ki-delivery-3 fs-4 me-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        4. Rental Mobil
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Panes -->
        <div class="tab-content" id="guideTabsContent">
            
            <!-- TAB 1: EVENT VENUES -->
            <div class="tab-pane fade show active" id="event-pane" role="tabpanel" aria-labelledby="event-tab">
                <div class="row g-6">
                    <div class="col-lg-5">
                        <h3 class="text-gray-900 mb-4 fw-bold">Venue Rangkaian Acara</h3>
                        <p class="text-muted fs-7 mb-6">Klik pada salah satu lokasi di bawah ini untuk memperbarui peta lokasi secara dinamis dan mendapatkan arah navigasi jalan.</p>
                        
                        <div class="d-flex flex-column gap-4">
                            @foreach($venues as $idx => $venue)
                                <div class="venue-selector-card p-5 d-flex gap-4 {{ $idx === 0 ? 'active' : '' }}" 
                                     data-map-embed="{{ $venue['map_embed'] }}"
                                     data-map-link="{{ $venue['map_link'] }}"
                                     onclick="changeActiveVenue(this)">
                                    <div class="venue-num">{{ $idx + 1 }}</div>
                                    <div>
                                        <h5 class="text-gray-800 mb-1 fw-bold">{{ $venue['name'] }}</h5>
                                        <span class="badge badge-light-success text-success fs-9 fw-bold mb-2">{{ $venue['type'] }}</span>
                                        <p class="text-muted fs-8 mb-1"><i class="ki-duotone ki-pin fs-9 me-1"><span class="path1"></span><span class="path2"></span></i> {{ $venue['address'] }}</p>
                                        <p class="text-gray-600 fs-8 mb-0 mt-2">{{ $venue['details'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="map-frame-wrapper position-relative">
                            <!-- Direct Navigation Button -->
                            <a href="{{ $venues[0]['map_link'] }}" target="_blank" id="btn-direct-nav" class="btn btn-sm btn-light px-4 py-2.5 rounded-pill position-absolute top-10px right-10px shadow-sm z-index-2">
                                <i class="ki-duotone ki-compass text-primary fs-5 me-1"><span class="path1"></span><span class="path2"></span></i> Arah Google Maps
                            </a>
                            <iframe src="{{ $venues[0]['map_embed'] }}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" id="iframe-venue-map"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: TOURIST ATTRACTIONS -->
            <div class="tab-pane fade" id="wisata-pane" role="tabpanel" aria-labelledby="wisata-tab">
                <div class="row g-6">
                    @foreach($tourisms as $tour)
                        <div class="col-md-6 col-lg-3">
                            <div class="card guide-card shadow-sm">
                                <div class="guide-card-img-wrapper">
                                    <img src="{{ $tour['image'] }}" alt="{{ $tour['name'] }}" class="guide-card-img" />
                                    <span class="badge bg-dark bg-opacity-75 text-white fs-9 px-3 py-2 rounded-pill position-absolute bottom-10px left-10px">
                                        <i class="ki-duotone ki-watch text-warning fs-9 me-1"><span class="path1"></span><span class="path2"></span></i> {{ $tour['distance'] }}
                                    </span>
                                </div>
                                <div class="guide-card-body">
                                    <h4 class="text-gray-900 fw-bold mb-1 fs-5">{{ $tour['name'] }}</h4>
                                    <span class="text-primary fs-8 fw-semibold mb-3">{{ $tour['location'] }}</span>
                                    <p class="text-muted fs-7 flex-grow-1 leading-relaxed mb-6">{{ $tour['description'] }}</p>
                                    
                                    <div class="separator mb-4"></div>
                                    <a href="{{ $tour['map_link'] }}" target="_blank" class="btn btn-light-success btn-sm w-100 rounded-pill py-2.5 fw-bold">
                                        <i class="ki-duotone ki-map fs-6 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> Buka Rute Peta
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TAB 3: HOTELS -->
            <div class="tab-pane fade" id="hotel-pane" role="tabpanel" aria-labelledby="hotel-tab">
                <div class="row g-6">
                    @foreach($hotels as $hotel)
                        <div class="col-md-6 col-lg-3">
                            <div class="card guide-card shadow-sm">
                                <div class="guide-card-img-wrapper">
                                    <img src="{{ $hotel['image'] }}" alt="{{ $hotel['name'] }}" class="guide-card-img" />
                                </div>
                                <div class="guide-card-body">
                                    <span class="badge badge-light-warning text-warning fs-9 fw-bold mb-2">{{ $hotel['class'] }}</span>
                                    <h4 class="text-gray-900 fw-bold mb-2 fs-5">{{ $hotel['name'] }}</h4>
                                    <p class="text-muted fs-8 mb-3"><i class="ki-duotone ki-pin fs-9 me-1"><span class="path1"></span><span class="path2"></span></i> {{ $hotel['address'] }}</p>
                                    
                                    <div class="bg-light p-3 rounded-lg mb-6 fs-8">
                                        <div class="d-flex align-items-center gap-1.5 text-gray-700 mb-1">
                                            <i class="ki-duotone ki-delivery-3 fs-8"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            <strong>Jarak:</strong> {{ $hotel['distance'] }}
                                        </div>
                                        <div class="d-flex align-items-center gap-1.5 text-gray-700">
                                            <i class="ki-duotone ki-phone fs-8"><span class="path1"></span><span class="path2"></span></i>
                                            <strong>Telp:</strong> {{ $hotel['phone'] }}
                                        </div>
                                    </div>
                                    
                                    <div class="mt-auto d-flex gap-2">
                                        <a href="tel:{{ str_replace(' ', '', $hotel['phone']) }}" class="btn btn-light-primary btn-icon btn-sm rounded-circle w-35px h-35px flex-shrink-0">
                                            <i class="ki-duotone ki-phone fs-5"><span class="path1"></span><span class="path2"></span></i>
                                        </a>
                                        <a href="{{ $hotel['map_link'] }}" target="_blank" class="btn btn-light-success btn-sm w-100 rounded-pill py-2.5 fw-bold fs-8">
                                            <i class="ki-duotone ki-compass fs-6 me-1"><span class="path1"></span><span class="path2"></span></i> Buka Rute
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TAB 4: CAR RENTALS -->
            <div class="tab-pane fade" id="car-pane" role="tabpanel" aria-labelledby="car-tab">
                <div class="row g-6 justify-content-center">
                    @foreach($rentals as $rental)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border border-2 border-dashed border-gray-300 rounded-xl p-8 bg-white h-100 d-flex flex-column">
                                <div class="d-flex align-items-center gap-4 mb-4">
                                    <div class="symbol symbol-50px symbol-circle bg-light-success text-success d-flex align-items-center justify-content-center">
                                        <i class="ki-duotone ki-delivery-2 fs-1 text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span></i>
                                    </div>
                                    <div>
                                        <h4 class="text-gray-900 fw-bold mb-0">{{ $rental['company'] }}</h4>
                                        <span class="text-success fs-8 fw-semibold">Penyedia Terverifikasi</span>
                                    </div>
                                </div>
                                <p class="text-muted fs-7 flex-grow-1 leading-relaxed mb-6">{{ $rental['services'] }} <br/><br/><em>*{{ $rental['desc'] }}</em></p>
                                
                                <div class="separator separator-dashed mb-6"></div>
                                
                                <!-- WhatsApp prefilled message link -->
                                @php
                                    $waMessage = "Halo " . $rental['company'] . ", saya delegasi HUT APKASI 2026 Deli Serdang ingin menanyakan informasi ketersediaan armada rental mobil.";
                                    $waUrl = "https://wa.me/" . $rental['whatsapp'] . "?text=" . urlencode($waMessage);
                                @endphp
                                <a href="{{ $waUrl }}" target="_blank" class="btn btn-success w-100 rounded-pill py-3 fw-bold d-flex align-items-center justify-content-center gap-2" style="background-color: #25D366; border-color: #25D366;">
                                    <i class="ki-duotone ki-message-text fs-4 text-white"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    Hubungi Via WhatsApp ({{ $rental['phone_format'] }})
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="py-12 text-center" style="background-color: #173423; color: rgba(255, 255, 255, 0.7); border-top: 5px solid var(--apkasi-accent);">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center gap-4 mb-6">
                <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/03_APKASI_Official Logo_Transparent.png') }}" alt="Logo APKASI" style="height: 45px;" />
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Logo_Deli_Serdang.png" alt="Logo Deli Serdang" style="height: 45px;" />
            </div>
            
            <h5 class="text-white fw-bold mb-2">HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang</h5>
            <p class="fs-7 text-white text-opacity-50 mb-6">
                Sekretariat APKASI & Dinas Kominfo Kabupaten Deli Serdang, Sumatera Utara.
            </p>
            
            <div class="separator separator-dashed border-white border-opacity-10 mb-6"></div>
            
            <p class="fs-8 text-white text-opacity-40 mb-0">
                &copy; 2026 Pemerintah Kabupaten Deli Serdang & APKASI. All Rights Reserved. Designed for premium experience.
            </p>
        </div>
    </footer>

    <!-- Javascript -->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    
    <script>
        // Tab switching event listener to adjust map iframe or grid layouts if needed
        var triggerTabList = [].slice.call(document.querySelectorAll('#guideTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })

        // Event Map interactive updater
        function changeActiveVenue(element) {
            // Remove active class from all selectors
            document.querySelectorAll('.venue-selector-card').forEach(function(card) {
                card.classList.remove('active');
            });

            // Add active class to clicked card
            element.classList.add('active');

            // Get map properties
            var embedUrl = element.getAttribute('data-map-embed');
            var directUrl = element.getAttribute('data-map-link');

            // Update iframe and direct nav link
            document.getElementById('iframe-venue-map').src = embedUrl;
            document.getElementById('btn-direct-nav').href = directUrl;
        }
    </script>
</body>
</html>
