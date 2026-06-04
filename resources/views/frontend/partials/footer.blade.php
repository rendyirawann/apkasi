{{-- Footer lengkap (gaya landing) untuk halaman dalam (peta, panduan) --}}
<footer class="bg-apkasi-dark border-t-4 border-apkasi-gold">
    <div class="max-w-[1400px] mx-auto px-5 sm:px-8 py-10 sm:py-14">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 mb-10">
            {{-- Brand --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <img src="{{ asset('logos/apkasi-logo.png') }}" alt="APKASI" loading="lazy" decoding="async" class="h-10 brightness-0 invert" />
                    <img src="{{ asset('logos/hut-apkasi.png') }}" alt="HUT" loading="lazy" decoding="async" class="h-10 brightness-0 invert opacity-80" />
                </div>
                <p class="text-white/50 text-xs leading-relaxed max-w-xs">Asosiasi Pemerintah Kabupaten Seluruh Indonesia. Memperkuat otonomi daerah untuk Indonesia Maju.</p>
            </div>

            {{-- Navigasi --}}
            <div>
                <h4 class="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">Navigasi</h4>
                <div class="space-y-2.5">
                    <a href="{{ route('home') }}#tentang" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Tentang</a>
                    <a href="{{ route('home') }}#agenda" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Agenda</a>
                    <a href="{{ route('home') }}#poi" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Putri Otonomi</a>
                    <a href="{{ route('guide') }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Panduan</a>
                </div>
            </div>

            {{-- Informasi --}}
            <div>
                <h4 class="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">Informasi</h4>
                <div class="space-y-2.5">
                    <a href="{{ route('peta-hotel') }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Peta Lokasi Acara</a>
                    <a href="{{ route('peta-hotel') }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Rekomendasi Hotel</a>
                    <a href="{{ route('peta-hotel') }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">Destinasi Wisata</a>
                    <a href="{{ route('guide') }}" class="block text-sm text-white/50 hover:text-white/90 transition-colors">PIC & Rental Mobil</a>
                </div>
            </div>

            {{-- Sekretariat --}}
            <div>
                <h4 class="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">Pemerintahan</h4>
                <p class="text-sm text-white/50 leading-relaxed mb-3">Dinas Kominfo Kabupaten Deli Serdang,<br />Sumatera Utara</p>
                <a href="https://portal.deliserdangkab.go.id/" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm font-semibold text-apkasi-gold hover:text-apkasi-goldlt transition-colors">
                    <i data-lucide="globe" class="w-3.5 h-3.5"></i> Portal DS
                </a>
            </div>
        </div>

        <div class="border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-[11px] text-white/30">&copy; 2026 Pemerintah Kabupaten Deli Serdang & APKASI. All rights reserved.</p>
            <div class="flex items-center gap-2">
                <img src="{{ asset('logos/logo-ds.png') }}" alt="Deli Serdang" loading="lazy" decoding="async" class="h-7 opacity-50" />
                <img src="{{ asset('logos/aoe2026-trans.png') }}" alt="AOE 2026" loading="lazy" decoding="async" class="h-7 opacity-50" />
            </div>
        </div>
    </div>
</footer>
