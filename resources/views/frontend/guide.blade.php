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
@endphp

@push('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <style>
        /* DataTables disesuaikan dengan tema apkasi */
        .dt-container { font-family: 'Plus Jakarta Sans', sans-serif; }
        table.dataTable thead th { background: #336443; color: #fff; font-weight: 700; font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; border: 0 !important; padding: 12px 14px; }
        table.dataTable tbody td { font-size: .85rem; color: #2d3a2a; border-color: #e8f0ea !important; padding: 11px 14px; vertical-align: middle; }
        table.dataTable tbody tr:hover { background: #f3f7f3; }
        table.dataTable { border-collapse: separate; }
        .dt-search input, .dt-length select { border: 1px solid #e8f0ea; border-radius: 9999px; padding: .45rem .9rem; font-size: .82rem; outline: none; background: #fff; }
        .dt-search input:focus { border-color: #85AB8B; }
        .dt-search label, .dt-length label, .dt-info { font-size: .8rem; color: #4b5b47; }
        .dt-paging .dt-paging-button { font-size: .8rem; padding: .3rem .7rem; margin: 0 2px; border-radius: 8px; }
        .dt-paging .dt-paging-button.current { background: #336443 !important; color: #fff !important; border: 0 !important; }
        .dt-paging .dt-paging-button:hover:not(.current) { background: #e8f0ea !important; border-color: #e8f0ea !important; color: #336443 !important; }
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
                order: [[0, 'asc']],
                columnDefs: [{ targets: [4], orderable: false, searchable: false }],
                language: {
                    search: 'Cari:', searchPlaceholder: 'provinsi / nama',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ PIC',
                    infoEmpty: 'Tidak ada data', infoFiltered: '(disaring dari _MAX_ total)',
                    zeroRecords: 'PIC tidak ditemukan',
                    paginate: { first: '«', previous: '‹', next: '›', last: '»' }
                }
            });
            picDT.on('draw', function () { if (window.lucide) lucide.createIcons(); });
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

        <div class="bg-white border border-apkasi-leaf rounded-2xl shadow-sm p-4 sm:p-5 overflow-x-auto">
            <table id="picTable" class="display w-full" style="width:100%">
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
        <p class="text-xs text-apkasi-body/60 mt-3">Catatan: DKI Jakarta belum tercantum PIC pada dokumen sumber.</p>
    </section>

    {{-- ═══════════ PANEL: RENTAL ═══════════ --}}
    <section data-panel="rental" class="hidden max-w-[1400px] mx-auto px-5 sm:px-8 pt-8 pb-16 sm:pb-20">
        <div class="mb-5">
            <h2 class="font-display text-2xl font-bold text-apkasi-dark leading-tight">Rental Kendaraan</h2>
            <p class="text-apkasi-body text-sm mt-1">Kontak penyedia sewa kendaraan untuk delegasi & rombongan.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($rentals as $r)
                <div class="bg-white rounded-2xl border border-apkasi-leaf p-5 hover:shadow-lg transition-all duration-300 flex flex-col">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-11 h-11 rounded-xl bg-apkasi-heading/10 flex items-center justify-center shrink-0">
                            <i data-lucide="car" class="w-5 h-5 text-apkasi-heading"></i>
                        </div>
                        <h3 class="font-bold text-apkasi-dark text-[15px] leading-snug">{{ $r['company'] }}</h3>
                    </div>
                    <p class="text-xs text-apkasi-body/80 leading-relaxed">{{ $r['services'] }}</p>
                    <p class="text-xs text-apkasi-body/60 italic leading-relaxed mt-2 flex-1">{{ $r['desc'] }}</p>
                    <div class="flex items-center gap-2 mt-4">
                        <a href="https://wa.me/{{ $r['whatsapp'] }}" target="_blank" rel="noopener" class="flex-1 inline-flex items-center justify-center gap-1.5 text-xs font-semibold px-3 py-2.5 rounded-full bg-apkasi-heading text-white hover:bg-apkasi-cta transition-colors">
                            <i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp
                        </a>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $r['phone_format']) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-leaf/50 transition-colors">
                            <i data-lucide="phone" class="w-4 h-4"></i> {{ $r['phone_format'] }}
                        </a>
                    </div>
                </div>
            @endforeach
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
    <footer class="bg-apkasi-dark border-t-4 border-apkasi-gold">
        <div class="max-w-[1400px] mx-auto px-5 sm:px-8 py-10 text-center">
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
