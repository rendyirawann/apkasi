{{-- Footer terpusat (dipakai landing & halaman dalam: peta, panduan).
     Konten dikelola di /admin/landing -> tab Footer. Satu sumber data -> tidak akan beda lagi. --}}
@php
    $S      = $appSettings ?? [];
    $fLinks = $footerLinks ?? collect();
    $col1   = $fLinks->get('col1', collect());
    $col2   = $fLinks->get('col2', collect());
    $fBrand = $footerBrandLogos ?? collect();
    $fSide  = $footerSideLogos ?? collect();
    $portalUrl = $S['lp_footer_portal_url'] ?? 'https://portal.deliserdangkab.go.id/';
@endphp
<footer class="bg-apkasi-dark border-t-4 border-apkasi-gold">
    <div class="max-w-[1400px] mx-auto px-5 sm:px-8 py-10 sm:py-14">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 mb-10">
            {{-- Brand --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    @forelse ($fBrand as $logo)
                        <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" loading="lazy" decoding="async" class="h-10 brightness-0 invert" />
                    @empty
                        <img src="{{ asset('logos/apkasi-logo.png') }}" alt="APKASI" loading="lazy" decoding="async" class="h-10 brightness-0 invert" />
                        <img src="{{ asset('logos/hut-apkasi.png') }}" alt="HUT" loading="lazy" decoding="async" class="h-10 brightness-0 invert opacity-80" />
                    @endforelse
                </div>
                <p class="text-white/50 text-xs leading-relaxed max-w-xs">{{ $S['lp_footer_tagline'] ?? 'Asosiasi Pemerintah Kabupaten Seluruh Indonesia. Memperkuat otonomi daerah untuk Indonesia Maju.' }}</p>
            </div>

            {{-- Kolom 1 (default: Navigasi) --}}
            <div>
                <h4 class="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">{{ $S['lp_footer_col1_title'] ?? 'Navigasi' }}</h4>
                <div class="space-y-2.5">
                    @foreach ($col1 as $l)
                        <a href="{{ $l->url }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">{{ $l->label }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Kolom 2 (default: Informasi) --}}
            <div>
                <h4 class="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">{{ $S['lp_footer_col2_title'] ?? 'Informasi' }}</h4>
                <div class="space-y-2.5">
                    @foreach ($col2 as $l)
                        <a href="{{ $l->url }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">{{ $l->label }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Pemerintahan --}}
            <div>
                <h4 class="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">{{ $S['lp_footer_gov_title'] ?? 'Pemerintahan' }}</h4>
                <p class="text-sm text-white/50 leading-relaxed mb-3">{{ $S['lp_footer_sekretariat'] ?? 'Dinas Kominfo Kabupaten Deli Serdang, Sumatera Utara' }}</p>
                @if ($portalUrl)
                    <a href="{{ $portalUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-apkasi-gold hover:text-apkasi-goldlt transition-colors">
                        <i data-lucide="globe" class="w-3.5 h-3.5"></i> {{ $S['lp_footer_portal_label'] ?? 'Portal DS' }}
                    </a>
                @endif
            </div>
        </div>

        <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-[11px] text-white/30">{{ $S['lp_footer_copyright'] ?? '© 2026 Pemerintah Kabupaten Deli Serdang & APKASI. All rights reserved.' }}</p>
            <div class="flex items-center gap-2">
                @forelse ($fSide as $logo)
                    <img src="{{ $logo->gambar_url }}" alt="{{ $logo->alt }}" loading="lazy" decoding="async" class="h-7 opacity-50" />
                @empty
                    <img src="{{ asset('logos/logo-ds.png') }}" alt="Deli Serdang" loading="lazy" decoding="async" class="h-7 opacity-50" />
                    <img src="{{ asset('logos/aoe2026-trans.png') }}" alt="AOE 2026" loading="lazy" decoding="async" class="h-7 opacity-50" />
                @endforelse
            </div>
        </div>
    </div>
</footer>
