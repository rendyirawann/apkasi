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
        'mobil' => $r->mobil->map(fn ($m) => ['nama' => $m->nama_mobil, 'unit' => $m->jumlah_unit])->values(),
        'kontak' => $r->kontak->map(fn ($k) => ['nama' => $k->nama, 'no_hp' => $k->no_hp])->values(),
        'logo' => $r->logo_url,
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
        #picTable tbody td { vertical-align: top; }   /* baris PIC bisa multi-provinsi (chip) -> rata atas */
        .dt-container .dt-search input, .dt-container .dt-length select { border: 1px solid #e8f0ea; border-radius: 9999px; padding: .38rem .9rem; font-size: .8rem; outline: none; background: #fff; }
        .dt-container .dt-search input:focus { border-color: #85AB8B; }
        .dt-container .dt-search label, .dt-container .dt-length label, .dt-container .dt-info { font-size: .76rem; color: #6b7c70; }
        .dt-container .dt-paging .dt-paging-button { font-size: .78rem; padding: .25rem .55rem; margin: 0 1px; border-radius: 8px; border: 0 !important; min-width: 30px; }
        .dt-container .dt-paging .dt-paging-button.current { background: #336443 !important; color: #fff !important; }
        .dt-container .dt-paging .dt-paging-button:hover:not(.current) { background: #e8f0ea !important; color: #336443 !important; }
        .dt-container .dt-layout-row { margin-top: .4rem; margin-bottom: .4rem; }
        /* Mapbox popup rental */
        .mapboxgl-popup-content { border-radius: 14px; padding: 13px 16px; font-family: 'Plus Jakarta Sans', sans-serif; box-shadow: 0 10px 30px rgba(0,0,0,.18); }
        /* Tombol close popup — bulat, rapi, sejajar brand */
        .mapboxgl-popup-close-button {
            top: 8px; right: 8px; width: 24px; height: 24px;
            display: flex; align-items: center; justify-content: center;
            padding: 0; border: 0; border-radius: 50%;
            background: #f1f5f1; color: #5b6b56;
            font-size: 16px; line-height: 1; font-weight: 600;
            transition: background .15s ease, color .15s ease, transform .15s ease;
        }
        .mapboxgl-popup-close-button:hover { background: #2B543A; color: #fff; transform: scale(1.06); }
        .mapboxgl-popup-close-button:focus { outline: none; box-shadow: 0 0 0 3px rgba(43,84,58,.18); }
        .pop { width: 230px; max-width: 76vw; }
        .pop-name { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: .95rem; color: #1f2a1d; margin-bottom: 4px; padding-right: 22px; }
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

    // ── Kartu PIC: pencarian + pagination dinamis (6 kartu / halaman, tanpa reload) ──
    (function () {
        var PER = 6;
        var grid = document.getElementById('picGrid');
        if (!grid) return;
        var all = Array.prototype.slice.call(grid.querySelectorAll('.pic-card'));
        var search = document.getElementById('picSearch');
        var info = document.getElementById('picInfo');
        var btns = document.getElementById('picPageBtns');
        var empty = document.getElementById('picEmpty');
        var page = 1, filtered = all;

        function makeBtn(label, target, o) {
            var b = document.createElement('button');
            b.type = 'button';
            b.innerHTML = label;
            b.className = 'min-w-[34px] h-[34px] px-2.5 rounded-lg text-sm font-semibold transition-colors '
                + (o.active ? 'bg-apkasi-heading text-white' : 'bg-white border border-apkasi-leaf text-apkasi-body hover:border-apkasi-accent')
                + (o.disabled ? ' opacity-40 pointer-events-none' : '');
            if (!o.disabled && !o.active) b.addEventListener('click', function () { page = target; render(); });
            return b;
        }
        function render() {
            var total = filtered.length;
            var pages = Math.max(1, Math.ceil(total / PER));
            if (page > pages) page = pages;
            if (page < 1) page = 1;
            all.forEach(function (c) { c.style.display = 'none'; });
            var start = (page - 1) * PER;
            var slice = filtered.slice(start, start + PER);
            slice.forEach(function (c) { c.style.display = ''; });
            if (info) info.textContent = total ? ('Menampilkan ' + (start + 1) + '–' + (start + slice.length) + ' dari ' + total + ' PIC') : '';
            if (empty) empty.classList.toggle('hidden', total > 0);
            if (btns) {
                btns.innerHTML = '';
                if (pages > 1) {
                    btns.appendChild(makeBtn('‹', page - 1, { disabled: page <= 1 }));
                    for (var i = 1; i <= pages; i++) btns.appendChild(makeBtn(String(i), i, { active: i === page }));
                    btns.appendChild(makeBtn('›', page + 1, { disabled: page >= pages }));
                }
            }
            if (window.lucide) lucide.createIcons();
        }
        if (search) search.addEventListener('input', function () {
            var q = search.value.trim().toLowerCase();
            filtered = q ? all.filter(function (c) { return (c.getAttribute('data-search') || '').indexOf(q) !== -1; }) : all;
            page = 1; render();
        });
        render();
    })();

    // ── Kartu LO: pencarian + pagination dinamis (mirror PIC) ──
    (function () {
        var PER = 9;
        var grid = document.getElementById('loGrid');
        if (!grid) return;
        var all = Array.prototype.slice.call(grid.querySelectorAll('.lo-card'));
        var search = document.getElementById('loSearch');
        var info = document.getElementById('loInfo');
        var btns = document.getElementById('loPageBtns');
        var empty = document.getElementById('loEmpty');
        var page = 1, filtered = all;
        function makeBtn(label, target, o) {
            var b = document.createElement('button');
            b.type = 'button';
            b.innerHTML = label;
            b.className = 'min-w-[34px] h-[34px] px-2.5 rounded-lg text-sm font-semibold transition-colors '
                + (o.active ? 'bg-apkasi-heading text-white' : 'bg-white border border-apkasi-leaf text-apkasi-body hover:border-apkasi-accent')
                + (o.disabled ? ' opacity-40 pointer-events-none' : '');
            if (!o.disabled && !o.active) b.addEventListener('click', function () { page = target; render(); });
            return b;
        }
        function render() {
            var total = filtered.length;
            var pages = Math.max(1, Math.ceil(total / PER));
            if (page > pages) page = pages;
            if (page < 1) page = 1;
            all.forEach(function (c) { c.style.display = 'none'; });
            var start = (page - 1) * PER;
            var slice = filtered.slice(start, start + PER);
            slice.forEach(function (c) { c.style.display = ''; });
            if (info) info.textContent = total ? ('Menampilkan ' + (start + 1) + '–' + (start + slice.length) + ' dari ' + total + ' provinsi') : '';
            if (empty) empty.classList.toggle('hidden', total > 0);
            if (btns) {
                btns.innerHTML = '';
                if (pages > 1) {
                    btns.appendChild(makeBtn('‹', page - 1, { disabled: page <= 1 }));
                    for (var i = 1; i <= pages; i++) btns.appendChild(makeBtn(String(i), i, { active: i === page }));
                    btns.appendChild(makeBtn('›', page + 1, { disabled: page >= pages }));
                }
            }
            if (window.lucide) lucide.createIcons();
        }
        if (search) search.addEventListener('input', function () {
            var q = search.value.trim().toLowerCase();
            filtered = q ? all.filter(function (c) { return (c.getAttribute('data-search') || '').indexOf(q) !== -1; }) : all;
            page = 1; render();
        });
        render();
    })();

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
        rentalMap.easeTo({ center: ll, duration: 600 });   // hanya GESER (pan), zoom dipertahankan
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

    // ── Klik kartu rental -> arahkan (fly) & zoom ke titiknya di peta ──
    document.querySelectorAll('[data-rental-id]').forEach(function (card) {
        var p = RENTALS.find(function (x) { return String(x.id) === String(card.getAttribute('data-rental-id')); });
        if (!p || !p.lat || !p.lng) return;                 // tanpa koordinat -> tak bisa diarahkan
        card.classList.add('cursor-pointer');
        card.title = 'Lihat lokasi di peta';
        card.addEventListener('click', function (e) {
            if (e.target.closest('a, button')) return;      // jangan ganggu tombol WA/Telepon/Rute
            initRentalMap();
            var ll = [Number(p.lng), Number(p.lat)];
            var mapEl = document.getElementById('rentalMap');
            if (mapEl) {
                var rect = mapEl.getBoundingClientRect();
                // JANGAN scroll halaman kalau peta masih terlihat -> cukup geser petanya saja.
                // Scroll HANYA bila peta benar-benar di luar layar (mis. lagi jauh di bawah).
                if (rect.bottom < 40 || rect.top > window.innerHeight - 40) {
                    window.scrollTo({ top: window.pageYOffset + rect.top - 96, behavior: 'smooth' });
                }
            }
            if (rentalMap) rentalOpen(p, ll);
            else setTimeout(function () { if (rentalMap) rentalOpen(p, ll); }, 450);
        });
    });

    // ── Modal Detail Rental (daftar mobil + kontak) ──
    function rentalDetailHTML(r) {
        var dic = r.logo ? '<div class="w-11 h-11 rounded-xl bg-white border border-apkasi-leaf flex items-center justify-center shrink-0 overflow-hidden"><img src="' + r.logo + '" alt="" class="w-full h-full object-contain p-0.5"></div>' : '<div class="w-11 h-11 rounded-xl bg-apkasi-heading/10 flex items-center justify-center shrink-0"><i data-lucide="car" class="w-5 h-5 text-apkasi-heading"></i></div>';
        var h = '<div class="flex items-center gap-3 mb-3">' + dic + '<h3 class="font-bold text-apkasi-dark text-lg leading-snug">' + r.nama + '</h3></div>';
        if (r.alamat) h += '<p class="flex items-start gap-2 text-sm text-apkasi-body leading-relaxed mb-4"><i data-lucide="map-pin" class="w-4 h-4 text-apkasi-body/50 mt-0.5 shrink-0"></i> ' + r.alamat + '</p>';
        if (r.mobil && r.mobil.length) {
            h += '<div class="text-xs font-bold uppercase tracking-wider text-apkasi-body/70 mb-2">Armada Mobil (' + r.mobil.length + ' jenis)</div><div class="flex flex-col gap-1.5 mb-4">';
            r.mobil.forEach(function (m) {
                var u = (m.unit != null && m.unit !== '') ? '<span class="font-bold text-apkasi-dark">' + m.unit + ' unit</span>' : '<span class="text-[11px] font-semibold text-apkasi-heading bg-apkasi-leaf/60 px-2 py-0.5 rounded-full">tersedia</span>';
                h += '<div class="flex items-center justify-between text-sm border-b border-apkasi-leaf/60 pb-1.5"><span class="text-apkasi-body">' + m.nama + '</span>' + u + '</div>';
            });
            h += '</div>';
        }
        if (r.kontak && r.kontak.length) {
            h += '<div class="text-xs font-bold uppercase tracking-wider text-apkasi-body/70 mb-2">Contact Person</div><div class="flex flex-col gap-1.5 mb-4">';
            r.kontak.forEach(function (k) {
                h += '<div class="flex items-center justify-between gap-2 text-sm border-b border-apkasi-leaf/60 pb-1.5"><span class="text-apkasi-dark"><span class="font-semibold">' + k.nama + '</span> <span class="text-apkasi-body/70">' + (k.no_hp || '') + '</span></span><a href="' + rentalWa(k.no_hp) + '" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-[11px] font-semibold px-2.5 py-1 rounded-full bg-apkasi-heading text-white hover:bg-apkasi-cta transition shrink-0"><i data-lucide="message-circle" class="w-3 h-3"></i> WA</a></div>';
            });
            h += '</div>';
        }
        h += '<div class="flex gap-2 mt-2">';
        if (r.kontak_wa) h += '<a href="' + rentalWa(r.kontak_wa) + '" target="_blank" rel="noopener" class="flex-1 inline-flex items-center justify-center gap-1.5 text-sm font-semibold px-4 py-2.5 rounded-full bg-apkasi-heading text-white hover:bg-apkasi-cta transition"><i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp</a>';
        if (r.maps_url || (r.lat && r.lng)) h += '<a href="' + rentalGmaps(r) + '" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-1.5 text-sm font-semibold px-4 py-2.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-leaf/50 transition"><i data-lucide="navigation" class="w-4 h-4"></i> Rute</a>';
        h += '</div>';
        return h;
    }
    var rdModalEl = document.getElementById('rentalDetailModal');
    function openRentalDetail(id) {
        var r = RENTALS.find(function (x) { return String(x.id) === String(id); });
        if (!r || !rdModalEl) return;
        document.getElementById('rentalDetailBody').innerHTML = rentalDetailHTML(r);
        rdModalEl.classList.remove('hidden'); rdModalEl.classList.add('flex');
        document.body.style.overflow = 'hidden';
        if (window.lucide) lucide.createIcons();
    }
    function closeRentalDetail() { if (rdModalEl) { rdModalEl.classList.add('hidden'); rdModalEl.classList.remove('flex'); document.body.style.overflow = ''; } }
    if (rdModalEl) rdModalEl.querySelectorAll('[data-rd-close]').forEach(function (el) { el.addEventListener('click', closeRentalDetail); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && rdModalEl && !rdModalEl.classList.contains('hidden')) closeRentalDetail(); });
    document.querySelectorAll('[data-rental-detail]').forEach(function (b) {
        b.addEventListener('click', function (e) { e.stopPropagation(); openRentalDetail(b.getAttribute('data-rental-detail')); });
    });

    // ── Modal Kontak Rental: tombol WhatsApp -> daftar nomor (pilih untuk hubungi) ──
    function rentalKontakHTML(r) {
        var list = (r.kontak && r.kontak.length) ? r.kontak : (r.kontak_wa ? [{ nama: null, no_hp: r.kontak_wa }] : []);
        var kic = r.logo ? '<div class="w-11 h-11 rounded-xl bg-white border border-apkasi-leaf flex items-center justify-center shrink-0 overflow-hidden"><img src="' + r.logo + '" alt="" class="w-full h-full object-contain p-0.5"></div>' : '<div class="w-11 h-11 rounded-xl bg-apkasi-heading/10 flex items-center justify-center shrink-0"><i data-lucide="message-circle" class="w-5 h-5 text-apkasi-heading"></i></div>';
        var h = '<div class="flex items-center gap-3 mb-4">' + kic + '<div class="min-w-0"><h3 class="font-bold text-apkasi-dark text-base leading-snug">' + r.nama + '</h3><p class="text-[11px] text-apkasi-body/60">Pilih nomor untuk dihubungi</p></div></div>';
        h += '<div class="flex flex-col gap-2">';
        list.forEach(function (k) {
            h += '<div class="flex items-center justify-between gap-2 border border-apkasi-leaf rounded-xl px-3 py-2.5">'
               + '<span class="text-sm min-w-0 truncate">' + (k.nama ? '<span class="font-semibold text-apkasi-dark">' + k.nama + '</span> ' : '') + '<span class="text-apkasi-body/70 tabular-nums">' + (k.no_hp || '') + '</span></span>'
               + '<div class="flex items-center gap-1.5 shrink-0">'
               + '<a href="' + rentalWa(k.no_hp) + '" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full bg-apkasi-heading text-white hover:bg-apkasi-cta transition"><i data-lucide="message-circle" class="w-3.5 h-3.5"></i> WA</a>'
               + '<a href="tel:' + String(k.no_hp || '').replace(/[^0-9+]/g, '') + '" title="Telepon" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-apkasi-gold/15 text-[#9a7d16] hover:bg-apkasi-gold hover:text-apkasi-dark transition"><i data-lucide="phone" class="w-3.5 h-3.5"></i></a>'
               + '</div></div>';
        });
        h += '</div>';
        return h;
    }
    var rkModalEl = document.getElementById('rentalKontakModal');
    function openRentalKontak(id) {
        var r = RENTALS.find(function (x) { return String(x.id) === String(id); });
        if (!r || !rkModalEl) return;
        document.getElementById('rentalKontakBody').innerHTML = rentalKontakHTML(r);
        rkModalEl.classList.remove('hidden'); rkModalEl.classList.add('flex');
        document.body.style.overflow = 'hidden';
        if (window.lucide) lucide.createIcons();
    }
    function closeRentalKontak() { if (rkModalEl) { rkModalEl.classList.add('hidden'); rkModalEl.classList.remove('flex'); document.body.style.overflow = ''; } }
    if (rkModalEl) rkModalEl.querySelectorAll('[data-rk-close]').forEach(function (el) { el.addEventListener('click', closeRentalKontak); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && rkModalEl && !rkModalEl.classList.contains('hidden')) closeRentalKontak(); });
    document.querySelectorAll('[data-rental-kontak]').forEach(function (b) {
        b.addEventListener('click', function (e) { e.stopPropagation(); openRentalKontak(b.getAttribute('data-rental-kontak')); });
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
            <button type="button" data-tab="lo" class="text-sm font-semibold px-5 py-2.5 rounded-full border bg-white border-apkasi-leaf text-apkasi-body hover:border-apkasi-accent transition-colors inline-flex items-center gap-2">
                <i data-lucide="map-pinned" class="w-4 h-4"></i> LO (Liaison Officer)
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

        {{-- Pencarian --}}
        <div class="mb-5 relative max-w-md">
            <i data-lucide="search" class="w-4 h-4 text-apkasi-body/50 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            <input type="text" id="picSearch" placeholder="Cari provinsi atau nama PIC..." autocomplete="off"
                   class="w-full pl-11 pr-4 py-2.5 rounded-full border border-apkasi-leaf bg-white text-sm text-apkasi-dark focus:outline-none focus:border-apkasi-accent focus:ring-2 focus:ring-apkasi-accent/20 transition">
        </div>

        {{-- Kartu PIC: 1 kartu = 1 PIC (grup per nomor HP), 6 kartu/halaman via pagination dinamis --}}
        @php $grouped = $pics->groupBy('no_hp'); @endphp
        <div id="picGrid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($grouped as $group)
                @php
                    $first = $group->first();
                    $provNames = $group->map(fn ($p) => optional($p->provinsi)->nama)->filter()->values();
                @endphp
                <div class="pic-card bg-white rounded-2xl border border-apkasi-leaf p-5 flex flex-col hover:shadow-lg hover:border-apkasi-accent/50 transition-all duration-300"
                     data-search="{{ strtolower($first->nama . ' ' . $provNames->implode(' ')) }}">
                    {{-- Nama PIC --}}
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-apkasi-heading/10 flex items-center justify-center shrink-0">
                            <i data-lucide="user-round" class="w-5 h-5 text-apkasi-heading"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-apkasi-dark text-[15px] leading-snug">{{ $first->nama }}</h3>
                            <p class="text-[11px] text-apkasi-body/60 mt-0.5">PIC pendamping &middot; {{ $provNames->count() }} provinsi</p>
                        </div>
                    </div>
                    {{-- List provinsi yang didampingi --}}
                    <div class="flex flex-wrap gap-1.5 mb-4 flex-1 content-start">
                        @foreach ($provNames as $prov)
                            <span class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-apkasi-leaf/50 text-apkasi-heading">
                                <i data-lucide="map-pin" class="w-3 h-3"></i> {{ $prov }}
                            </span>
                        @endforeach
                    </div>
                    {{-- No HP + Kontak --}}
                    @if ($first->no_hp)
                        <div class="flex items-center justify-between gap-2 pt-3 border-t border-apkasi-leaf">
                            <span class="tabular-nums text-sm font-semibold text-apkasi-dark">{{ $first->no_hp }}</span>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="{{ $wa($first->no_hp) }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full bg-apkasi-heading text-white hover:bg-apkasi-cta transition-colors">
                                    <i data-lucide="message-circle" class="w-3.5 h-3.5"></i> WA
                                </a>
                                <a href="{{ $tel($first->no_hp) }}" title="Telepon"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-apkasi-gold/15 text-[#9a7d16] hover:bg-apkasi-gold hover:text-apkasi-dark transition-colors">
                                    <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <p id="picEmpty" class="hidden text-center text-apkasi-body/60 text-sm py-10">PIC tidak ditemukan.</p>

        {{-- Pagination dinamis (6 kartu / halaman, tanpa reload) --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-6">
            <span id="picInfo" class="text-xs text-apkasi-body/70"></span>
            <div id="picPageBtns" class="flex items-center gap-1.5 flex-wrap"></div>
        </div>
        <!-- <p class="text-xs text-apkasi-body/60 mt-3">Catatan: DKI Jakarta belum tercantum PIC pada dokumen sumber.</p> -->
    </section>

    {{-- ═══════════ PANEL: LO TERBARU ═══════════ --}}
    <section data-panel="lo" class="hidden max-w-[1400px] mx-auto px-5 sm:px-8 pt-8 pb-16 sm:pb-20">
        <div class="mb-5">
            <h2 class="font-display text-2xl font-bold text-apkasi-dark leading-tight">Pembagian LO &amp; PIC per Provinsi</h2>
            <p class="text-apkasi-body text-sm mt-1">LO (Liaison Officer): penempatan kecamatan &amp; instansi pendamping tiap provinsi, beserta PIC-nya.</p>
        </div>

        <div class="mb-5 relative max-w-md">
            <i data-lucide="search" class="w-4 h-4 text-apkasi-body/50 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            <input type="text" id="loSearch" placeholder="Cari provinsi, kecamatan, instansi, atau PIC..." autocomplete="off"
                   class="w-full pl-11 pr-4 py-2.5 rounded-full border border-apkasi-leaf bg-white text-sm text-apkasi-dark focus:outline-none focus:border-apkasi-accent focus:ring-2 focus:ring-apkasi-accent/20 transition">
        </div>

        <div id="loGrid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($los as $lo)
                @php $prov = optional($lo->provinsi)->nama ?? '-'; @endphp
                <div class="lo-card bg-white rounded-2xl border border-apkasi-leaf p-5 flex flex-col hover:shadow-lg hover:border-apkasi-accent/50 transition-all duration-300"
                     data-search="{{ strtolower($prov . ' ' . $lo->lo_camat . ' ' . $lo->lo_jabatan_instansi . ' ' . $lo->nama) }}">
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-apkasi-gold/15 flex items-center justify-center shrink-0">
                            <i data-lucide="map-pinned" class="w-5 h-5 text-[#9a7d16]"></i>
                        </div>
                        <h3 class="font-bold text-apkasi-dark text-[15px] leading-snug">{{ $prov }}</h3>
                    </div>
                    <div class="space-y-2.5 text-sm flex-1">
                        @if ($lo->lo_camat)
                            <div class="flex items-start gap-2">
                                <i data-lucide="map-pin" class="w-4 h-4 text-apkasi-accent mt-0.5 shrink-0"></i>
                                <span class="font-semibold text-apkasi-dark">{{ $lo->lo_camat }}</span>
                            </div>
                        @endif
                        @if ($lo->lo_jabatan_instansi)
                            <div class="flex items-start gap-2">
                                <i data-lucide="building-2" class="w-4 h-4 text-apkasi-heading mt-0.5 shrink-0"></i>
                                <span class="text-apkasi-body">{{ $lo->lo_jabatan_instansi }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-between gap-2 pt-3 mt-3 border-t border-apkasi-leaf">
                        <div class="flex items-center gap-2 min-w-0">
                            <i data-lucide="user-round" class="w-4 h-4 text-apkasi-heading shrink-0"></i>
                            <span class="text-sm font-semibold text-apkasi-dark truncate">{{ $lo->nama }}</span>
                        </div>
                        @if ($lo->no_hp)
                            <a href="{{ $wa($lo->no_hp) }}" target="_blank" rel="noopener" title="WhatsApp {{ $lo->nama }}"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-apkasi-heading text-white hover:bg-apkasi-cta transition-colors shrink-0">
                                <i data-lucide="message-circle" class="w-3.5 h-3.5"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <p id="loEmpty" class="hidden text-center text-apkasi-body/60 text-sm py-10">Data LO tidak ditemukan.</p>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-6">
            <span id="loInfo" class="text-xs text-apkasi-body/70"></span>
            <div id="loPageBtns" class="flex items-center gap-1.5 flex-wrap"></div>
        </div>
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

        @if (!empty($rentalBanner2))
            <div class="rounded-2xl overflow-hidden border border-apkasi-leaf mb-6 shadow-sm">
                <img src="{{ \App\Support\Media::url($rentalBanner2) }}" alt="Rental Kendaraan APKASI 2026 — Naga Hitam Rentcar" loading="lazy" class="w-full h-auto block">
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
                <div data-rental-id="{{ $r->id }}" class="bg-white rounded-2xl border border-apkasi-leaf p-5 hover:shadow-lg transition-all duration-300 flex flex-col">
                    <div class="flex items-center gap-3 mb-3">
                        @if ($r->logo_url)
                            <div class="w-11 h-11 rounded-xl bg-white border border-apkasi-leaf flex items-center justify-center shrink-0 overflow-hidden">
                                <img src="{{ $r->logo_url }}" alt="{{ $r->nama }}" loading="lazy" class="w-full h-full object-contain p-0.5" />
                            </div>
                        @else
                            <div class="w-11 h-11 rounded-xl bg-apkasi-heading/10 flex items-center justify-center shrink-0">
                                <i data-lucide="car" class="w-5 h-5 text-apkasi-heading"></i>
                            </div>
                        @endif
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
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-apkasi-leaf/60 text-apkasi-heading px-2.5 py-1 rounded-full">{{ $m->nama_mobil }}@if (! is_null($m->jumlah_unit)) <span class="text-apkasi-body/70">×{{ $m->jumlah_unit }}</span>@endif</span>
                                @endforeach
                            </div>
                            @if ($totalUnit > 0)
                                <div class="text-xs font-semibold text-apkasi-heading"><i data-lucide="car" class="w-3.5 h-3.5 inline"></i> Total {{ $totalUnit }} unit tersedia</div>
                            @endif
                        @endif
                    </div>
                    <div class="flex items-center gap-2 mt-4">
                        <button type="button" data-rental-detail="{{ $r->id }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-leaf/50 transition-colors">
                            <i data-lucide="list" class="w-4 h-4"></i> Detail
                        </button>
                        @if ($r->kontak->count() || $r->kontak_wa)
                            <button type="button" data-rental-kontak="{{ $r->id }}" class="flex-1 inline-flex items-center justify-center gap-1.5 text-xs font-semibold px-3 py-2.5 rounded-full bg-apkasi-heading text-white hover:bg-apkasi-cta transition-colors">
                                <i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp
                            </button>
                        @endif
                        @if ($r->telepon)
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $r->telepon) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-leaf/50 transition-colors">
                                <i data-lucide="phone" class="w-4 h-4"></i> {{ $r->telepon }}
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-10 text-sm text-apkasi-body/70">Belum ada data rental.</div>
            @endforelse
        </div>
    </section>

    {{-- ═══════════ MODAL DETAIL RENTAL ═══════════ --}}
    <div id="rentalDetailModal" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-apkasi-dark/55 backdrop-blur-sm" data-rd-close></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md max-h-[88vh] overflow-y-auto">
            <button type="button" data-rd-close class="absolute top-3.5 right-3.5 z-10 w-9 h-9 inline-flex items-center justify-center rounded-full bg-white/90 border border-apkasi-leaf text-apkasi-body hover:bg-apkasi-heading hover:text-white shadow transition-colors"><i data-lucide="x" class="w-4 h-4"></i></button>
            <div id="rentalDetailBody" class="p-6"></div>
        </div>
    </div>

    {{-- ═══════════ MODAL KONTAK RENTAL (daftar nomor WhatsApp) ═══════════ --}}
    <div id="rentalKontakModal" class="fixed inset-0 z-[70] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-apkasi-dark/55 backdrop-blur-sm" data-rk-close></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-sm max-h-[88vh] overflow-y-auto">
            <button type="button" data-rk-close class="absolute top-3.5 right-3.5 z-10 w-9 h-9 inline-flex items-center justify-center rounded-full bg-white/90 border border-apkasi-leaf text-apkasi-body hover:bg-apkasi-heading hover:text-white shadow transition-colors"><i data-lucide="x" class="w-4 h-4"></i></button>
            <div id="rentalKontakBody" class="p-6"></div>
        </div>
    </div>

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
