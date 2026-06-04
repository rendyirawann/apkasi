import { useEffect, useMemo, useRef, useState } from 'react';
import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';
import {
  MapPin, Search, Phone, Star, Navigation, ArrowLeft, Maximize2,
  Building2, Calendar, Plane, Globe, Loader2, MapPinned,
} from 'lucide-react';
import GlassOrbCursor from '../GlassOrbCursor';

const TOKEN = import.meta.env.VITE_MAPBOX_TOKEN as string | undefined;

type Place = {
  id: number;
  category: 'venue' | 'hotel' | string;
  name: string;
  address: string | null;
  description: string | null;
  phone: string | null;
  price_range: string | null;
  rating: number | null;
  image: string | null;
  lat: number;
  lng: number;
  maps_url: string | null;
};

type Cat = 'all' | 'venue' | 'hotel';

const DEFAULT_CENTER: [number, number] = [98.8645, 3.5503];

function gmapsUrl(p: Place) {
  return p.maps_url || `https://www.google.com/maps/search/?api=1&query=${p.lat},${p.lng}`;
}
function telHref(phone: string) {
  return 'tel:' + phone.replace(/[^0-9+]/g, '');
}

export default function PetaHotel() {
  const [places, setPlaces] = useState<Place[]>([]);
  const [center, setCenter] = useState<[number, number]>(DEFAULT_CENTER);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const [activeCat, setActiveCat] = useState<Cat>('all');
  const [query, setQuery] = useState('');
  const [selectedId, setSelectedId] = useState<number | null>(null);
  const [scrolled, setScrolled] = useState(false);

  const containerRef = useRef<HTMLDivElement>(null);
  const mapRef = useRef<mapboxgl.Map | null>(null);
  const markersRef = useRef<Record<number, mapboxgl.Marker>>({});
  const cardsRef = useRef<Record<number, HTMLDivElement | null>>({});
  const [mapReady, setMapReady] = useState(false);

  /* ── Navbar shrink ── */
  useEffect(() => {
    const fn = () => setScrolled(window.scrollY > 40);
    window.addEventListener('scroll', fn, { passive: true });
    return () => window.removeEventListener('scroll', fn);
  }, []);

  /* ── Fetch data dari Laravel ── */
  useEffect(() => {
    let alive = true;
    fetch('/api/places')
      .then((r) => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
      })
      .then((d) => {
        if (!alive) return;
        const list: Place[] = d.places ?? d ?? [];
        setPlaces(list);
        if (Array.isArray(d.center) && d.center.length === 2) setCenter(d.center);
      })
      .catch((e) => alive && setError(String(e?.message || e)))
      .finally(() => alive && setLoading(false));
    return () => { alive = false; };
  }, []);

  const venueCount = useMemo(() => places.filter((p) => p.category === 'venue').length, [places]);
  const hotelCount = useMemo(() => places.filter((p) => p.category === 'hotel').length, [places]);

  const visible = useMemo(() => {
    const q = query.trim().toLowerCase();
    return places.filter((p) => {
      const catOk = activeCat === 'all' || p.category === activeCat;
      const qOk = !q || p.name.toLowerCase().includes(q) || (p.address || '').toLowerCase().includes(q);
      return catOk && qOk;
    });
  }, [places, activeCat, query]);

  /* ── Init Mapbox setelah data siap ── */
  useEffect(() => {
    if (!TOKEN || !containerRef.current || places.length === 0 || mapRef.current) return;

    mapboxgl.accessToken = TOKEN;
    const map = new mapboxgl.Map({
      container: containerRef.current,
      style: 'mapbox://styles/mapbox/light-v11',
      center,
      zoom: 11,
      attributionControl: false,
    });
    mapRef.current = map;
    map.addControl(new mapboxgl.NavigationControl({ showCompass: false }), 'top-right');
    map.addControl(new mapboxgl.AttributionControl({ compact: true }));

    map.on('load', () => {
      places.forEach((p) => {
        const el = document.createElement('div');
        el.className = 'pin ' + (p.category === 'hotel' ? 'pin-hotel' : 'pin-venue');

        const popup = new mapboxgl.Popup({ offset: 26, closeButton: false }).setHTML(
          `<div class="pop-name">${p.name}</div>` +
          `<div class="pop-addr">${p.address || ''}</div>` +
          `<a class="pop-link" href="${gmapsUrl(p)}" target="_blank" rel="noopener">Buka di Google Maps &rarr;</a>`
        );

        const marker = new mapboxgl.Marker({ element: el, anchor: 'bottom' })
          .setLngLat([p.lng, p.lat])
          .setPopup(popup)
          .addTo(map);

        el.addEventListener('click', () => focusPlace(p.id, false));
        markersRef.current[p.id] = marker;
      });
      setMapReady(true);
      fitToBounds(map, places);
    });

    return () => {
      map.remove();
      mapRef.current = null;
      markersRef.current = {};
      setMapReady(false);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [places]);

  /* ── Sinkron visibilitas marker dgn filter/search ── */
  useEffect(() => {
    if (!mapReady) return;
    const ids = new Set(visible.map((p) => p.id));
    Object.entries(markersRef.current).forEach(([id, mk]) => {
      mk.getElement().style.display = ids.has(Number(id)) ? '' : 'none';
    });
  }, [visible, mapReady]);

  /* ── Fit ulang saat ganti kategori ── */
  useEffect(() => {
    if (!mapReady || !mapRef.current) return;
    fitToBounds(mapRef.current, visible);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [activeCat, mapReady]);

  /* ── Highlight pin aktif ── */
  useEffect(() => {
    Object.entries(markersRef.current).forEach(([id, mk]) => {
      mk.getElement().classList.toggle('active', Number(id) === selectedId);
    });
  }, [selectedId]);

  function fitToBounds(map: mapboxgl.Map, pts: Place[]) {
    if (!pts.length) return;
    if (pts.length === 1) {
      map.flyTo({ center: [pts[0].lng, pts[0].lat], zoom: 14, duration: 700 });
      return;
    }
    const b = new mapboxgl.LngLatBounds();
    pts.forEach((p) => b.extend([p.lng, p.lat]));
    map.fitBounds(b, { padding: 70, maxZoom: 15, duration: 700 });
  }

  function focusPlace(id: number, fromCard: boolean) {
    setSelectedId(id);
    const p = places.find((x) => x.id === id);
    const map = mapRef.current;
    if (p && map) {
      map.flyTo({ center: [p.lng, p.lat], zoom: 15, duration: 800 });
      const mk = markersRef.current[id];
      if (mk && !mk.getPopup()?.isOpen()) mk.togglePopup();
    }
    if (!fromCard) cardsRef.current[id]?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  const tabs: { key: Cat; label: string; count: number }[] = [
    { key: 'all', label: 'Semua', count: places.length },
    { key: 'venue', label: 'Lokasi Acara', count: venueCount },
    { key: 'hotel', label: 'Hotel', count: hotelCount },
  ];

  return (
    <div className="min-h-screen bg-apkasi-cream">
      <GlassOrbCursor />

      {/* Map-specific styles (elemen marker dibuat manual / pihak ketiga) */}
      <style>{`
        .pin {
          width: 26px; height: 26px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg);
          border: 2px solid #fff; box-shadow: 0 3px 8px rgba(0,0,0,.3); cursor: pointer;
          display: flex; align-items: center; justify-content: center; transition: transform .2s ease;
        }
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
      `}</style>

      {/* ═══════════ NAVBAR ═══════════ */}
      <nav className={`fixed top-0 left-0 right-0 z-50 transition-all duration-500 ${scrolled ? 'py-1.5 sm:py-2' : 'py-3 sm:py-4'}`}>
        <div className={`mx-auto flex items-center justify-between transition-all duration-500 ${
          scrolled
            ? 'max-w-[95%] xl:max-w-[90%] px-4 sm:px-6 bg-white shadow-lg border border-gray-100 rounded-2xl'
            : 'max-w-6xl mx-4 sm:mx-6 lg:mx-auto px-4 sm:px-6 bg-white/70 backdrop-blur-md rounded-full shadow-sm border border-white/60'
        } py-2`}>
          <a href="/" className="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
            <img src="/logos/logo-ds.png" alt="Deli Serdang" className="h-8 sm:h-9 w-auto object-contain" />
            <img src="/logos/apkasi-alt2.png" alt="APKASI" className="h-7 sm:h-8 w-auto object-contain" />
            <img src="/logos/aoe2026.png" alt="AOE 2026" className="hidden sm:block h-7 sm:h-8 w-auto object-contain" />
          </a>

          <div className="hidden lg:flex items-center gap-1 absolute left-1/2 -translate-x-1/2">
            <a href="/" className="text-sm px-4 py-2 rounded-full font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5 transition-colors">Beranda</a>
            <a href="/#agenda" className="text-sm px-4 py-2 rounded-full font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5 transition-colors">Agenda</a>
            <a href="/#poi" className="text-sm px-4 py-2 rounded-full font-medium text-apkasi-body hover:text-apkasi-dark hover:bg-apkasi-dark/5 transition-colors">Putri Otonomi</a>
            <span className="text-sm px-4 py-2 rounded-full font-semibold text-apkasi-dark bg-apkasi-dark/5">Peta & Hotel</span>
          </div>

          <div className="flex items-center gap-2 sm:gap-3 shrink-0">
            <a href="/" className="hidden md:inline-flex items-center gap-1.5 text-sm font-medium text-apkasi-body hover:text-apkasi-dark transition-colors">
              <ArrowLeft className="w-3.5 h-3.5" /> Beranda
            </a>
            <a href="/admin/login" className="hidden sm:inline-flex items-center gap-1.5 bg-apkasi-dark hover:bg-apkasi-hover text-white text-sm font-semibold px-5 py-2 rounded-full transition-colors">
              <Globe className="w-3.5 h-3.5" /> Portal DS
            </a>
          </div>
        </div>
      </nav>

      {/* ═══════════ HERO / PAGE HEADER ═══════════ */}
      <section className="relative overflow-hidden bg-gradient-to-br from-apkasi-dark via-[#223d2c] to-apkasi-cta pt-32 sm:pt-36 pb-16 sm:pb-20">
        <div className="absolute -right-20 -bottom-28 w-[380px] h-[380px] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(212,175,55,0.22), transparent 70%)' }} />
        <div className="absolute -left-24 -top-24 w-[320px] h-[320px] rounded-full pointer-events-none"
          style={{ background: 'radial-gradient(circle, rgba(255,255,255,0.06), transparent 70%)' }} />

        <div className="relative max-w-6xl mx-auto px-5 sm:px-8">
          <div className="text-xs text-white/55 mb-4">
            <a href="/" className="hover:text-apkasi-goldlt transition-colors">Beranda</a>
            <span className="mx-2">/</span> Peta Lokasi &amp; Hotel
          </div>

          <span className="inline-flex items-center gap-2 bg-white/10 border border-white/15 text-white text-xs font-semibold px-4 py-1.5 rounded-full backdrop-blur-sm mb-4">
            <MapPin className="w-3.5 h-3.5 text-apkasi-goldlt" /> Kabupaten Deli Serdang, Sumatera Utara
          </span>

          <h1 className="font-display font-bold text-white text-3xl sm:text-4xl md:text-[3rem] leading-tight tracking-tight">
            Peta Lokasi <span className="text-apkasi-accent">&amp; Hotel</span>
          </h1>
          <p className="mt-3 text-white/75 text-sm sm:text-base leading-relaxed max-w-2xl">
            Temukan lokasi venue rangkaian acara dan rekomendasi penginapan terdekat untuk delegasi.
            Klik kartu untuk menyorot titik pada peta, atau buka langsung ke Google Maps untuk navigasi.
          </p>

          {/* Stat pills */}
          <div className="mt-7 flex flex-wrap gap-3">
            {[
              { icon: MapPin, num: venueCount || '—', label: 'Lokasi Acara' },
              { icon: Building2, num: hotelCount || '—', label: 'Hotel Rekomendasi' },
              { icon: Calendar, num: '1–3 Juli', label: 'Rangkaian Acara 2026' },
              { icon: Plane, num: "± 20–30'", label: 'Dari Bandara Kualanamu' },
            ].map((s, i) => (
              <div key={i} className="flex items-center gap-3 bg-white/8 border border-white/15 rounded-2xl px-4 py-3 backdrop-blur-sm">
                <div className="w-10 h-10 rounded-xl bg-apkasi-gold/15 flex items-center justify-center shrink-0">
                  <s.icon className="w-5 h-5 text-apkasi-gold" />
                </div>
                <div>
                  <div className="font-display font-extrabold text-white text-base leading-none">{s.num}</div>
                  <div className="text-[11px] text-white/65 mt-1">{s.label}</div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ═══════════ TOOLBAR ═══════════ */}
      <div className="max-w-6xl mx-auto px-5 sm:px-8 -mt-9 relative z-10">
        <div className="bg-white border border-apkasi-leaf rounded-2xl shadow-[0_18px_40px_rgba(43,84,58,0.08)] p-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
          <div className="relative flex-1 min-w-0">
            <Search className="w-4 h-4 text-apkasi-body/50 absolute left-4 top-1/2 -translate-y-1/2" />
            <input
              type="text"
              value={query}
              onChange={(e) => setQuery(e.target.value)}
              placeholder="Cari nama lokasi atau hotel..."
              className="w-full bg-apkasi-leaf/30 border border-apkasi-leaf rounded-full pl-11 pr-4 py-2.5 text-sm text-apkasi-dark placeholder:text-apkasi-body/50 focus:outline-none focus:border-apkasi-accent focus:bg-white transition-colors"
            />
          </div>
          <div className="flex gap-2 flex-wrap">
            {tabs.map((t) => (
              <button
                key={t.key}
                onClick={() => setActiveCat(t.key)}
                className={`text-sm font-semibold px-4 py-2.5 rounded-full border transition-colors ${
                  activeCat === t.key
                    ? 'bg-apkasi-heading border-apkasi-heading text-white'
                    : 'bg-white border-apkasi-leaf text-apkasi-body hover:border-apkasi-accent'
                }`}
              >
                {t.label} <span className="opacity-60 font-medium ml-0.5">{t.count}</span>
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* ═══════════ MAP + LIST ═══════════ */}
      <section className="max-w-6xl mx-auto px-5 sm:px-8 pt-8 pb-16 sm:pb-20">
        <div className="grid lg:grid-cols-12 gap-6">
          {/* List */}
          <div className="lg:col-span-5 order-2 lg:order-1">
            <div className="flex items-baseline justify-between mb-4">
              <h2 className="font-display text-xl font-bold text-apkasi-dark">Daftar Tempat</h2>
              <span className="text-xs font-semibold text-apkasi-body">
                {loading ? 'Memuat…' : `Menampilkan ${visible.length} tempat`}
              </span>
            </div>

            <div className="place-scroll flex flex-col gap-3.5 lg:max-h-[74vh] lg:overflow-y-auto lg:pr-1.5">
              {loading && (
                <div className="flex items-center justify-center gap-2 text-apkasi-body py-16 text-sm">
                  <Loader2 className="w-4 h-4 animate-spin" /> Memuat data lokasi…
                </div>
              )}
              {error && !loading && (
                <div className="text-center py-12 px-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-2xl">
                  Gagal memuat data: {error}
                  <div className="text-apkasi-body/70 mt-1 text-xs">Pastikan <code>php artisan serve</code> berjalan di port 2707.</div>
                </div>
              )}
              {!loading && !error && visible.length === 0 && (
                <div className="text-center py-12 text-sm text-apkasi-body/70">Tidak ada lokasi yang cocok dengan pencarian/filter ini.</div>
              )}

              {!loading && visible.map((p) => {
                const isHotel = p.category === 'hotel';
                const active = selectedId === p.id;
                return (
                  <div
                    key={p.id}
                    ref={(el) => { cardsRef.current[p.id] = el; }}
                    onClick={() => focusPlace(p.id, true)}
                    className={`group relative flex gap-3.5 p-3.5 rounded-2xl border bg-white cursor-pointer transition-all duration-300 overflow-hidden ${
                      active ? 'border-apkasi-accent shadow-[0_16px_32px_rgba(43,84,58,0.12)] -translate-y-0.5' : 'border-apkasi-leaf hover:border-apkasi-accent/50 hover:-translate-y-0.5 hover:shadow-lg'
                    }`}
                  >
                    <span className={`absolute left-0 top-0 bottom-0 w-1 transition-opacity ${active ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'} ${isHotel ? 'bg-apkasi-gold' : 'bg-apkasi-heading'}`} />
                    {p.image ? (
                      <img src={p.image} alt="" loading="lazy" className="w-24 h-24 rounded-xl object-cover shrink-0 bg-apkasi-leaf" />
                    ) : (
                      <div className="w-24 h-24 rounded-xl bg-apkasi-leaf flex items-center justify-center shrink-0 text-apkasi-accent">
                        {isHotel ? <Building2 className="w-7 h-7" /> : <MapPinned className="w-7 h-7" />}
                      </div>
                    )}
                    <div className="flex-1 min-w-0">
                      <span className={`inline-block text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full mb-1.5 ${isHotel ? 'bg-apkasi-gold/15 text-[#9a7d16]' : 'bg-apkasi-heading/10 text-apkasi-heading'}`}>
                        {isHotel ? 'Hotel' : 'Lokasi Acara'}
                      </span>
                      <h3 className="font-bold text-apkasi-dark text-[15px] leading-snug">{p.name}</h3>
                      {p.address && (
                        <p className="flex items-start gap-1.5 text-xs text-apkasi-body mt-1 leading-relaxed">
                          <MapPin className="w-3.5 h-3.5 text-apkasi-body/50 mt-0.5 shrink-0" /> {p.address}
                        </p>
                      )}
                      {p.description && <p className="text-xs text-apkasi-body/80 mt-1.5 leading-relaxed line-clamp-2">{p.description}</p>}
                      <div className="flex items-center gap-3 mt-2 text-xs text-apkasi-body flex-wrap">
                        {p.rating ? <span className="flex items-center gap-1 font-bold text-[#b9931f]"><Star className="w-3.5 h-3.5 fill-current" /> {p.rating}</span> : null}
                        {p.price_range && <span className="font-semibold">{p.price_range}</span>}
                      </div>
                      <div className="flex items-center gap-2 mt-3 flex-wrap">
                        <button
                          onClick={(e) => { e.stopPropagation(); focusPlace(p.id, true); }}
                          className="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-heading hover:text-white hover:border-apkasi-heading transition-colors"
                        >
                          <MapPin className="w-3.5 h-3.5" /> Lihat di Peta
                        </button>
                        <a
                          href={gmapsUrl(p)} target="_blank" rel="noopener" onClick={(e) => e.stopPropagation()}
                          className="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-gold hover:text-apkasi-dark hover:border-apkasi-gold transition-colors"
                        >
                          <Navigation className="w-3.5 h-3.5" /> Rute
                        </a>
                        {isHotel && p.phone && (
                          <a
                            href={telHref(p.phone)} onClick={(e) => e.stopPropagation()}
                            className="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full border border-apkasi-leaf text-apkasi-heading hover:bg-apkasi-accent hover:text-white hover:border-apkasi-accent transition-colors"
                          >
                            <Phone className="w-3.5 h-3.5" /> Telepon
                          </a>
                        )}
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>

          {/* Map */}
          <div className="lg:col-span-7 order-1 lg:order-2">
            <div className="relative rounded-3xl overflow-hidden border border-apkasi-leaf shadow-[0_20px_50px_rgba(43,84,58,0.10)] lg:sticky lg:top-24">
              <div ref={containerRef} className="w-full h-[56vh] lg:h-[78vh] min-h-[360px] bg-apkasi-leaf/40" />

              {!TOKEN && (
                <div className="absolute inset-0 flex flex-col items-center justify-center text-center gap-2 p-8 text-apkasi-body">
                  <MapPin className="w-10 h-10 text-apkasi-accent/50" />
                  <p><strong>Peta belum aktif.</strong><br />Setel <code>VITE_MAPBOX_TOKEN</code> di <code>frontend/.env</code>.</p>
                </div>
              )}

              {TOKEN && (
                <button
                  onClick={() => mapRef.current && fitToBounds(mapRef.current, visible)}
                  className="absolute top-3.5 left-3.5 z-[5] inline-flex items-center gap-1.5 bg-white/92 backdrop-blur text-apkasi-heading text-xs font-bold px-3.5 py-2 rounded-full shadow-md hover:bg-apkasi-heading hover:text-white transition-colors"
                >
                  <Maximize2 className="w-3.5 h-3.5" /> Tampilkan semua
                </button>
              )}

              <div className="absolute bottom-3.5 left-3.5 z-[5] bg-white/92 backdrop-blur rounded-xl px-3.5 py-2.5 shadow-md text-xs">
                <div className="flex items-center gap-2 font-semibold text-apkasi-body my-0.5"><span className="w-3 h-3 rounded-full bg-apkasi-heading inline-block" /> Lokasi Acara / Venue</div>
                <div className="flex items-center gap-2 font-semibold text-apkasi-body my-0.5"><span className="w-3 h-3 rounded-full bg-apkasi-gold inline-block" /> Hotel &amp; Penginapan</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ═══════════ FOOTER ═══════════ */}
      <footer className="bg-apkasi-dark border-t-4 border-apkasi-gold">
        <div className="max-w-6xl mx-auto px-5 sm:px-8 py-10 text-center">
          <div className="flex items-center justify-center gap-4 mb-5">
            <img src="/logos/apkasi-logo.png" alt="APKASI" className="h-10 brightness-0 invert" />
            <img src="/logos/logo-ds.png" alt="Deli Serdang" className="h-10" />
          </div>
          <h5 className="font-display text-white font-bold mb-1.5">HUT Ke-26 APKASI &amp; HUT Ke-80 Kabupaten Deli Serdang</h5>
          <p className="text-white/50 text-xs mb-5">Sekretariat APKASI &amp; Dinas Kominfo Kabupaten Deli Serdang, Sumatera Utara.</p>
          <div className="border-t border-white/10 pt-5">
            <p className="text-[11px] text-white/30">&copy; 2026 Pemerintah Kabupaten Deli Serdang &amp; APKASI. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  );
}
