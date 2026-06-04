import { useState, useEffect, useRef } from 'react';
import {
  LogIn, Menu, X, MapPin, Calendar, ChevronRight, Users, Trophy,
  Sparkles, Building2, TreePine, Heart, Star, Clock, ArrowRight,
  ChevronDown, Phone, ExternalLink, Globe
} from 'lucide-react';
import GlassOrbCursor from './GlassOrbCursor';
import SplashLoader from './SplashLoader';

const EVENT_DATE = new Date('2026-07-01T19:00:00+07:00');

/* ─── Countdown Hook ─── */
function useCountdown(target: Date) {
  const calc = () => {
    const d = target.getTime() - Date.now();
    if (d <= 0) return { days: 0, hours: 0, mins: 0, secs: 0 };
    return {
      days: Math.floor(d / 864e5),
      hours: Math.floor((d / 36e5) % 24),
      mins: Math.floor((d / 6e4) % 60),
      secs: Math.floor((d / 1e3) % 60),
    };
  };
  const [r, setR] = useState(calc);
  useEffect(() => { const id = setInterval(() => setR(calc), 1000); return () => clearInterval(id); }, []);
  return r;
}

/* ─── Scroll-triggered visibility hook ─── */
function useInView(threshold = 0.15) {
  const ref = useRef<HTMLDivElement>(null);
  const [visible, setVisible] = useState(false);
  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    const obs = new IntersectionObserver(([e]) => { if (e.isIntersecting) setVisible(true); }, { threshold });
    obs.observe(el);
    return () => obs.disconnect();
  }, [threshold]);
  return { ref, visible };
}

/* ─── Small reusable components ─── */
function CdUnit({ value, label }: { value: number; label: string }) {
  return (
    <div className="flex flex-col items-center bg-white/10 backdrop-blur-sm border border-white/15 rounded-xl sm:rounded-2xl px-3 sm:px-5 py-2.5 sm:py-3.5 min-w-[60px] sm:min-w-[76px]">
      <span className="text-2xl sm:text-3xl md:text-4xl font-bold leading-none tabular-nums text-apkasi-gold" style={{ letterSpacing: '-0.03em' }}>
        {String(value).padStart(2, '0')}
      </span>
      <span className="mt-1 text-[9px] sm:text-[10px] font-semibold uppercase tracking-[0.12em] text-white/55">{label}</span>
    </div>
  );
}

function SectionBadge({ children }: { children: React.ReactNode }) {
  return (
    <span className="inline-flex items-center gap-1.5 bg-apkasi-heading/8 text-apkasi-heading text-xs sm:text-sm font-semibold tracking-wide uppercase px-4 py-1.5 rounded-full mb-4">
      {children}
    </span>
  );
}

/* ─── Agenda data ─── */
const AGENDA = [
  {
    day: 'Hari 1', date: 'Rabu, 1 Juli 2026', icon: Star,
    events: [
      { time: '19.00 – 22.00', title: 'Welcome Dinner & Syukuran HUT APKASI', place: 'Graha Bhineka', desc: 'Pemotongan tumpeng, santunan anak yatim, gala dinner, dan tarian selamat datang.' },
    ],
  },
  {
    day: 'Hari 2', date: 'Kamis, 2 Juli 2026', icon: Users,
    events: [
      { time: '09.00 – 12.00', title: 'Dialog Strategi Pembiayaan Alternatif Pembangunan Daerah', place: 'IKM Hall', desc: 'Narasumber dari Pemkab Sintang, Sumedang, akademisi, dan BUMN sektor pembiayaan.' },
      { time: '09.00 – 13.00', title: 'Women Program — UMKM & Stunting', place: 'IKM Hall', desc: 'Talkshow penguatan peran perempuan dalam pengembangan UMKM dan penurunan stunting.' },
      { time: '13.00 – 15.00', title: 'Forum Bisnis Daerah (FORBISDA)', place: 'IKM Hall', desc: 'Bersama KADIN, perwakilan Pemkab, pengusaha lokal, Gubernur DKI Jakarta, dan IBA.' },
      { time: '18.30 – 22.00', title: 'Malam Grand Final Putri Otonomi Indonesia 2026', place: 'Graha Bhineka', desc: 'Puncak penobatan duta otonomi daerah dari seluruh kabupaten di Indonesia.' },
    ],
  },
  {
    day: 'Hari 3', date: "Jum'at, 3 Juli 2026", icon: TreePine,
    events: [
      { time: '06.00 – 10.30', title: 'Fun Walk & Penanaman Pohon', place: 'Alun-Alun Deli Serdang', desc: 'Jalan santai bersama, penanaman pohon, pembagian doorprize, dan hiburan rakyat.' },
    ],
  },
];

/* ─── FAQ ─── */
const FAQS = [
  {
    q: 'Apa saja dresscode untuk Kepala Daerah selama acara?',
    a: 'Welcome Dinner: Batik khas Deli Serdang. Dialog & FORBISDA: Kemeja Putih APKASI. Malam Final POI: Batik Resmi APKASI. Fun Walk: Kaos, topi, dan gelang peserta dari APKASI.',
  },
  {
    q: 'Di mana dan kapan stempel SPPD / Surat Tugas bisa diproses?',
    a: 'Di Meja Registrasi Delegasi pada IKM Hall (2 Juli, 08.00–15.00 WIB) dan Graha Bhineka (1 Juli, 18.00–20.00 WIB). Pastikan membawa dokumen cetak Surat Tugas.',
  },
  {
    q: 'Bagaimana shuttle bus untuk delegasi daerah?',
    a: 'Panitia menyediakan bus shuttle dari hotel rekomendasi menuju venue acara (PP). Bagi yang menghendaki mobil privat, tersedia info rental di halaman Panduan Delegasi.',
  },
  {
    q: 'Bagaimana cara mendapatkan kaos Fun Walk?',
    a: 'Kaos, topi, dan gelang peserta (dengan nomor doorprize) disiapkan oleh APKASI dan dibagikan di loket pendaftaran Fun Walk, Alun-Alun Deli Serdang, pukul 06.00 WIB.',
  },
];

/* ═══════════════════════ MAIN APP ═══════════════════════ */
function App() {
  const [menuOpen, setMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const [openFaq, setOpenFaq] = useState<number | null>(null);
  const { days, hours, mins, secs } = useCountdown(EVENT_DATE);

  const leaders = useInView();
  const about = useInView();
  const agenda = useInView();
  const poi = useInView();
  const faq = useInView();

  useEffect(() => {
    document.body.style.overflow = menuOpen ? 'hidden' : '';
    return () => { document.body.style.overflow = ''; };
  }, [menuOpen]);

  useEffect(() => {
    const fn = () => setScrolled(window.scrollY > 40);
    window.addEventListener('scroll', fn, { passive: true });
    return () => window.removeEventListener('scroll', fn);
  }, []);

  const navLinks = [
    { href: '#tentang', label: 'Tentang' },
    { href: '#agenda',  label: 'Agenda' },
    { href: '#poi',     label: 'Putri Otonomi' },
    { href: '#faq',     label: 'Panduan' },
  ];

  return (
    <div className="min-h-screen bg-apkasi-cream">
      <SplashLoader oncePerSession={false} minDuration={3000} />
      <GlassOrbCursor />

      {/* ═══════════ NAVBAR ═══════════ */}
      <nav className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${scrolled ? 'py-1.5 sm:py-2' : 'py-3 sm:py-4'}`}>
        <div className={`mx-auto flex items-center justify-between transition-all duration-500 ${
          scrolled
            ? 'max-w-[95%] xl:max-w-[90%] mx-auto px-4 sm:px-6 bg-white shadow-lg border border-gray-100 rounded-2xl'
            : 'max-w-6xl mx-4 sm:mx-6 lg:mx-auto px-4 sm:px-6 bg-white/70 backdrop-blur-md rounded-full shadow-sm border border-white/60'
        } py-2`}>

          {/* Logo - left: DS + APKASI + AOE */}
          <div className="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
            <img src="/logos/logo-ds.png" alt="Deli Serdang" className="h-8 sm:h-9 w-auto object-contain" />
            <img src="/logos/apkasi-alt2.png" alt="APKASI" className="h-7 sm:h-8 w-auto object-contain" />
            <img src="/logos/aoe2026.png" alt="AOE 2026" className="hidden sm:block h-7 sm:h-8 w-auto object-contain" />
          </div>

          {/* Center nav */}
          <div className="hidden lg:flex items-center gap-1 absolute left-1/2 -translate-x-1/2">
            {navLinks.map((link, i) => (
              <a key={link.href} href={link.href}
                className={`text-sm px-4 py-2 rounded-full transition-colors duration-200 ${
                  i === 0 ? 'font-semibold text-apkasi-dark' : 'font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5'
                }`}>{link.label}</a>
            ))}
          </div>

          {/* Right */}
          <div className="flex items-center gap-2 sm:gap-3 shrink-0">
            <a href="/panduan" className="hidden md:inline-flex items-center gap-1.5 text-sm font-medium text-apkasi-body hover:text-apkasi-dark transition-colors">
              <MapPin className="w-3.5 h-3.5" /> Panduan
            </a>
            <a href="/admin/login"
              className="hidden sm:inline-flex items-center gap-1.5 bg-apkasi-dark hover:bg-apkasi-hover text-white text-sm font-semibold px-5 py-2 rounded-full transition-colors">
              <Globe className="w-3.5 h-3.5" /> Portal DS
            </a>
            <button onClick={() => setMenuOpen(v => !v)}
              className="lg:hidden relative flex items-center justify-center w-9 h-9 rounded-full bg-apkasi-dark/5 hover:bg-apkasi-dark/10 text-apkasi-dark transition-all duration-300"
              aria-label={menuOpen ? 'Tutup' : 'Menu'} aria-expanded={menuOpen}>
              <Menu className={`w-4 h-4 absolute transition-all duration-300 ${menuOpen ? 'opacity-0 rotate-90 scale-50' : 'opacity-100'}`} />
              <X className={`w-4 h-4 absolute transition-all duration-300 ${menuOpen ? 'opacity-100' : 'opacity-0 -rotate-90 scale-50'}`} />
            </button>
          </div>
        </div>
      </nav>

      {/* ═══════════ MOBILE OVERLAY ═══════════ */}
      <div className={`lg:hidden fixed inset-0 z-40 transition-opacity duration-300 ${menuOpen ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'}`}
        onClick={() => setMenuOpen(false)}>
        <div className="absolute inset-0 bg-apkasi-dark/40 backdrop-blur-sm" />
      </div>
      <div className={`lg:hidden fixed top-0 right-0 bottom-0 z-40 w-[82%] max-w-sm bg-white/95 backdrop-blur-xl shadow-2xl transition-transform duration-500 ease-[cubic-bezier(0.22,1,0.36,1)] ${menuOpen ? 'translate-x-0' : 'translate-x-full'}`}>
        <div className="flex flex-col h-full pt-20 px-6 pb-8">
          <div className="flex items-center gap-3 mb-8 pb-6 border-b border-apkasi-leaf">
            <img src="/logos/apkasi-logo.png" alt="APKASI" className="h-10" />
            <img src="/logos/hut-apkasi.png" alt="HUT" className="h-10" />
          </div>
          <div className="flex flex-col gap-1">
            {navLinks.map((link, i) => (
              <a key={link.href} href={link.href} onClick={() => setMenuOpen(false)}
                className={`text-xl font-semibold text-apkasi-dark py-3 border-b border-apkasi-dark/5 transition-all duration-500 ${menuOpen ? 'translate-x-0 opacity-100' : 'translate-x-8 opacity-0'}`}
                style={{ transitionDelay: menuOpen ? `${120 + i * 60}ms` : '0ms' }}>
                {link.label}
              </a>
            ))}
          </div>
          <div className={`mt-8 flex flex-col gap-3 transition-all duration-500 ${menuOpen ? 'translate-x-0 opacity-100' : 'translate-x-8 opacity-0'}`}
            style={{ transitionDelay: menuOpen ? '380ms' : '0ms' }}>
            <a href="/panduan" className="flex items-center gap-2 text-sm font-medium text-apkasi-body">
              <MapPin className="w-4 h-4" /> Panduan Delegasi
            </a>
            <a href="/admin/login" className="mt-2 bg-apkasi-dark hover:bg-apkasi-hover text-white text-sm font-semibold px-5 py-3 rounded-full transition-colors text-center">
              Masuk Portal DS
            </a>
          </div>
        </div>
      </div>

      {/* ═══════════ HERO ═══════════ */}
      <section id="home" className="relative w-full min-h-[100svh] overflow-hidden flex flex-col">
        {/* Background with Ken Burns */}
        <div className="absolute inset-0 w-full h-full">
          <img
            src="/logos/hero-bg.png"
            alt=""
            className="absolute inset-0 w-full h-full object-cover"
            style={{ animation: 'kenburns 25s ease-in-out infinite alternate' }}
          />
        </div>

        {/* Animated color splashes — red, green, blue, orange */}
        <div className="absolute inset-0 overflow-hidden pointer-events-none" style={{ mixBlendMode: 'overlay' }}>
          <div className="hero-splash splash-red" />
          <div className="hero-splash splash-blue" />
          <div className="hero-splash splash-green" />
          <div className="hero-splash splash-orange" />
        </div>
        <style>{`
          @keyframes kenburns {
            0% { transform: scale(1) translate(0, 0); }
            100% { transform: scale(1.1) translate(-1%, -0.8%); }
          }
          .hero-splash {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.45;
            will-change: transform, opacity;
          }
          .splash-red {
            width: 45vw; height: 45vw;
            background: radial-gradient(circle, rgba(220,30,30,0.8), rgba(180,10,10,0.3) 60%, transparent 80%);
            top: -10%; left: -10%;
            animation: splashFloat1 14s ease-in-out infinite alternate, splashPulse 8s ease-in-out infinite;
          }
          .splash-blue {
            width: 40vw; height: 40vw;
            background: radial-gradient(circle, rgba(0,100,255,0.8), rgba(0,150,255,0.3) 60%, transparent 80%);
            bottom: -8%; left: 15%;
            animation: splashFloat2 16s ease-in-out infinite alternate, splashPulse 10s ease-in-out infinite 2s;
          }
          .splash-green {
            width: 38vw; height: 38vw;
            background: radial-gradient(circle, rgba(20,180,60,0.8), rgba(0,150,50,0.3) 60%, transparent 80%);
            top: 5%; right: -8%;
            animation: splashFloat3 18s ease-in-out infinite alternate, splashPulse 9s ease-in-out infinite 4s;
          }
          .splash-orange {
            width: 35vw; height: 35vw;
            background: radial-gradient(circle, rgba(255,150,0,0.8), rgba(240,120,0,0.3) 60%, transparent 80%);
            bottom: -5%; right: -5%;
            animation: splashFloat4 15s ease-in-out infinite alternate, splashPulse 7s ease-in-out infinite 1s;
          }
          @keyframes splashFloat1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(12vw, 8vh) scale(1.15); }
          }
          @keyframes splashFloat2 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(8vw, -10vh) scale(1.1); }
          }
          @keyframes splashFloat3 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-10vw, 6vh) scale(1.2); }
          }
          @keyframes splashFloat4 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-6vw, -8vh) scale(1.1); }
          }
          @keyframes splashPulse {
            0%, 100% { opacity: 0.35; }
            50% { opacity: 0.55; }
          }
        `}</style>

        {/* gradient overlay */}
        <div className="absolute inset-0 bg-gradient-to-b from-apkasi-dark/40 via-apkasi-dark/15 to-apkasi-dark/70 pointer-events-none" />

        {/* Center content */}
        <div className="relative z-10 flex-1 flex flex-col items-center justify-center text-center px-5 sm:px-8 pt-28 sm:pt-32 pb-32 sm:pb-36">

          {/* ── Hero event logos ── */}
          <div className="flex items-center justify-center gap-6 sm:gap-10 md:gap-14 mb-8 sm:mb-10">
            <img
              src="/logos/hut-apkasi.png"
              alt="HUT APKASI Ke-26"
              className="h-28 sm:h-32 md:h-40 lg:h-48 w-auto object-contain drop-shadow-xl"
            />
            <img
              src="/logos/hutds80.png"
              alt="HUT Deli Serdang Ke-80"
              className="h-28 sm:h-32 md:h-40 lg:h-48 w-auto object-contain drop-shadow-xl"
            />
          </div>

          {/* Headline — two separate blocks for guaranteed spacing */}
          <div className="max-w-5xl">
            <h1 className="font-display font-bold text-white text-[1.65rem] sm:text-3xl md:text-[2.75rem] lg:text-[3.5rem] xl:text-[4rem] tracking-tight">
              Bersinergi Membangun Daerah
            </h1>
            <p className="font-display font-bold text-apkasi-accent text-[1.65rem] sm:text-3xl md:text-[2.75rem] lg:text-[3.5rem] xl:text-[4rem] tracking-tight mt-2 sm:mt-4 md:mt-5">
              Memperkuat Otonomi Indonesia
            </p>
          </div>


          <p className="mt-4 sm:mt-6 text-white/75 text-sm sm:text-base md:text-lg leading-relaxed max-w-lg font-normal">
            HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang
            <br className="hidden sm:block" />
            1 – 3 Juli 2026
          </p>

          {/* Location badge — above countdown */}
          <div className="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 rounded-full px-4 py-2 mt-5 sm:mt-6">
            <MapPin className="w-3 h-3 sm:w-3.5 sm:h-3.5 text-apkasi-goldlt" />
            <span className="text-white/90 text-[11px] sm:text-xs font-medium tracking-wide">Kabupaten Deli Serdang, Sumatera Utara</span>
          </div>

          {/* Countdown */}
          <div className="mt-7 sm:mt-9 flex items-center gap-2 sm:gap-3">
            <CdUnit value={days} label="Hari" />
            <span className="text-white/30 text-xl sm:text-2xl font-light">:</span>
            <CdUnit value={hours} label="Jam" />
            <span className="text-white/30 text-xl sm:text-2xl font-light">:</span>
            <CdUnit value={mins} label="Menit" />
            <span className="text-white/30 text-xl sm:text-2xl font-light">:</span>
            <CdUnit value={secs} label="Detik" />
          </div>

          {/* CTA buttons */}
          <div className="mt-8 sm:mt-10 flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
            <a href="#agenda" className="w-full sm:w-auto bg-apkasi-gold hover:bg-apkasi-goldlt text-apkasi-dark text-sm font-bold px-7 py-3 sm:py-3.5 rounded-full transition-colors shadow-lg text-center">
              Lihat Jadwal Agenda
            </a>
            <a href="/panduan" className="w-full sm:w-auto border-2 border-white/40 hover:border-white/70 text-white text-sm font-semibold px-7 py-3 sm:py-3.5 rounded-full transition-colors text-center">
              Panduan Delegasi
            </a>
          </div>
        </div>

        {/* Mascot floating animation */}
        <style>{`
          @keyframes mascotFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
          }
        `}</style>

        {/* Bottom bar */}
        <div className="absolute bottom-0 left-0 right-0 z-10 px-5 sm:px-8 md:px-10 pb-5 sm:pb-7 flex items-end justify-between">
          {/* Left */}
          <div className="max-w-xs hidden sm:block">
            <div className="flex items-center gap-2 text-white/85 mb-2">
              <Sparkles className="w-3.5 h-3.5" />
              <span className="text-xs font-semibold tracking-wide">Portal Resmi HUT APKASI 2026</span>
            </div>
            <p className="text-white/60 text-[11px] leading-relaxed">
              Informasi agenda, panduan delegasi, akomodasi, dan peta lokasi selama rangkaian kegiatan di Deli Serdang.
            </p>
          </div>
          {/* Right — date */}
          <div className="flex items-center gap-2 text-white/70 text-xs ml-auto">
            <Calendar className="w-3.5 h-3.5" />
            <span className="font-medium">1–3 Juli 2026</span>
            <span className="text-white/40">·</span>
            <span className="text-white/50">Deli Serdang</span>
          </div>
        </div>

        {/* Mascot — absolute bottom-right */}
        <img
          src="/logos/maskot.png"
          alt="Maskot APKASI"
          className="absolute z-20 right-4 sm:right-8 md:right-12 bottom-16 sm:bottom-20 h-28 sm:h-36 md:h-44 lg:h-52 w-auto object-contain drop-shadow-2xl"
          style={{ animation: 'mascotFloat 3.5s ease-in-out infinite' }}
        />
      </section>

      {/* ═══════════ LOGO PARTNERS ═══════════ */}
      <div className="py-8 sm:py-10 bg-white border-b border-apkasi-leaf">
        <div className="max-w-5xl mx-auto px-5 sm:px-8">
          <p className="text-[10px] sm:text-xs text-apkasi-body/60 font-semibold uppercase tracking-[0.15em] text-center mb-6">
            Kolaborasi Penyelenggara
          </p>
          <div className="flex items-center justify-center gap-6 sm:gap-10 md:gap-14 flex-wrap">
            {[
              { src: '/logos/apkasi-full.png', alt: 'APKASI', h: 'h-12 sm:h-14' },
              { src: '/logos/hut-apkasi.png', alt: 'HUT APKASI 2026', h: 'h-10 sm:h-12' },
              { src: '/logos/aoe2026.png', alt: 'AOE 2026', h: 'h-12 sm:h-14' },
              { src: 'https://upload.wikimedia.org/wikipedia/commons/2/2f/Logo_Deli_Serdang.png', alt: 'Kab. Deli Serdang', h: 'h-12 sm:h-14' },
            ].map(logo => (
              <div key={logo.alt} className="flex items-center justify-center px-2 py-1 opacity-80 hover:opacity-100 transition-opacity duration-300 grayscale hover:grayscale-0">
                <img src={logo.src} alt={logo.alt} className={`${logo.h} w-auto object-contain`} />
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* ═══════════ BUPATI & WAKIL BUPATI ═══════════ */}
      <section id="pimpinan" className="py-16 sm:py-20 md:py-28 bg-gradient-to-b from-white to-apkasi-cream">
        <div ref={leaders.ref} className={`max-w-5xl mx-auto px-5 sm:px-8 transition-all duration-700 ${leaders.visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}`}>
          <div className="text-center mb-10 sm:mb-14">
            <SectionBadge>Pimpinan Daerah Tuan Rumah</SectionBadge>
            <h2 className="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-apkasi-dark leading-tight mb-3">
              Kabupaten Deli Serdang
            </h2>
            <p className="text-apkasi-body text-sm sm:text-base max-w-md mx-auto">
              Menyambut seluruh delegasi APKASI dalam rangkaian peringatan HUT Ke-80 Kabupaten Deli Serdang.
            </p>
          </div>

          <div className="flex flex-col sm:flex-row items-center justify-center gap-8 sm:gap-12 md:gap-20">
            {/* Bupati */}
            <div className="group flex flex-col items-center text-center max-w-xs">
              <div className="relative mb-5">
                <div className="w-44 h-44 sm:w-52 sm:h-52 md:w-60 md:h-60 rounded-full overflow-hidden border-4 border-apkasi-gold/30 shadow-xl group-hover:border-apkasi-gold transition-colors duration-500">
                  <img src="/logos/bupati.png" alt="Bupati Deli Serdang" className="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" />
                </div>
                {/* Gold ribbon badge */}
                <div className="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-gradient-to-r from-apkasi-gold to-[#c5a028] text-apkasi-dark text-[10px] sm:text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full shadow-md whitespace-nowrap">
                  Bupati
                </div>
              </div>
              <h3 className="font-display text-lg sm:text-xl font-bold text-apkasi-dark mt-2">H. Ali Yusuf Siregar, S.Sos.</h3>
              <p className="text-apkasi-body text-xs sm:text-sm mt-1">Bupati Deli Serdang<br/>Periode 2024–2029</p>
              <div className="mt-3 w-10 h-0.5 bg-apkasi-gold/40 rounded-full group-hover:w-16 transition-all duration-500" />
            </div>

            {/* Divider — desktop only */}
            <div className="hidden sm:block w-px h-48 bg-gradient-to-b from-transparent via-apkasi-dark/15 to-transparent" />

            {/* Wakil Bupati */}
            <div className="group flex flex-col items-center text-center max-w-xs">
              <div className="relative mb-5">
                <div className="w-44 h-44 sm:w-52 sm:h-52 md:w-60 md:h-60 rounded-full overflow-hidden border-4 border-apkasi-gold/30 shadow-xl group-hover:border-apkasi-gold transition-colors duration-500">
                  <img src="/logos/wabup.png" alt="Wakil Bupati Deli Serdang" className="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" />
                </div>
                <div className="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-gradient-to-r from-apkasi-gold to-[#c5a028] text-apkasi-dark text-[10px] sm:text-xs font-bold uppercase tracking-wider px-4 py-1.5 rounded-full shadow-md whitespace-nowrap">
                  Wakil Bupati
                </div>
              </div>
              <h3 className="font-display text-lg sm:text-xl font-bold text-apkasi-dark mt-2">HM. Yusuf Siregar, S.E.</h3>
              <p className="text-apkasi-body text-xs sm:text-sm mt-1">Wakil Bupati Deli Serdang<br/>Periode 2024–2029</p>
              <div className="mt-3 w-10 h-0.5 bg-apkasi-gold/40 rounded-full group-hover:w-16 transition-all duration-500" />
            </div>
          </div>

          {/* Sambutan quote */}
          <div className="mt-12 sm:mt-16 max-w-2xl mx-auto text-center">
            <blockquote className="relative">
              <span className="absolute -top-4 -left-2 text-5xl sm:text-6xl text-apkasi-gold/20 font-serif leading-none">&ldquo;</span>
              <p className="text-apkasi-heading text-sm sm:text-base md:text-lg leading-relaxed italic font-medium px-6">
                Kami sangat bangga menjadi tuan rumah HUT APKASI Ke-26. Deli Serdang siap menyambut seluruh Bupati dan perwakilan kabupaten se-Indonesia untuk bersinergi membangun daerah.
              </p>
              <span className="absolute -bottom-6 -right-2 text-5xl sm:text-6xl text-apkasi-gold/20 font-serif leading-none rotate-180">&ldquo;</span>
            </blockquote>
            <p className="mt-6 text-xs sm:text-sm text-apkasi-body font-semibold">&mdash; Bupati Deli Serdang</p>
          </div>
        </div>
      </section>

      {/* ═══════════ ABOUT ═══════════ */}
      <section id="tentang" className="py-16 sm:py-20 md:py-28 bg-white">
        <div ref={about.ref} className={`max-w-6xl mx-auto px-5 sm:px-8 transition-all duration-700 ${about.visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}`}>
          <div className="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            {/* Text */}
            <div>
              <SectionBadge>Tentang Event</SectionBadge>
              <h2 className="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-apkasi-dark leading-tight mb-5">
                Dua Hari Jadi Besar,<br />Satu Tekad Bersinergi
              </h2>
              <p className="text-apkasi-body text-sm sm:text-base leading-relaxed mb-4">
                Rangkaian ini memperingati <strong>HUT APKASI (Asosiasi Pemerintah Kabupaten Seluruh Indonesia) Ke-26</strong> sekaligus <strong>HUT Kabupaten Deli Serdang Ke-80</strong>, dengan tema besar:
              </p>
              <blockquote className="border-l-4 border-apkasi-gold pl-4 sm:pl-5 my-5 sm:my-6">
                <p className="text-apkasi-heading font-semibold text-base sm:text-lg italic leading-relaxed">
                  "Penguatan Sinergi Antar Pemerintah Kabupaten Dalam Mendukung Pembangunan Daerah dan Otonomi Daerah."
                </p>
              </blockquote>
              <p className="text-apkasi-body text-sm sm:text-base leading-relaxed mb-6">
                Pemerintah Kabupaten Deli Serdang, Sumatera Utara, menyambut perwakilan dari seluruh pemerintah kabupaten di Indonesia untuk membahas strategi pembiayaan alternatif, kemandirian ekonomi lokal, dan peran perempuan dalam pemberantasan stunting.
              </p>

              {/* Stats */}
              <div className="grid grid-cols-2 sm:grid-cols-4 gap-3">
                {[
                  { icon: Users, label: '400+ Delegasi', sub: 'Bupati se-Indonesia' },
                  { icon: Building2, label: '3 Venue Utama', sub: 'Deli Serdang' },
                  { icon: Trophy, label: 'Grand Final POI', sub: 'Putri Otonomi 2026' },
                  { icon: Heart, label: 'Women Program', sub: 'UMKM & Stunting' },
                ].map(s => (
                  <div key={s.label} className="bg-apkasi-leaf/50 rounded-xl p-3 sm:p-4 text-center">
                    <s.icon className="w-5 h-5 text-apkasi-heading mx-auto mb-2" />
                    <p className="text-xs sm:text-sm font-bold text-apkasi-dark leading-tight">{s.label}</p>
                    <p className="text-[10px] sm:text-xs text-apkasi-body mt-0.5">{s.sub}</p>
                  </div>
                ))}
              </div>
            </div>

            {/* Image + mini info */}
            <div className="space-y-4">
              <div className="rounded-2xl sm:rounded-3xl overflow-hidden shadow-lg aspect-[4/3]">
                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=900&q=80"
                  alt="Seminar Otonomi" className="w-full h-full object-cover" />
              </div>
              <div className="grid grid-cols-2 gap-3">
                <div className="bg-apkasi-heading rounded-xl sm:rounded-2xl p-4 sm:p-5 text-white">
                  <Calendar className="w-5 h-5 text-apkasi-accent mb-2" />
                  <p className="text-sm font-bold">1 – 3 Juli 2026</p>
                  <p className="text-xs text-white/70 mt-0.5">3 hari rangkaian acara</p>
                </div>
                <div className="bg-apkasi-dark rounded-xl sm:rounded-2xl p-4 sm:p-5 text-white">
                  <MapPin className="w-5 h-5 text-apkasi-gold mb-2" />
                  <p className="text-sm font-bold">Deli Serdang</p>
                  <p className="text-xs text-white/70 mt-0.5">Sumatera Utara</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ═══════════ AGENDA ═══════════ */}
      <section id="agenda" className="py-16 sm:py-20 md:py-28 bg-gradient-to-b from-apkasi-cream to-apkasi-leaf/30">
        <div ref={agenda.ref} className={`max-w-6xl mx-auto px-5 sm:px-8 transition-all duration-700 ${agenda.visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}`}>
          <div className="text-center mb-12 sm:mb-16">
            <SectionBadge>Jadwal Acara</SectionBadge>
            <h2 className="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-apkasi-dark leading-tight mb-3">
              Rangkaian Acara 1 – 3 Juli 2026
            </h2>
            <p className="text-apkasi-body text-sm sm:text-base max-w-lg mx-auto">
              Informasi waktu, tempat, dan detail kegiatan selama rangkaian hari jadi berlangsung.
            </p>
          </div>

          <div className="space-y-6 sm:space-y-8">
            {AGENDA.map((day, di) => (
              <div key={di} className="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-apkasi-leaf overflow-hidden">
                {/* Day header */}
                <div className="flex items-center gap-3 sm:gap-4 px-5 sm:px-7 py-4 sm:py-5 bg-apkasi-dark text-white">
                  <div className="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                    <day.icon className="w-4 h-4 sm:w-5 sm:h-5 text-apkasi-gold" />
                  </div>
                  <div>
                    <p className="text-xs font-bold tracking-wide text-apkasi-accent uppercase">{day.day}</p>
                    <p className="text-sm sm:text-base font-semibold">{day.date}</p>
                  </div>
                </div>

                {/* Events */}
                <div className="divide-y divide-apkasi-leaf">
                  {day.events.map((ev, ei) => (
                    <div key={ei} className="flex flex-col sm:flex-row gap-3 sm:gap-6 px-5 sm:px-7 py-4 sm:py-5 hover:bg-apkasi-leaf/20 transition-colors">
                      <div className="flex items-center gap-2 sm:w-40 shrink-0">
                        <Clock className="w-3.5 h-3.5 text-apkasi-heading shrink-0" />
                        <span className="text-xs sm:text-sm font-semibold text-apkasi-heading whitespace-nowrap">{ev.time} WIB</span>
                      </div>
                      <div className="flex-1 min-w-0">
                        <h4 className="text-sm sm:text-base font-bold text-apkasi-dark leading-snug mb-1">{ev.title}</h4>
                        <p className="text-xs sm:text-sm text-apkasi-body leading-relaxed">{ev.desc}</p>
                        <div className="flex items-center gap-1.5 mt-2 text-apkasi-heading">
                          <MapPin className="w-3 h-3" />
                          <span className="text-xs font-semibold">{ev.place}</span>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>

          <div className="text-center mt-8 sm:mt-10">
            <a href="/panduan" className="inline-flex items-center gap-2 bg-apkasi-heading hover:bg-apkasi-cta text-white text-sm font-semibold px-7 py-3.5 rounded-full transition-colors shadow-md">
              Lihat Peta Lokasi & Hotel <ArrowRight className="w-4 h-4" />
            </a>
          </div>
        </div>
      </section>

      {/* ═══════════ POI ═══════════ */}
      <section id="poi" className="py-16 sm:py-20 md:py-28 bg-white">
        <div ref={poi.ref} className={`max-w-6xl mx-auto px-5 sm:px-8 transition-all duration-700 ${poi.visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}`}>
          <div className="bg-gradient-to-br from-apkasi-dark via-[#223d2c] to-apkasi-cta rounded-2xl sm:rounded-3xl overflow-hidden shadow-xl">
            <div className="grid lg:grid-cols-5 gap-0">
              {/* Text — 3 cols */}
              <div className="lg:col-span-3 p-7 sm:p-10 md:p-14 flex flex-col justify-center">
                <span className="inline-flex items-center gap-1.5 bg-apkasi-gold/20 text-apkasi-gold text-xs font-bold tracking-wider uppercase px-3 py-1 rounded-full w-fit mb-5">
                  <Trophy className="w-3 h-3" /> Special Event
                </span>
                <h2 className="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-white leading-tight mb-4">
                  Malam Grand Final<br />
                  <span className="text-apkasi-accent">Putri Otonomi Indonesia</span> 2026
                </h2>
                <p className="text-white/75 text-sm sm:text-base leading-relaxed mb-6 max-w-lg">
                  Ajang bergengsi pemilihan duta otonomi daerah dari seluruh kabupaten di Indonesia. Malam penobatan puncak dilaksanakan <strong className="text-white">Kamis, 2 Juli 2026</strong> di <strong className="text-white">Graha Bhineka</strong>.
                </p>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                  {[
                    { icon: Trophy, t: 'Penobatan Juara', s: 'Duta Otonomi Nasional' },
                    { icon: Users, t: '400+ Kepala Daerah', s: 'Bupati & Tokoh Nasional' },
                    { icon: Calendar, t: 'Kamis, 2 Juli 2026', s: '18.30 – 22.00 WIB' },
                    { icon: MapPin, t: 'Graha Bhineka', s: 'Deli Serdang' },
                  ].map(item => (
                    <div key={item.t} className="flex items-start gap-3">
                      <item.icon className="w-4 h-4 text-apkasi-gold mt-0.5 shrink-0" />
                      <div>
                        <p className="text-white text-sm font-semibold">{item.t}</p>
                        <p className="text-white/55 text-xs">{item.s}</p>
                      </div>
                    </div>
                  ))}
                </div>
              </div>

              {/* Image — 2 cols */}
              <div className="lg:col-span-2 relative min-h-[280px] sm:min-h-[340px]">
                <img src="https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=700&q=80"
                  alt="Putri Otonomi Indonesia 2026" className="w-full h-full object-cover" />
                <div className="absolute inset-0 bg-gradient-to-r from-apkasi-dark/50 via-transparent to-transparent lg:block hidden" />
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ═══════════ FAQ ═══════════ */}
      <section id="faq" className="py-16 sm:py-20 md:py-28 bg-gradient-to-b from-apkasi-cream to-apkasi-leaf/20">
        <div ref={faq.ref} className={`max-w-3xl mx-auto px-5 sm:px-8 transition-all duration-700 ${faq.visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'}`}>
          <div className="text-center mb-10 sm:mb-14">
            <SectionBadge>Panduan Delegasi</SectionBadge>
            <h2 className="font-display text-2xl sm:text-3xl md:text-4xl font-bold text-apkasi-dark leading-tight mb-3">
              Informasi Penting
            </h2>
            <p className="text-apkasi-body text-sm sm:text-base max-w-md mx-auto">
              Pertanyaan umum seputar registrasi, dresscode, transportasi, dan logistik selama event.
            </p>
          </div>

          <div className="space-y-3">
            {FAQS.map((f, i) => (
              <div key={i} className="bg-white rounded-xl sm:rounded-2xl border border-apkasi-leaf overflow-hidden transition-shadow hover:shadow-sm">
                <button onClick={() => setOpenFaq(openFaq === i ? null : i)}
                  className="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-4 sm:py-5 text-left">
                  <span className="text-sm sm:text-base font-semibold text-apkasi-dark leading-snug">{f.q}</span>
                  <ChevronDown className={`w-4 h-4 sm:w-5 sm:h-5 text-apkasi-body shrink-0 transition-transform duration-300 ${openFaq === i ? 'rotate-180' : ''}`} />
                </button>
                <div className={`transition-all duration-300 ease-in-out overflow-hidden ${openFaq === i ? 'max-h-60 opacity-100' : 'max-h-0 opacity-0'}`}>
                  <p className="px-5 sm:px-6 pb-5 text-sm text-apkasi-body leading-relaxed">
                    {f.a}
                  </p>
                </div>
              </div>
            ))}
          </div>

          {/* Extra CTA */}
          <div className="mt-10 sm:mt-12 bg-apkasi-dark rounded-2xl sm:rounded-3xl p-6 sm:p-8 text-center text-white">
            <h3 className="font-display text-lg sm:text-xl font-bold mb-2">Butuh Informasi Lebih Lengkap?</h3>
            <p className="text-white/65 text-sm mb-5 max-w-md mx-auto">
              Akses panduan lengkap peta lokasi event, rekomendasi hotel, destinasi wisata Deli Serdang, dan kontak rental mobil.
            </p>
            <a href="/panduan" className="inline-flex items-center gap-2 bg-apkasi-gold hover:bg-apkasi-goldlt text-apkasi-dark text-sm font-bold px-7 py-3 rounded-full transition-colors">
              Buka Panduan Lengkap <ExternalLink className="w-4 h-4" />
            </a>
          </div>
        </div>
      </section>

      {/* ═══════════ FOOTER ═══════════ */}
      <footer className="bg-apkasi-dark border-t-4 border-apkasi-gold">
        <div className="max-w-6xl mx-auto px-5 sm:px-8 py-10 sm:py-14">
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 mb-10">
            {/* Brand */}
            <div className="sm:col-span-2 lg:col-span-1">
              <div className="flex items-center gap-3 mb-4">
                <img src="/logos/apkasi-logo.png" alt="APKASI" className="h-10 brightness-0 invert" />
                <img src="/logos/hut-apkasi.png" alt="HUT" className="h-10 brightness-0 invert opacity-80" />
              </div>
              <p className="text-white/50 text-xs leading-relaxed max-w-xs">
                Asosiasi Pemerintah Kabupaten Seluruh Indonesia. Memperkuat otonomi daerah untuk Indonesia Maju.
              </p>
            </div>

            {/* Quick links */}
            <div>
              <h4 className="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">Navigasi</h4>
              <div className="space-y-2.5">
                {navLinks.map(l => (
                  <a key={l.href} href={l.href} className="block text-sm text-white/50 hover:text-white/90 transition-colors">{l.label}</a>
                ))}
              </div>
            </div>

            {/* Links */}
            <div>
              <h4 className="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">Panduan</h4>
              <div className="space-y-2.5">
                <a href="/panduan" className="block text-sm text-white/50 hover:text-white/90 transition-colors">Peta Lokasi Event</a>
                <a href="/panduan" className="block text-sm text-white/50 hover:text-white/90 transition-colors">Rekomendasi Hotel</a>
                <a href="/panduan" className="block text-sm text-white/50 hover:text-white/90 transition-colors">Destinasi Wisata</a>
                <a href="/panduan" className="block text-sm text-white/50 hover:text-white/90 transition-colors">Rental Mobil</a>
              </div>
            </div>

            {/* Contact */}
            <div>
              <h4 className="text-white/80 text-xs font-bold uppercase tracking-widest mb-4">Sekretariat</h4>
              <p className="text-sm text-white/50 leading-relaxed mb-3">
                Dinas Kominfo Kabupaten Deli Serdang,<br />Sumatera Utara
              </p>
              <a href="/admin/login" className="inline-flex items-center gap-1.5 text-sm font-semibold text-apkasi-gold hover:text-apkasi-goldlt transition-colors">
                <Globe className="w-3.5 h-3.5" /> Portal DS
              </a>
            </div>
          </div>

          <div className="border-t border-white/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p className="text-[11px] text-white/30">
              &copy; 2026 Pemerintah Kabupaten Deli Serdang & APKASI. All rights reserved.
            </p>
            <div className="flex items-center gap-2">
              <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Logo_Deli_Serdang.png" alt="Deli Serdang" className="h-7 opacity-50" />
              <img src="/logos/aoe2026-trans.png" alt="AOE 2026" className="h-7 opacity-50" />
            </div>
          </div>
        </div>
      </footer>
    </div>
  );
}

export default App;
