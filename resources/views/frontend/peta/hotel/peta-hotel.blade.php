@extends('frontend.layouts.apkasi')

@section('title', 'Peta Lokasi & Hotel — HUT Ke-26 APKASI & Deli Serdang Ke-80')
@section('description', 'Peta lokasi venue acara dan rekomendasi hotel untuk delegasi HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang.')

@php
    $places   = collect($places);
    $venueCnt = $places->where('category', 'venue')->count();
    $hotelCnt = $places->where('category', 'hotel')->count();
    $gmaps = fn($p) => $p->maps_url ?: ('https://www.google.com/maps/search/?api=1&query=' . $p->lat . ',' . $p->lng);
@endphp

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/mapbox-gl@3.24.0/dist/mapbox-gl.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/mapbox-gl@3.24.0/dist/mapbox-gl.js"></script>
    <style>
        .pcard { position: relative; overflow: hidden; }
        .pcard .accent { position: absolute; left: 0; top: 0; bottom: 0; width: 4px; opacity: 0; transition: opacity .25s ease; }
        .pcard:hover .accent, .pcard.is-active .accent { opacity: 1; }
        .pcard.is-active { border-color: #85AB8B !important; box-shadow: 0 16px 32px rgba(43,84,58,.12); transform: translateY(-2px); }
        .pin { width: 26px; height: 26px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 2px solid #fff; box-shadow: 0 3px 8px rgba(0,0,0,.3); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform .2s ease; }
        .pin-venue { background: #336443; }
        .pin-hotel { background: #D4AF37; }
        .pin::after { content: ''; width: 8px; height: 8px; border-radius: 50%; background: #fff; transform: rotate(45deg); }
        .pin.active { transform: rotate(-45deg) scale(1.32); z-index: 3; }
        .mapboxgl-popup-content { border-radius: 14px; padding: 13px 16px; font-family: 'Plus Jakarta Sans', sans-serif; box-shadow: 0 10px 30px rgba(0,0,0,.18); }
        .pop-name { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .95rem; color: #1f2a1d; margin-bottom: 3px; }
        .pop-addr { font-size: .78rem; color: #4b5b47; margin-bottom: 7px; }
        .pop-link { font-size: .78rem; font-weight: 700; color: #336443; text-decoration: none; }
        .place-scroll::-webkit-scrollbar { width: 8px; }
        .place-scroll::-webkit-scrollbar-thumb { background: #c4d6c9; border-radius: 99px; }
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-apkasi-cream">

    {{-- ═══════════ NAVBAR ═══════════ --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 py-3 sm:py-4">
        <div id="nav-inner" class="mx-auto flex items-center justify-between transition-all duration-500 max-w-6xl mx-4 sm:mx-6 lg:mx-auto px-4 sm:px-6 bg-white/70 backdrop-blur-md rounded-full shadow-sm border border-white/60 py-2">
            <a href="{{ route('home') }}" class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                <img src="{{ asset('logos/logo-ds.png') }}" alt="Deli Serdang" class="h-8 sm:h-9 w-auto object-contain" />
                <img src="{{ asset('logos/apkasi-alt2.png') }}" alt="APKASI" class="h-7 sm:h-8 w-auto object-contain" />
                <img src="{{ asset('logos/aoe2026.png') }}" alt="AOE 2026" class="hidden sm:block h-7 sm:h-8 w-auto object-contain" />
            </a>

            <div class="hidden lg:flex items-center gap-1 absolute left-1/2 -translate-x-1/2">
                <a href="{{ route('home') }}" class="text-sm px-4 py-2 rounded-full font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5 transition-colors">Beranda</a>
                <a href="{{ route('home') }}#agenda" class="text-sm px-4 py-2 rounded-full font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5 transition-colors">Agenda</a>
                <a href="{{ route('home') }}#poi" class="text-sm px-4 py-2 rounded-full font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5 transition-colors">Putri Otonomi</a>
                <span class="text-sm px-4 py-2 rounded-full font-semibold text-apkasi-dark bg-apkasi-dark/5">Peta & Hotel</span>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <a href="{{ route('home') }}" class="hidden md:inline-flex items-center gap-1.5 text-sm font-medium text-apkasi-body hover:text-apkasi-dark transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Beranda
                </a>
                <a href="{{ url('/admin/login') }}" class="hidden sm:inline-flex items-center gap-1.5 bg-apkasi-dark hover:bg-apkasi-hover text-white text-sm font-semibold px-5 py-2 rounded-full transition-colors">
                    <i data-lucide="globe" class="w-3.5 h-3.5"></i> Portal DS
                </a>
            </div>
        </div>
    </nav>

    {{-- ═══════════ HERO / PAGE HEADER ═══════════ --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-apkasi-dark via-[#223d2c] to-apkasi-cta pt-32 sm:pt-36 pb-16 sm:pb-20">
        <div class="absolute -right-20 -bottom-28 w-[380px] h-[380px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(212,175,55,0.22), transparent 70%)"></div>
        <div class="absolute -left-24 -top-24 w-[320px] h-[320px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(255,255,255,0.06), transparent 70%)"></div>

        <div class="relative max-w-6xl mx-auto px-5 sm:px-8">
            <div class="text-xs text-white/55 mb-4">
                <a href="{{ route('home') }}" class="hover:text-apkasi-goldlt transition-colors">Beranda</a>
                <span class="mx-2">/</span> Peta Lokasi & Hotel
            </div>

            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/15 text-white text-xs font-semibold px-4 py-1.5 rounded-full backdrop-blur-sm mb-4">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-apkasi-goldlt"></i> Kabupaten Deli Serdang, Sumatera Utara
            </span>

            <h1 class="font-display font-bold text-white text-3xl sm:text-4xl md:text-[3rem] leading-tight tracking-tight">
                Peta Lokasi <span class="text-apkasi-accent">& Hotel</span>
            </h1>
            <p class="mt-3 text-white/75 text-sm sm:text-base leading-relaxed max-w-2xl">
                Temukan lokasi venue rangkaian acara dan rekomendasi penginapan terdekat untuk delegasi.
                Klik kartu untuk menyorot titik pada peta, atau buka langsung ke Google Maps untuk navigasi.
            </p>

            <div class="mt-7 flex flex-wrap gap-3">
                @foreach ([['map-pin', $venueCnt ?: '—', 'Lokasi Acara'], ['building-2', $hotelCnt ?: '—', 'Hotel Rekomendasi'], ['calendar', '1–3 Juli', 'Rangkaian Acara 2026'], ['plane', "± 20–30'", 'Dari Bandara Kualanamu']] as $st)
                    <div class="flex items-center gap-3 bg-white/8 border border-white/15 rounded-2xl px-4 py-3 backdrop-blur-sm">
                        <div class="w-10 h-10 rounded-xl bg-apkasi-gold/15 flex items-center justify-center shrink-0">
                            <i data-lucide="{{ $st[0] }}" class="w-5 h-5 text-apkasi-gold"></i>
                        </div>
                        <div>
                            <div class="font-display font-extrabold text-white text-base leading-none">{{ $st[1] }}</div>
                            <div class="text-[11px] text-white/65 mt-1">{{ $st[2] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════ TOOLBAR ═══════════ --}}
    <div class="max-w-6xl mx-auto px-5 sm:px-8 -mt-9 relative z-10">
        <div class="bg-white border border-apkasi-leaf rounded-2xl shadow-[0_18px_40px_rgba(43,84,58,0.08)] p-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <div class="relative flex-1 min-w-0">
                <i data-lucide="search" class="w-4 h-4 text-apkasi-body/50 absolute left-4 top-1/2 -translate-y-1/2"></i>
                <input id="searchInput" type="text" placeholder="Cari nama lokasi atau hotel..." autocomplete="off"
                    class="w-full bg-apkasi-leaf/30 border border-apkasi-leaf rounded-full pl-11 pr-4 py-2.5 text-sm text-apkasi-dark placeholder:text-apkasi-body/50 focus:outline-none focus:border-apkasi-accent focus:bg-white transition-colors" />
            </div>
            <div class="flex gap-2 flex-wrap" id="filterTabs">
                @foreach ([['all', 'Semua', $places->count()], ['venue', 'Lokasi Acara', $venueCnt], ['hotel', 'Hotel', $hotelCnt]] as $tab)
                    <button type="button" data-cat="{{ $tab[0] }}"
                        class="filter-tab text-sm font-semibold px-4 py-2.5 rounded-full border transition-colors {{ $loop->first ? 'bg-apkasi-heading border-apkasi-heading text-white' : 'bg-white border-apkasi-leaf text-apkasi-body hover:border-apkasi-accent' }}">
                        {{ $tab[1] }} <span class="opacity-60 font-medium ml-0.5">{{ $tab[2] }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══════════ MAP + LIST ═══════════ --}}
    <section class="max-w-6xl mx-auto px-5 sm:px-8 pt-8 pb-16 sm:pb-20">
        <div class="grid lg:grid-cols-12 gap-6">
            {{-- List --}}
            <div class="lg:col-span-5 order-2 lg:order-1">
                <div class="flex items-baseline justify-between mb-4">
                    <h2 class="font-display text-xl font-bold text-apkasi-dark">Daftar Tempat</h2>
                    <span id="listCount" class="text-xs font-semibold text-apkasi-body">Menampilkan {{ $places->count() }} tempat</span>
                </div>

                <div id="placeList" class="place-scroll flex flex-col gap-3.5 lg:max-h-[74vh] lg:overflow-y-auto lg:pr-1.5">
                    @forelse ($places as $p)
                        @php $isHotel = $p->category === 'hotel'; @endphp
                        <div data-card data-id="{{ $p->id }}" data-cat="{{ $p->category }}" data-name="{{ strtolower($p->name) }}" data-addr="{{ strtolower($p->address ?? '') }}"
                            class="pcard group flex gap-3.5 p-3.5 rounded-2xl border border-apkasi-leaf bg-white cursor-pointer transition-all duration-300 hover:border-apkasi-accent/50 hover:-translate-y-0.5 hover:shadow-lg">
                            <span class="accent {{ $isHotel ? 'bg-apkasi-gold' : 'bg-apkasi-heading' }}"></span>
                            @if ($p->image)
                                <img src="{{ $p->image }}" alt="" loading="lazy" class="w-24 h-24 rounded-xl object-cover shrink-0 bg-apkasi-leaf" />
                            @else
                                <div class="w-24 h-24 rounded-xl bg-apkasi-leaf flex items-center justify-center shrink-0 text-apkasi-accent">
                                    <i data-lucide="{{ $isHotel ? 'building-2' : 'map-pinned' }}" class="w-7 h-7"></i>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full mb-1.5 {{ $isHotel ? 'bg-apkasi-gold/15 text-[#9a7d16]' : 'bg-apkasi-heading/10 text-apkasi-heading' }}">
                                    {{ $isHotel ? 'Hotel' : 'Lokasi Acara' }}
                                </span>
                                <h3 class="font-bold text-apkasi-dark text-[15px] leading-snug">{{ $p->name }}</h3>
                                @if ($p->address)
                                    <p class="flex items-start gap-1.5 text-xs text-apkasi-body mt-1 leading-relaxed">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-apkasi-body/50 mt-0.5 shrink-0"></i> {{ $p->address }}
                                    </p>
                                @endif
                                @if ($p->description)
                                    <p class="text-xs text-apkasi-body/80 mt-1.5 leading-relaxed">{{ $p->description }}</p>
                                @endif
                                <div class="flex items-center gap-3 mt-2 text-xs text-apkasi-body flex-wrap">
                                    @if ($p->rating)
                                        <span class="flex items-center gap-1 font-bold text-[#b9931f]"><i data-lucide="star" class="w-3.5 h-3.5"></i> {{ $p->rating }}</span>
                                    @endif
                                    @if ($p->price_range)
                                        <span class="font-semibold">{{ $p->price_range }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mt-3 flex-wrap">
                                    <button type="button" data-focus="{{ $p->id }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-heading hover:text-white hover:border-apkasi-heading transition-colors">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Lihat di Peta
                                    </button>
                                    <a href="{{ $gmaps($p) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-gold hover:text-apkasi-dark hover:border-apkasi-gold transition-colors">
                                        <i data-lucide="navigation" class="w-3.5 h-3.5"></i> Rute
                                    </a>
                                    @if ($isHotel && $p->phone)
                                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $p->phone) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-accent hover:text-white hover:border-apkasi-accent transition-colors">
                                            <i data-lucide="phone" class="w-3.5 h-3.5"></i> Telepon
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-sm text-apkasi-body/70">Belum ada data lokasi.</div>
                    @endforelse
                    <div id="emptyNote" class="hidden text-center py-12 text-sm text-apkasi-body/70">Tidak ada lokasi yang cocok dengan pencarian/filter ini.</div>
                </div>
            </div>

            {{-- Map --}}
            <div class="lg:col-span-7 order-1 lg:order-2">
                <div class="relative rounded-3xl overflow-hidden border border-apkasi-leaf shadow-[0_20px_50px_rgba(43,84,58,0.10)] lg:sticky lg:top-24">
                    <div id="map" class="w-full h-[56vh] lg:h-[78vh] min-h-[360px] bg-apkasi-leaf/40"></div>

                    @if (empty($mapboxToken))
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center gap-2 p-8 text-apkasi-body">
                            <i data-lucide="map-pin" class="w-10 h-10 text-apkasi-accent/50"></i>
                            <p><strong>Peta belum aktif.</strong><br>Setel <code>MAPBOX_TOKEN</code> di file .env.</p>
                        </div>
                    @else
                        <button id="mapFit" type="button" class="absolute top-3.5 left-3.5 z-[5] inline-flex items-center gap-1.5 bg-white/90 backdrop-blur text-apkasi-heading text-xs font-bold px-3.5 py-2 rounded-full shadow-md hover:bg-apkasi-heading hover:text-white transition-colors">
                            <i data-lucide="maximize-2" class="w-3.5 h-3.5"></i> Tampilkan semua
                        </button>
                    @endif

                    <div class="absolute bottom-3.5 left-3.5 z-[5] bg-white/90 backdrop-blur rounded-xl px-3.5 py-2.5 shadow-md text-xs">
                        <div class="flex items-center gap-2 font-semibold text-apkasi-body my-0.5"><span class="w-3 h-3 rounded-full bg-apkasi-heading inline-block"></span> Lokasi Acara / Venue</div>
                        <div class="flex items-center gap-2 font-semibold text-apkasi-body my-0.5"><span class="w-3 h-3 rounded-full bg-apkasi-gold inline-block"></span> Hotel & Penginapan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA ke Panduan --}}
        <div class="mt-10">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-apkasi-dark to-apkasi-cta p-8 sm:p-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(212,175,55,0.22), transparent 70%)"></div>
                <div class="relative">
                    <h3 class="font-display text-white text-lg sm:text-xl font-bold mb-2">Butuh info transportasi & destinasi wisata?</h3>
                    <p class="text-white/75 text-sm max-w-xl">Lihat panduan lengkap delegasi: rekomendasi rental kendaraan, destinasi wisata Deli Serdang, serta detail tiap venue acara.</p>
                </div>
                <a href="{{ route('guide') }}" class="relative shrink-0 inline-flex items-center gap-2 bg-apkasi-gold hover:bg-apkasi-goldlt text-apkasi-dark text-sm font-bold px-6 py-3 rounded-full transition-colors">
                    Buka Panduan Lengkap <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════ FOOTER ═══════════ --}}
    <footer class="bg-apkasi-dark border-t-4 border-apkasi-gold">
        <div class="max-w-6xl mx-auto px-5 sm:px-8 py-10 text-center">
            <div class="flex items-center justify-center gap-4 mb-5">
                <img src="{{ asset('logos/apkasi-logo.png') }}" alt="APKASI" class="h-10 brightness-0 invert" />
                <img src="{{ asset('logos/logo-ds.png') }}" alt="Deli Serdang" class="h-10" />
            </div>
            <h5 class="font-display text-white font-bold mb-1.5">HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang</h5>
            <p class="text-white/50 text-xs mb-5">Sekretariat APKASI & Dinas Kominfo Kabupaten Deli Serdang, Sumatera Utara.</p>
            <div class="border-t border-white/10 pt-5">
                <p class="text-[11px] text-white/30">&copy; 2026 Pemerintah Kabupaten Deli Serdang & APKASI. All rights reserved.</p>
            </div>
        </div>
    </footer>
</div>
@endsection

@push('scripts')
<script>
    // ── Navbar scroll shrink ──
    (function () {
        var navbar = document.getElementById('navbar');
        var navInner = document.getElementById('nav-inner');
        var NAV_BASE = 'fixed top-0 left-0 right-0 z-50 transition-all duration-500';
        var INNER_BASE = 'mx-auto flex items-center justify-between transition-all duration-500 py-2';
        var INNER_TOP = 'max-w-6xl mx-4 sm:mx-6 lg:mx-auto px-4 sm:px-6 bg-white/70 backdrop-blur-md rounded-full shadow-sm border border-white/60';
        var INNER_SCR = 'max-w-[95%] xl:max-w-[90%] px-4 sm:px-6 bg-white shadow-lg border border-gray-100 rounded-2xl';
        function onScroll() {
            if (window.scrollY > 40) { navbar.className = NAV_BASE + ' py-1.5 sm:py-2'; navInner.className = INNER_BASE + ' ' + INNER_SCR; }
            else { navbar.className = NAV_BASE + ' py-3 sm:py-4'; navInner.className = INNER_BASE + ' ' + INNER_TOP; }
        }
        window.addEventListener('scroll', onScroll, { passive: true });
    })();

    // ── Data dari controller ──
    var PLACES = @json($places->values());
    var MAP_CENTER = @json($center);        // [lng, lat]
    var TOKEN = @json($mapboxToken);

    var activeCat = 'all', query = '';
    var map = null, mapReady = false;
    var markers = {};   // id -> mapboxgl.Marker
    var selectedId = null;

    function gmapsUrl(p) { return p.maps_url || ('https://www.google.com/maps/search/?api=1&query=' + p.lat + ',' + p.lng); }

    function isVisible(p) {
        var catOk = activeCat === 'all' || p.category === activeCat;
        var qOk = !query || (p.name || '').toLowerCase().indexOf(query) >= 0 || (p.address || '').toLowerCase().indexOf(query) >= 0;
        return catOk && qOk;
    }

    function fitToVisible() {
        if (!map) return;
        var pts = PLACES.filter(isVisible);
        if (!pts.length) return;
        if (pts.length === 1) { map.flyTo({ center: [pts[0].lng, pts[0].lat], zoom: 14, duration: 700 }); return; }
        var b = new mapboxgl.LngLatBounds();
        pts.forEach(function (p) { b.extend([p.lng, p.lat]); });
        map.fitBounds(b, { padding: 70, maxZoom: 15, duration: 700 });
    }

    function focusPlace(id, fromCard) {
        selectedId = id;
        var p = PLACES.find(function (x) { return String(x.id) === String(id); });
        if (p && map) {
            map.flyTo({ center: [p.lng, p.lat], zoom: 15, duration: 800 });
            if (markers[id] && !(markers[id].getPopup() && markers[id].getPopup().isOpen())) markers[id].togglePopup();
        }
        Object.keys(markers).forEach(function (k) { markers[k].getElement().classList.toggle('active', String(k) === String(id)); });
        document.querySelectorAll('[data-card]').forEach(function (c) {
            var on = c.dataset.id === String(id);
            c.classList.toggle('is-active', on);
            if (on && !fromCard) c.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    }

    function applyFilter() {
        var ids = {};
        var count = 0;
        document.querySelectorAll('[data-card]').forEach(function (c) {
            var p = { category: c.dataset.cat, name: c.dataset.name, address: c.dataset.addr };
            var ok = isVisible(p);
            c.style.display = ok ? '' : 'none';
            if (ok) { ids[c.dataset.id] = true; count++; }
        });
        var note = document.getElementById('emptyNote');
        if (note) note.classList.toggle('hidden', count !== 0);
        document.getElementById('listCount').textContent = 'Menampilkan ' + count + ' tempat';
        Object.keys(markers).forEach(function (k) { markers[k].getElement().style.display = ids[k] ? '' : 'none'; });
    }

    // Klik kartu & tombol
    document.querySelectorAll('[data-card]').forEach(function (c) {
        c.addEventListener('click', function () { focusPlace(c.dataset.id, true); });
    });
    document.querySelectorAll('[data-focus]').forEach(function (btn) {
        btn.addEventListener('click', function (e) { e.stopPropagation(); focusPlace(btn.dataset.focus, true); });
    });

    // Filter tabs
    document.querySelectorAll('.filter-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.filter-tab').forEach(function (b) {
                b.classList.remove('bg-apkasi-heading', 'border-apkasi-heading', 'text-white');
                b.classList.add('bg-white', 'border-apkasi-leaf', 'text-apkasi-body', 'hover:border-apkasi-accent');
            });
            btn.classList.add('bg-apkasi-heading', 'border-apkasi-heading', 'text-white');
            btn.classList.remove('bg-white', 'border-apkasi-leaf', 'text-apkasi-body', 'hover:border-apkasi-accent');
            activeCat = btn.dataset.cat;
            applyFilter();
            fitToVisible();
        });
    });

    // Search
    var search = document.getElementById('searchInput');
    if (search) search.addEventListener('input', function (e) { query = e.target.value.trim().toLowerCase(); applyFilter(); });

    // Fit all
    var fitBtn = document.getElementById('mapFit');
    if (fitBtn) fitBtn.addEventListener('click', fitToVisible);

    // Init Mapbox
    if (TOKEN) {
        mapboxgl.accessToken = TOKEN;
        map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/light-v11',
            center: MAP_CENTER,
            zoom: 11,
            attributionControl: false,
        });
        map.addControl(new mapboxgl.NavigationControl({ showCompass: false }), 'top-right');
        map.addControl(new mapboxgl.AttributionControl({ compact: true }));
        map.on('load', function () {
            PLACES.forEach(function (p) {
                var el = document.createElement('div');
                el.className = 'pin ' + (p.category === 'hotel' ? 'pin-hotel' : 'pin-venue');
                var popup = new mapboxgl.Popup({ offset: 26, closeButton: false }).setHTML(
                    '<div class="pop-name">' + p.name + '</div>' +
                    '<div class="pop-addr">' + (p.address || '') + '</div>' +
                    '<a class="pop-link" href="' + gmapsUrl(p) + '" target="_blank" rel="noopener">Buka di Google Maps &rarr;</a>'
                );
                var marker = new mapboxgl.Marker({ element: el, anchor: 'bottom' }).setLngLat([p.lng, p.lat]).setPopup(popup).addTo(map);
                el.addEventListener('click', function () { focusPlace(p.id, false); });
                markers[p.id] = marker;
            });
            mapReady = true;
            applyFilter();
            fitToVisible();
        });
    }

    if (window.lucide) lucide.createIcons();
</script>
@endpush
