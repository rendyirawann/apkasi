<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang</title>
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
            background-color: #fbfdfb;
            color: #2F3E35;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .display-font {
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
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 50px;
            z-index: 1000;
            padding: 10px 24px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .glass-header.scrolled {
            top: 0;
            width: 100%;
            max-width: 100%;
            border-radius: 0;
            border-left: 0;
            border-right: 0;
            border-top: 0;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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

        /* Hero Section */
        .hero-section {
            position: relative;
            min-height: 95vh;
            background: linear-gradient(rgba(21, 45, 30, 0.72), rgba(15, 30, 20, 0.88)), 
                        url("{{ asset('assets/media/apkasi_hero_bg.png') }}") no-repeat center center;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 140px;
            padding-bottom: 80px;
            color: #fff;
        }

        /* Custom typography and decoration mimicking Gambar 1 */
        .hero-badge {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .hero-title {
            font-size: 3.5rem;
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 20px;
        }

        .hero-title span {
            color: var(--apkasi-accent);
            background: linear-gradient(45deg, #FFE07D, #D4AF37);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @media (max-width: 991.98px) {
            .hero-title {
                font-size: 2.5rem;
            }
        }

        .hero-desc {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.85);
            max-width: 700px;
            margin: 0 auto 36px auto;
            line-height: 1.6;
        }

        /* Countdown styling */
        .countdown-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            min-width: 90px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .countdown-num {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--apkasi-accent);
            line-height: 1;
            margin-bottom: 4px;
        }

        .countdown-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
        }

        /* Section header badge */
        .section-badge {
            background: rgba(43, 84, 58, 0.08);
            color: var(--apkasi-green);
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            display: inline-block;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        /* Event Timeline Card Hover Effects */
        .event-card {
            border-radius: 20px;
            border: 1px solid #eaf2eb;
            background: #fff;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
        }

        .event-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: var(--apkasi-green);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(43, 84, 58, 0.08);
            border-color: rgba(43, 84, 58, 0.15);
        }

        .event-card:hover::before {
            opacity: 1;
        }

        .event-icon-box {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(43, 84, 58, 0.08);
            color: var(--apkasi-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }

        .event-card:hover .event-icon-box {
            background: var(--apkasi-green);
            color: #fff;
        }

        /* POI Feature Card */
        .poi-card {
            background: linear-gradient(135deg, #173423 0%, #2b543a 100%);
            border-radius: 24px;
            color: #fff;
            overflow: hidden;
            border: none;
            position: relative;
        }

        .poi-card .gold-accent {
            color: var(--apkasi-accent);
        }

        /* FAQ Accordion Styling */
        .faq-accordion .accordion-item {
            border: 1px solid #eaf2eb;
            border-radius: 12px;
            margin-bottom: 12px;
            overflow: hidden;
            background: #fff;
            transition: all 0.2s ease;
        }

        .faq-accordion .accordion-item:hover {
            border-color: rgba(43, 84, 58, 0.2);
            box-shadow: 0 4px 12px rgba(43, 84, 58, 0.02);
        }

        .faq-accordion .accordion-button {
            font-weight: 600;
            color: #2F3E35;
            background: #fff;
            box-shadow: none;
            padding: 20px 24px;
        }

        .faq-accordion .accordion-button:not(.collapsed) {
            color: var(--apkasi-green);
            background: rgba(43, 84, 58, 0.02);
        }

        .faq-accordion .accordion-button::after {
            background-size: 1rem;
        }

        /* Logo gallery */
        .logo-box {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            background: #fff;
            border: 1px solid #edf3ef;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.01);
            transition: all 0.3s ease;
        }

        .logo-box:hover {
            transform: scale(1.05);
            border-color: rgba(43, 84, 58, 0.15);
        }

        .logo-box img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
    </style>
</head>

<body>

    <!-- Header / Navigation Bar -->
    <header class="glass-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/03_APKASI_Official Logo_Transparent.png') }}" alt="Logo APKASI" style="height: 38px;" />
            <div class="vr h-25px d-none d-sm-block"></div>
            <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/05_HUT  Apkasi Tahun 2026.png') }}" alt="Logo HUT APKASI 2026" style="height: 38px;" class="d-none d-sm-block" />
        </div>
        
        <nav class="d-none d-md-flex align-items-center gap-2">
            <a href="#home" class="nav-link-custom active">Beranda</a>
            <a href="#about" class="nav-link-custom">Tentang</a>
            <a href="#agenda" class="nav-link-custom">Agenda</a>
            <a href="#poi" class="nav-link-custom">Putri Otonomi</a>
            <a href="{{ route('guide') }}" class="nav-link-custom">Panduan Peta & Hotel</a>
        </nav>

        <div>
            <a href="{{ url('/admin/login') }}" class="btn btn-sm btn-primary px-5 py-2.5 rounded-pill" style="background-color: var(--apkasi-green); border-color: var(--apkasi-green);">
                <i class="ki-duotone ki-user-square fs-5 me-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                Area Admin
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero-section text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="hero-badge">
                        <i class="ki-duotone ki-pin text-warning fs-6"><span class="path1"></span><span class="path2"></span></i>
                        Kabupaten Deli Serdang, Sumatera Utara
                    </div>
                    
                    <h1 class="hero-title text-white">
                        Penguatan Sinergi Kabupaten<br/>
                        <span>Membangun Otonomi Daerah</span>
                    </h1>
                    
                    <p class="hero-desc">
                        Selamat Datang di Portal Resmi Acara Rangkaian Hari Ulang Tahun (HUT) Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang Tahun 2026. Bersinergi memperkuat pembangunan untuk Indonesia Maju.
                    </p>

                    <!-- Target Date Countdown -->
                    <div class="d-flex flex-wrap justify-content-center gap-3 mb-8">
                        <div class="countdown-card">
                            <div class="countdown-num" id="cd-days">00</div>
                            <div class="countdown-label">Hari</div>
                        </div>
                        <div class="countdown-card">
                            <div class="countdown-num" id="cd-hours">00</div>
                            <div class="countdown-label">Jam</div>
                        </div>
                        <div class="countdown-card">
                            <div class="countdown-num" id="cd-mins">00</div>
                            <div class="countdown-label">Menit</div>
                        </div>
                        <div class="countdown-card">
                            <div class="countdown-num" id="cd-secs">00</div>
                            <div class="countdown-label">Detik</div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="#agenda" class="btn btn-lg btn-warning px-6 py-4 rounded-pill fw-bold" style="background-color: var(--apkasi-accent); border-color: var(--apkasi-accent); color: #173423;">
                            Lihat Jadwal Agenda
                        </a>
                        <a href="{{ route('guide') }}" class="btn btn-lg btn-outline-light px-6 py-4 rounded-pill fw-bold border-2">
                            Peta Lokasi & Hotel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Logo Collaboration Banner -->
    <div class="py-10 bg-light" style="border-bottom: 1px solid #edf4ef;">
        <div class="container text-center">
            <h5 class="fs-7 text-muted text-uppercase fw-bold tracking-wider mb-6">Kolaborasi Penyelenggara & Sponsor Utama</h5>
            <div class="row justify-content-center align-items-center g-5">
                <div class="col-6 col-sm-4 col-md-2.5">
                    <div class="logo-box">
                        <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/01_APKASI_Official Logo.png') }}" alt="APKASI Official" />
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2.5">
                    <div class="logo-box">
                        <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/05_HUT  Apkasi Tahun 2026.png') }}" alt="HUT APKASI 2026" />
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2.5">
                    <div class="logo-box">
                        <img src="{{ asset('assets/apkasi/z_04_LOGO-LOGO APKASI/07_AOE2026_MASTER.png') }}" alt="AOE 2026" />
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-2.5">
                    <div class="logo-box">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Logo_Deli_Serdang.png" alt="Kabupaten Deli Serdang" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="container">
            <div class="row align-items-center g-10">
                <div class="col-lg-6">
                    <div class="pe-lg-8">
                        <span class="section-badge">Tentang Event</span>
                        <h2 class="fs-1 text-gray-900 mb-6">Sinergi Dua Hari Jadi Besar Di Kabupaten Deli Serdang</h2>
                        <p class="fs-6 text-muted mb-4 leading-relaxed">
                            Rangkaian kegiatan ini merupakan wujud syukur kolosal atas perayaan **HUT APKASI (Asosiasi Pemerintah Kabupaten Seluruh Indonesia) Ke-26** sekaligus memperingati **HUT Kabupaten Deli Serdang Ke-80**. 
                        </p>
                        <p class="fs-6 text-muted mb-6 leading-relaxed">
                            Bertindak sebagai tuan rumah penyelenggaraan, Pemerintah Kabupaten Deli Serdang menyambut perwakilan dari seluruh pemerintah daerah di tanah air untuk membahas arah strategis otonomi daerah, mempercepat kemandirian ekonomi, serta memperkuat peran perempuan di daerah.
                        </p>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-40px symbol-circle bg-light-success text-success d-flex align-items-center justify-content-center">
                                        <i class="ki-duotone ki-check fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <span class="fw-bold text-gray-800">400+ Delegasi Daerah</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-40px symbol-circle bg-light-success text-success d-flex align-items-center justify-content-center">
                                        <i class="ki-duotone ki-check fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <span class="fw-bold text-gray-800">Pameran Bazar & UMKM</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-40px symbol-circle bg-light-success text-success d-flex align-items-center justify-content-center">
                                        <i class="ki-duotone ki-check fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <span class="fw-bold text-gray-800">Malam Budaya & Final POI</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="symbol symbol-40px symbol-circle bg-light-success text-success d-flex align-items-center justify-content-center">
                                        <i class="ki-duotone ki-check fs-4"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <span class="fw-bold text-gray-800">Aksi Lestari Fun Walk</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 24px;">
                        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80" alt="Seminar Otonomi" class="card-img" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Agenda Highlights Section -->
    <section id="agenda" class="py-20 bg-light" style="border-top: 1px solid #eaf2eb; border-bottom: 1px solid #eaf2eb;">
        <div class="container">
            <div class="text-center mb-16">
                <span class="section-badge">Jadwal Agenda</span>
                <h2 class="fs-1 text-gray-900 mb-4">Rangkaian Acara Utama (1 - 3 Juli 2026)</h2>
                <p class="fs-6 text-muted max-w-600 mx-auto">Informasi waktu, tempat, dan jenis kegiatan selama rangkaian hari jadi berlangsung.</p>
            </div>

            <div class="row g-6">
                @foreach($rundownHighlights as $highlight)
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 event-card shadow-sm">
                            <div class="card-body p-8 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-6">
                                    <span class="badge badge-light-success fw-bold px-3 py-2 fs-8 rounded-pill">{{ $highlight['day'] }}</span>
                                    <div class="event-icon-box">
                                        <i class="ki-duotone {{ $highlight['icon'] }} fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                    </div>
                                </div>
                                <span class="fs-8 text-primary fw-bold mb-2">{{ $highlight['date'] }}</span>
                                <h4 class="text-gray-900 fw-bold mb-3 fs-5">{{ $highlight['title'] }}</h4>
                                <p class="text-muted fs-7 mb-6 leading-relaxed flex-grow-1">{{ $highlight['desc'] }}</p>
                                
                                <div class="separator mb-4"></div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ki-duotone ki-pin text-gray-600 fs-6"><span class="path1"></span><span class="path2"></span></i>
                                    <span class="fs-7 fw-semibold text-gray-800">{{ $highlight['location'] }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <i class="ki-duotone ki-time text-gray-600 fs-6"><span class="path1"></span><span class="path2"></span></i>
                                    <span class="fs-8 text-gray-600">{{ $highlight['time'] }} WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('guide') }}" class="btn btn-primary px-8 py-4 rounded-pill" style="background-color: var(--apkasi-green); border-color: var(--apkasi-green);">
                    Buka Peta Lokasi & Peta Hotel Detail
                    <i class="ki-duotone ki-right fs-4 ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Putri Otonomi Highlight Section -->
    <section id="poi" class="py-20 bg-white">
        <div class="container">
            <div class="poi-card card shadow-lg p-10 p-lg-15">
                <div class="row align-items-center g-10">
                    <div class="col-lg-7">
                        <div class="pe-lg-5">
                            <span class="badge badge-light-warning text-warning fw-bold px-4 py-2 fs-7 rounded-pill mb-4 text-uppercase">Special Event</span>
                            <h2 class="display-font text-white mb-6 fs-1">Malam Grand Final Putri Otonomi Indonesia 2026</h2>
                            <p class="fs-6 text-white text-opacity-80 mb-6 leading-relaxed">
                                Ajang bergengsi pemilihan duta otonomi daerah yang akan menjadi representasi kecerdasan, bakat, dan pesona putri daerah dari seluruh penjuru kabupaten di Indonesia. Malam penobatan puncak akan dilaksanakan pada **Kamis malam, 2 Juli 2026** bertempat di **Graha Bhineka Deli Serdang**.
                            </p>
                            
                            <div class="row g-4 mb-8">
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="ki-duotone ki-crown fs-2 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                        <div>
                                            <div class="fw-bold text-white fs-6">Penobatan Juara</div>
                                            <div class="fs-8 text-white text-opacity-70">Duta Otonomi Nasional</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="ki-duotone ki-address-book fs-2 text-warning"><span class="path1"></span><span class="path2"></span></i>
                                        <div>
                                            <div class="fw-bold text-white fs-6">Dihadiri 400+ Kepala Daerah</div>
                                            <div class="fs-8 text-white text-opacity-70">Para Bupati & Tokoh Nasional</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="card border-0 overflow-hidden" style="border-radius: 16px;">
                            <img src="https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=600&q=80" alt="Putri Otonomi Indonesia" class="card-img" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Accordion FAQ / Panduan Dresscode -->
    <section class="py-20 bg-light" style="border-top: 1px solid #eaf2eb;">
        <div class="container">
            <div class="text-center mb-16">
                <span class="section-badge">Panduan Cepat</span>
                <h2 class="fs-1 text-gray-900 mb-4">Informasi Penting Untuk Delegasi</h2>
                <p class="fs-6 text-muted max-w-600 mx-auto">Panduan praktis seputar registrasi, tata pakaian, stempel SPPD, dan transportasi selama event berlangsung.</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion faq-accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Apa saja Dresscode (Tata Pakaian) untuk Kepala Daerah (Bupati)?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted leading-relaxed">
                                    <ul>
                                        <li><strong>Welcome Dinner & Syukuran (1 Juli)</strong>: Pakaian Batik khas Deli Serdang / Batik khas daerah asal.</li>
                                        <li><strong>Dialog & FORBISDA (2 Juli)</strong>: Kemeja Putih Resmi APKASI.</li>
                                        <li><strong>Malam Final POI (2 Juli)</strong>: Batik Resmi APKASI.</li>
                                        <li><strong>Fun Walk (3 Juli)</strong>: Kaos olahraga, Topi, dan Gelang peserta khusus yang disediakan oleh APKASI.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Di mana dan kapan stempel SPPD / Surat Tugas dapat diproses?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted leading-relaxed">
                                    Stempel dan pengesahan SPPD/Surat Tugas delegasi dapat dilakukan di <strong>Meja Registrasi Delegasi</strong> yang berada di lokasi berikut:
                                    <ul>
                                        <li>IKM Hall (Selama Dialog Otonomi & Forum Bisnis) pada tanggal 2 Juli pukul 08.00 - 15.00 WIB.</li>
                                        <li>Graha Bhineka (Sebelum Welcome Dinner) pada tanggal 1 Juli pukul 18.00 - 20.00 WIB.</li>
                                    </ul>
                                    Pastikan membawa dokumen cetak Surat Tugas masing-masing daerah.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Bagaimana pembagian transportasi dan shuttle bagi delegasi?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted leading-relaxed">
                                    Panitia Tuan Rumah (Pemkab Deli Serdang) menyediakan <strong>Bus & Mobil Shuttle khusus</strong> dari hotel-hotel rekomendasi menuju venue acara pulang-pergi (PP). 
                                    Bagi delegasi yang menghendaki mobil privat, panitia juga bekerja sama dengan penyedia rental mobil lokal yang terdaftar (kontak WhatsApp dapat dilihat di halaman panduan).
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
        // Shrink header on scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.glass-header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Simple Active Class Switcher on Scroll
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-link-custom');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });

        // Target Date Countdown (1 July 2026 19:00:00)
        const targetDate = new Date("{{ $eventTargetDate }}").getTime();

        const updateCountdown = () => {
            const now = new Date().getTime();
            const difference = targetDate - now;

            if (difference <= 0) {
                document.getElementById('cd-days').innerText = "00";
                document.getElementById('cd-hours').innerText = "00";
                document.getElementById('cd-mins').innerText = "00";
                document.getElementById('cd-secs').innerText = "00";
                return;
            }

            const days = Math.floor(difference / (1000 * 60 * 60 * 24));
            const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((difference % (1000 * 60)) / 1000);

            document.getElementById('cd-days').innerText = String(days).padStart(2, '0');
            document.getElementById('cd-hours').innerText = String(hours).padStart(2, '0');
            document.getElementById('cd-mins').innerText = String(minutes).padStart(2, '0');
            document.getElementById('cd-secs').innerText = String(seconds).padStart(2, '0');
        };

        // Run immediately and then every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>
</html>
