@extends('frontend.layouts.apkasi')

@section('title', 'Panduan Delegasi — HUT Ke-26 APKASI & Deli Serdang Ke-80')
@section('description', 'Panduan delegasi HUT Ke-26 APKASI: daftar PIC per provinsi dan rental kendaraan.')

@php
    $wa = function ($no) {
        $d = preg_replace('/\D/', '', $no ?? '');
        if (str_starts_with($d, '0')) $d = '62' . substr($d, 1);
        return 'https://wa.me/' . $d;
    };
    $tel = fn($no) => 'tel:' . preg_replace('/[^0-9+]/', '', $no ?? '');
    $picCount = $pics->count();
    // Data rental utk peta (dibangun di @php agar tak kena salah-parse Blade pada @json + array/fn inline)
    $rentalGeo = $rentals->map(fn ($r) => [
        'id' => $r->id, 'nama' => $r->nama, 'alamat' => $r->alamat,
        'telepon' => $r->telepon, 'kontak_wa' => $r->kontak_wa,
        'lat' => $r->lat, 'lng' => $r->lng, 'maps_url' => $r->maps_url,
    ])->values();
@endphp

@push('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/mapbox-gl@3.24.0/dist/mapbox-gl.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/mapbox-gl@3.24.0/dist/mapbox-gl.js"></script>
    <style>
        /* DataTables PIC — compact & estetik */
        .dt-container { font-family: 'Plus Jakarta Sans', sans-serif; }
        #picTable { border-collapse: separate !important; border-spacing: 0; width: 100% !important; }
        #picTable thead th { background: #f3f7f3; color: #4a6b54; font-weight: 700; font-size: .66rem; text-transform: uppercase; letter-spacing: .07em; border: 0 !important; border-bottom: 1px solid #e3ece5 !important; padding: 9px 14px; }
        #picTable tbody td { font-size: .82rem; color: #2d3a2a; background: #fff; border: 0 !important; border-bottom: 1px solid #f1f5f1 !important; padding: 7px 14px; vertical-align: middle; }
        #picTable tbody tr:last-child td { border-bottom: 0 !important; }
        #picTable tbody tr:hover td { background: #f7faf7; }
        #picTable td.pic-merged { visibility: hidden; }                              /* baris lanjutan PIC sama -> disembunyikan (merge) */
        #picTable td.pic-merge-head { border-bottom-color: transparent !important; } /* sambungkan nama PIC ke baris bawahnya */
        .dt-container .dt-search input, .dt-container .dt-length select { border: 1px solid #e8f0ea; border-radius: 9999px; padding: .38rem .9rem; font-size: .8rem; outline: none; background: #fff; }
        .dt-container .dt-search input:focus { border-color: #85AB8B; }
        .dt-container .dt-search label, .dt-container .dt-length label, .dt-container .dt-info { font-size: .76rem; color: #6b7c70; }
        .dt-container .dt-paging .dt-paging-button { font-size: .78rem; padding: .25rem .55rem; margin: 0 1px; border-radius: 8px; border: 0 !important; min-width: 30px; }
        .dt-container .dt-paging .dt-paging-button.current { background: #336443 !important; color: #fff !important; }
        .dt-container .dt-paging .dt-paging-button:hover:not(.current) { background: #e8f0ea !important; color: #336443 !important; }
        .dt-container .dt-layout-row { margin-top: .4rem; margin-bottom: .4rem; }
        /* Mapbox popup rental */
        .mapboxgl-popup-content { border-radius: 14px; padding: 13px 16px; font-family: 'Plus Jakarta Sans', sans-serif; box-shadow: 0 10px 30px rgba(0,0,0,.18); }
        .pop { width: 230px; max-width: 76vw; }
        .pop-name { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .95rem; color: #1f2a1d; margin-bottom: 4px; }
        .pop-row { display: flex; align-items: flex-start; gap: 5px; font-size: .74rem; color: #4b5b47; margin-bottom: 4px; line-height: 1.35; }
        .pop-row svg { width: 13px; height: 13px; flex: none; margin-top: 2px; color: #85AB8B; }
        .pop-acts { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 9px; }
        .pop-acts a { display: inline-flex; align-items: center; gap: 4px; font-size: .72rem; font-weight: 700; text-decoration: none; padding: 5px 9px; border-radius: 8px; border: 1px solid #d7e3d9; color: #336443; }
        .pop-acts a svg { width: 13px; height: 13px; }
        .pop-acts a.wa { color: #fff; background: #25a567; border-color: #25a567; }
    </style>
@endpush

@push('scripts')
<script>
    (function () {
        var navbar = document.getElementById('navbar');
        var navInner = document.getElementById('nav-inner');
        var NAV_BASE = 'fixed top-0 left-0 right-0 z-50 transition-all duration-500';
        var INNER_BASE = 'mx-auto flex items-center justify-between transition-all duration-500 py-2';
        var INNER_TOP = 'max-w-[1400px] mx-4 sm:mx-6 lg:mx-auto px-4 sm:px-6 bg-white/70 backdrop-blur-md rounded-full shadow-sm border border-white/60';
        var INNER_SCR = 'max-w-[95%] xl:max-w-[90%] px-4 sm:px-6 bg-white shadow-lg border border-gray-100 rounded-2xl';
        function onScroll() {
            if (window.scrollY > 40) { navbar.className = NAV_BASE + ' py-1.5 sm:py-2'; navInner.className = INNER_BASE + ' ' + INNER_SCR; }
            else { navbar.className = NAV_BASE + ' py-3 sm:py-4'; navInner.className = INNER_BASE + ' ' + INNER_TOP; }
        }
        window.addEventListener('scroll', onScroll, { passive: true });
    })();

    // ── DataTables PIC ──
    var picDT = null;
    if (window.jQuery) {
        jQuery(function ($) {
            picDT = $('#picTable').DataTable({
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                order: [],                                  // pertahankan urutan server (sudah dikelompokkan per PIC)
                columnDefs: [{ targets: [0, 4], orderable: false }, { targets: [4], searchable: false }],
                language: {
                    search: 'Cari:', searchPlaceholder: 'provinsi / nama',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ PIC',
                    infoEmpty: 'Tidak ada data', infoFiltered: '(disaring dari _MAX_ total)',
                    zeroRecords: 'PIC tidak ditemukan',
                    paginate: { first: '«', previous: '‹', next: '›', last: '»' }
                },
                // No urut tampilan + GABUNG (merge) sel PIC/No HP/Kontak utk PIC yg sama & berurutan.
                // Non-destruktif (toggle class + reset tiap draw) supaya sort/filter ulang tetap benar.
                drawCallback: function () {
                    var prev = null, prevTd = null, n = 0;
                    $('#picTable tbody tr').each(function () {
                        var td = $(this).children('td');
                        if (td.length < 5) return;                              // lewati baris info/kosong
                        td.eq(0).text(++n);                                     // nomor urut tampilan
                        td.eq(2).add(td.eq(3)).add(td.eq(4)).removeClass('pic-merged pic-merge-head');
                        var key = td.eq(2).text().trim() + '|' + td.eq(3).text().trim();
                        if (key && key === prev) {                              // PIC sama dgn baris atas -> sembunyikan
                            td.eq(2).add(td.eq(3)).add(td.eq(4)).addClass('pic-merged');
                            if (prevTd) prevTd.eq(2).add(prevTd.eq(3)).add(prevTd.eq(4)).addClass('pic-merge-head');
                        }
                        prev = key; prevTd = td;
                    });
                    if (window.lucide) lucide.createIcons();
                }
            });
        });
    }

    // ── Peta Rental (Mapbox + GeoJSON, pola sama spt peta-hotel) ──
    var RENTALS = @json($rentalGeo);
    var RTOKEN = @json($mapboxToken ?? '');
    var rentalMap = null, rentalMapInit = false, rentalPopupRef = null;
    function rentalWa(no) { var d = (no || '').replace(/[^0-9]/g, ''); if (d.charAt(0) === '0') d = '62' + d.slice(1); return 'https://wa.me/' + d; }
    function rentalGmaps(p) { return p.maps_url || ('https://www.google.com/maps/search/?api=1&query=' + p.lat + ',' + p.lng); }
    function rentalOpen(p, ll) {
        var act = '<a href="' + rentalGmaps(p) + '" target="_blank" rel="noopener"><i data-lucide="navigation"></i> Rute</a>';
        if (p.kontak_wa) act += '<a class="wa" href="' + rentalWa(p.kontak_wa) + '" target="_blank" rel="noopener"><i data-lucide="message-circle"></i> WhatsApp</a>';
        if (p.telepon)   act += '<a href="tel:' + p.telepon.replace(/[^0-9+]/g, '') + '"><i data-lucide="phone"></i> Telp</a>';
        var h = '<div class="pop"><div class="pop-name">' + p.nama + '</div>'
              + (p.alamat ? '<div class="pop-row"><i data-lucide="map-pin"></i><span>' + p.alamat + '</span></div>' : '')
              + '<div class="pop-acts">' + act + '</div></div>';
        if (rentalPopupRef) rentalPopupRef.remove();
        rentalMap.flyTo({ center: ll, zoom: 14, duration: 700 });
        rentalPopupRef = new mapboxgl.Popup({ offset: 14, maxWidth: '270px' }).setLngLat(ll).setHTML(h).addTo(rentalMap);
        if (window.lucide) lucide.createIcons();
    }
    function initRentalMap() {
        if (rentalMapInit) { if (rentalMap) setTimeout(function () { rentalMap.resize(); }, 60); return; }
        var pts = RENTALS.filter(function (r) { return r.lat && r.lng; });
        if (!RTOKEN || !pts.length || !window.mapboxgl || !document.getElementById('rentalMap')) return;
        rentalMapInit = true;
        mapboxgl.accessToken = RTOKEN;
        var cx = pts.reduce(function (s, p) { return s + Number(p.lng); }, 0) / pts.length;
        var cy = pts.reduce(function (s, p) { return s + Number(p.lat); }, 0) / pts.length;
        rentalMap = new mapboxgl.Map({ container: 'rentalMap', style: 'mapbox://styles/mapbox/light-v11', center: [cx, cy], zoom: 10, attributionControl: false });
        rentalMap.addControl(new mapboxgl.NavigationControl({ showCompass: false }), 'top-right');
        rentalMap.addControl(new mapboxgl.AttributionControl({ compact: true }));
        var fc = { type: 'FeatureCollection', features: pts.map(function (p) { return { type: 'Feature', properties: { id: p.id }, geometry: { type: 'Point', coordinates: [Number(p.lng), Number(p.lat)] } }; }) };
        rentalMap.on('load', function () {
            rentalMap.addSource('rentals', { type: 'geojson', data: fc });
            rentalMap.addLayer({ id: 'rentals', type: 'circle', source: 'rentals', paint: { 'circle-radius': ['interpolate', ['linear'], ['zoom'], 8, 6, 14, 9], 'circle-color': '#336443', 'circle-stroke-width': 2.5, 'circle-stroke-color': '#ffffff' } });
            if (pts.length > 1) { var b = new mapboxgl.LngLatBounds(); pts.forEach(function (p) { b.extend([Number(p.lng), Number(p.lat)]); }); rentalMap.fitBounds(b, { padding: 60, maxZoom: 12, duration: 0 }); }
            rentalMap.on('click', 'rentals', function (e) { var id = e.features[0].properties.id; var p = RENTALS.find(function (x) { return String(x.id) === String(id); }); if (p) rentalOpen(p, [Number(p.lng), Number(p.lat)]); });
            rentalMap.on('mouseenter', 'rentals', function () { rentalMap.getCanvas().style.cursor = 'pointer'; });
            rentalMap.on('mouseleave', 'rentals', function () { rentalMap.getCanvas().style.cursor = ''; });
        });
    }

    // ── Tab switcher ──
    function activateTab(t) {
        document.querySelectorAll('[data-tab]').forEach(function (b) {
            var on = b.dataset.tab === t;
            b.classList.toggle('bg-apkasi-heading', on);
            b.classList.toggle('border-apkasi-heading', on);
            b.classList.toggle('text-white', on);
            b.classList.toggle('bg-white', !on);
            b.classList.toggle('border-apkasi-leaf', !on);
            b.classList.toggle('text-apkasi-body', !on);
        });
        document.querySelectorAll('[data-panel]').forEach(function (p) {
            p.classList.toggle('hidden', p.dataset.panel !== t);
        });
        if (t === 'pic' && picDT) picDT.columns.adjust();
        if (t === 'rental') initRentalMap();
    }
    document.querySelectorAll('[data-tab]').forEach(function (b) {
        b.addEventListener('click', function () { activateTab(b.dataset.tab); });
    });

    if (window.lucide) lucide.createIcons();
</script>
@endpush

@section('content')
@include('frontend.partials.pageloader')

<div class="min-h-screen bg-apkasi-cream">

    {{-- ═══════════ NAVBAR ═══════════ --}}
    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 py-3 sm:py-4">
        <div id="nav-inner" class="mx-auto flex items-center justify-between transition-all duration-500 max-w-[1400px] mx-4 sm:mx-6 lg:mx-auto px-4 sm:px-6 bg-white/70 backdrop-blur-md rounded-full shadow-sm border border-white/60 py-2">
            <a href="{{ route('home') }}" class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
                <img src="{{ asset('logos/logo-ds.png') }}" alt="Deli Serdang" class="h-8 sm:h-9 w-auto object-contain" />
                <img src="{{ asset('logos/apkasi-alt2.png') }}" alt="APKASI" class="h-7 sm:h-8 w-auto object-contain" />
                <img src="{{ asset('logos/aoe2026.png') }}" alt="AOE 2026" class="hidden sm:block h-7 sm:h-8 w-auto object-contain" />
            </a>

            <div class="hidden lg:flex items-center gap-1 absolute left-1/2 -translate-x-1/2">
                <a href="{{ route('home') }}" class="text-sm px-4 py-2 rounded-full font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5 transition-colors">Beranda</a>
                <a href="{{ route('home') }}#agenda" class="text-sm px-4 py-2 rounded-full font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5 transition-colors">Agenda</a>
                <a href="{{ route('peta-hotel') }}" class="text-sm px-4 py-2 rounded-full font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5 transition-colors">Peta & Hotel</a>
                <span class="text-sm px-4 py-2 rounded-full font-semibold text-apkasi-dark bg-apkasi-dark/5">Panduan</span>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <a href="{{ route('home') }}" class="hidden md:inline-flex items-center gap-1.5 text-sm font-medium text-apkasi-body hover:text-apkasi-dark transition-colors">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Beranda
                </a>
                <a href="https://portal.deliserdangkab.go.id/" target="_blank" rel="noopener" class="hidden sm:inline-flex items-center gap-1.5 bg-apkasi-dark hover:bg-apkasi-hover text-white text-sm font-semibold px-5 py-2 rounded-full transition-colors">
                    <i data-lucide="globe" class="w-3.5 h-3.5"></i> Portal DS
                </a>
            </div>
        </div>
    </nav>

    {{-- ═══════════ HERO ═══════════ --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-apkasi-dark via-[#223d2c] to-apkasi-cta pt-32 sm:pt-36 pb-16 sm:pb-20">
        <div class="absolute -right-20 -bottom-28 w-[380px] h-[380px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(212,175,55,0.22), transparent 70%)"></div>
        <div class="absolute -left-24 -top-24 w-[320px] h-[320px] rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(255,255,255,0.06), transparent 70%)"></div>

        <div class="relative max-w-[1400px] mx-auto px-5 sm:px-8">
            <div class="text-xs text-white/55 mb-4">
                <a href="{{ route('home') }}" class="hover:text-apkasi-goldlt transition-colors">Beranda</a>
                <span class="mx-2">/</span> Panduan Delegasi
            </div>
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/15 text-white text-xs font-semibold px-4 py-1.5 rounded-full backdrop-blur-sm mb-4">
                <i data-lucide="book-open" class="w-3.5 h-3.5 text-apkasi-goldlt"></i> Informasi Delegasi
            </span>
            <h1 class="font-display font-bold text-white text-3xl sm:text-4xl md:text-[3rem] leading-tight tracking-tight">
                Panduan <span class="text-apkasi-accent">Delegasi</span>
            </h1>
            <p class="mt-3 text-white/75 text-sm sm:text-base leading-relaxed max-w-2xl">
                Kontak PIC tiap provinsi dan rental kendaraan untuk delegasi. Untuk venue, hotel, dan destinasi wisata,
                lihat halaman <a href="{{ route('peta-hotel') }}" class="text-apkasi-goldlt font-semibold hover:underline">Peta & Hotel</a>.
            </p>

            <div class="mt-7 flex flex-wrap gap-3">
                @foreach ([['users', $picCount, 'PIC Provinsi'], ['car', count($rentals), 'Rental Kendaraan'], ['calendar', '1–3 Juli', 'Rangkaian Acara 2026']] as $st)
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

    {{-- ═══════════ TABS ═══════════ --}}
    <div class="max-w-[1400px] mx-auto px-5 sm:px-8 -mt-9 relative z-10">
        <div class="bg-white border border-apkasi-leaf rounded-2xl shadow-[0_18px_40px_rgba(43,84,58,0.08)] p-3 inline-flex gap-2">
            <button type="button" data-tab="pic" class="text-sm font-semibold px-5 py-2.5 rounded-full border bg-apkasi-heading border-apkasi-heading text-white transition-colors inline-flex items-center gap-2">
                <i data-lucide="users" class="w-4 h-4"></i> PIC per Provinsi
            </button>
            <button type="button" data-tab="rental" class="text-sm font-semibold px-5 py-2.5 rounded-full border bg-white border-apkasi-leaf text-apkasi-body hover:border-apkasi-accent transition-colors inline-flex items-center gap-2">
                <i data-lucide="car" class="w-4 h-4"></i> Rental Kendaraan
            </button>
        </div>
    </div>

    {{-- ═══════════ PANEL: PIC (DataTables) ═══════════ --}}
    <section data-panel="pic" class="max-w-[1400px] mx-auto px-5 sm:px-8 pt-8 pb-16 sm:pb-20">
        <div class="mb-5">
            <h2 class="font-display text-2xl font-bold text-apkasi-dark leading-tight">Nama & No HP PIC per Provinsi</h2>
            <p class="text-apkasi-body text-sm mt-1">Penanggung jawab (PIC) pendampingan delegasi tiap provinsi. Gunakan kolom <em>Cari</em> untuk memfilter.</p>
        </div>

        <div class="bg-white border border-apkasi-leaf rounded-2xl shadow-sm p-3 sm:p-4 overflow-x-auto">
            <table id="picTable" class="w-full" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Provinsi</th>
                        <th>PIC</th>
                        <th>No HP</th>
                        <th>Kontak</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pics as $p)
                        @php $prov = optional($p->provinsi)->nama ?? '-'; @endphp
                        <tr>
                            <td>{{ $p->urut }}</td>
                            <td class="font-semibold text-apkasi-dark">{{ $prov }}</td>
                            <td>{{ $p->nama }}</td>
                            <td class="tabular-nums">{{ $p->no_hp ?: '-' }}</td>
                            <td>
                                @if ($p->no_hp)
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ $wa($p->no_hp) }}" target="_blank" rel="noopener" title="WhatsApp" class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1.5 rounded-full bg-apkasi-heading/10 text-apkasi-heading hover:bg-apkasi-heading hover:text-white transition-colors">
                                            <i data-lucide="message-circle" class="w-3.5 h-3.5"></i> WA
                                        </a>
                                        <a href="{{ $tel($p->no_hp) }}" title="Telepon" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-apkasi-gold/15 text-[#9a7d16] hover:bg-apkasi-gold hover:text-apkasi-dark transition-colors">
                                            <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-apkasi-body/50 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- <p class="text-xs text-apkasi-body/60 mt-3">Catatan: DKI Jakarta belum tercantum PIC pada dokumen sumber.</p> -->
    </section>

    {{-- ═══════════ PANEL: RENTAL ═══════════ --}}
    <section data-panel="rental" class="hidden max-w-[1400px] mx-auto px-5 sm:px-8 pt-8 pb-16 sm:pb-20">
        <div class="mb-5">
            <h2 class="font-display text-2xl font-bold text-apkasi-dark leading-tight">Rental Kendaraan</h2>
            <p class="text-apkasi-body text-sm mt-1">Kontak penyedia sewa kendaraan untuk delegasi & rombongan.</p>
        </div>

        @if (!empty($rentalBanner))
            <div class="rounded-2xl overflow-hidden border border-apkasi-leaf mb-6 shadow-sm">
                <img src="{{ \App\Support\Media::url($rentalBanner) }}" alt="PIC Kendaraan APKASI 2026 — Partner Transportasi Terpercaya" loading="lazy" class="w-full h-auto block">
            </div>
        @endif

        @php $rentalsGeo = $rentals->filter(fn($r) => $r->lat && $r->lng); @endphp
        @if (($mapboxToken ?? false) && $rentalsGeo->count())
        <div class="rounded-2xl overflow-hidden border border-apkasi-leaf mb-6 shadow-sm relative">
            <div id="rentalMap" class="w-full h-[280px] sm:h-[360px] bg-apkasi-leaf"></div>
            <div class="absolute bottom-2.5 left-2.5 z-10 bg-white/90 backdrop-blur px-2.5 py-1 rounded-full text-[11px] font-medium text-apkasi-body flex items-center gap-1.5 pointer-events-none">
                <i data-lucide="map-pin" class="w-3 h-3 text-apkasi-heading"></i> Klik titik untuk detail & rute
            </div>
        </div>
        @endif

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($rentals as $r)
                @php $totalUnit = $r->mobil->sum('jumlah_unit'); @endphp
                <div class="bg-white rounded-2xl border border-apkasi-leaf p-5 hover:shadow-lg transition-all duration-300 flex flex-col">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-11 h-11 rounded-xl bg-apkasi-heading/10 flex items-center justify-center shrink-0">
                            <i data-lucide="car" class="w-5 h-5 text-apkasi-heading"></i>
                        </div>
                        <h3 class="font-bold text-apkasi-dark text-[15px] leading-snug">{{ $r->nama }}</h3>
                    </div>
                    @if ($r->alamat)
                        <p class="flex items-start gap-1.5 text-xs text-apkasi-body leading-relaxed mb-2"><i data-lucide="map-pin" class="w-3.5 h-3.5 text-apkasi-body/50 mt-0.5 shrink-0"></i> {{ $r->alamat }}</p>
                    @endif
                    @if ($r->deskripsi)
                        <p class="text-xs text-apkasi-body/60 italic leading-relaxed mb-2">{{ $r->deskripsi }}</p>
                    @endif
                    <div class="flex-1">
                        @if ($r->mobil->count())
                            <div class="flex flex-wrap gap-1.5 mb-2">
                                @foreach ($r->mobil as $m)
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-apkasi-leaf/60 text-apkasi-heading px-2.5 py-1 rounded-full">{{ $m->nama_mobil }} <span class="text-apkasi-body/70">×{{ $m->jumlah_unit }}</span></span>
                                @endforeach
                            </div>
                            <div class="text-xs font-semibold text-apkasi-heading"><i data-lucide="car" class="w-3.5 h-3.5 inline"></i> Total {{ $totalUnit }} unit tersedia</div>
                        @endif
                    </div>
                    @if ($r->kontak_wa || $r->telepon)
                        <div class="flex items-center gap-2 mt-4">
                            @if ($r->kontak_wa)
                                <a href="{{ $wa($r->kontak_wa) }}" target="_blank" rel="noopener" class="flex-1 inline-flex items-center justify-center gap-1.5 text-xs font-semibold px-3 py-2.5 rounded-full bg-apkasi-heading text-white hover:bg-apkasi-cta transition-colors">
                                    <i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp
                                </a>
                            @endif
                            @if ($r->telepon)
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $r->telepon) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-leaf/50 transition-colors">
                                    <i data-lucide="phone" class="w-4 h-4"></i> {{ $r->telepon }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-sm text-apkasi-body/70">Belum ada data rental.</div>
            @endforelse
        </div>
    </section>

    {{-- ═══════════ CTA: PETA ═══════════ --}}
    <section class="max-w-[1400px] mx-auto px-5 sm:px-8 pb-16 sm:pb-20">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-apkasi-dark to-apkasi-cta p-8 sm:p-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full pointer-events-none" style="background: radial-gradient(circle, rgba(212,175,55,0.22), transparent 70%)"></div>
            <div class="relative">
                <h3 class="font-display text-white text-lg sm:text-xl font-bold mb-2">Venue, Hotel & Destinasi Wisata</h3>
                <p class="text-white/75 text-sm max-w-xl">Semua lokasi venue acara, rekomendasi hotel, dan destinasi wisata Deli Serdang tersedia di peta interaktif.</p>
            </div>
            <a href="{{ route('peta-hotel') }}" class="relative shrink-0 inline-flex items-center gap-2 bg-apkasi-gold hover:bg-apkasi-goldlt text-apkasi-dark text-sm font-bold px-6 py-3 rounded-full transition-colors">
                Buka Peta Lokasi & Hotel <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </section>

    {{-- ═══════════ FOOTER ═══════════ --}}
    @include('frontend.partials.footer')
</div>
@endsection
