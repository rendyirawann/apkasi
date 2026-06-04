import { useEffect, useState } from 'react';

/*
 *  PageLoader.tsx
 *  Loader ringkas untuk perpindahan ke halaman dalam (agenda, wisata, hotel, rental).
 *  Beda dari SplashLoader: singkat, tanpa tile-burst, cuma logo + ring + fade.
 *
 *  Pakai dengan React Router:
 *    <PageLoader key={location.pathname} />   // remount tiap ganti route
 *  Atau hard navigation (Blade per halaman): cukup render sekali, jalan saat load.
 */
import hut from './assets/logo_hut26.webp';

type Props = {
  /** durasi minimal tampil (ms) */
  minDuration?: number;
  /** tunggu window 'load' juga (untuk navigasi non-SPA) */
  waitForLoad?: boolean;
  onDone?: () => void;
};

export default function PageLoader({ minDuration = 600, waitForLoad = false, onDone }: Props) {
  const [phase, setPhase] = useState<'in' | 'out' | 'gone'>('in');

  useEffect(() => {
    const start = performance.now();
    let loaded = !waitForLoad || document.readyState === 'complete';
    const onLoad = () => { loaded = true; };
    if (waitForLoad && !loaded) window.addEventListener('load', onLoad);

    let raf = 0;
    const tick = () => {
      if (performance.now() - start >= minDuration && loaded) {
        setPhase('out');
        setTimeout(() => { onDone?.(); setPhase('gone'); }, 320);
        return;
      }
      raf = requestAnimationFrame(tick);
    };
    raf = requestAnimationFrame(tick);

    return () => {
      cancelAnimationFrame(raf);
      window.removeEventListener('load', onLoad);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  if (phase === 'gone') return null;

  return (
    <div className={`apk-ploader ${phase}`} aria-hidden="true">
      <style>{CSS}</style>
      <div className="badge">
        <div className="ring" />
        <img src={hut} alt="" />
      </div>
      <div className="lbl">Memuat</div>
    </div>
  );
}

const CSS = `
.apk-ploader{position:fixed;inset:0;z-index:99400;display:flex;align-items:center;justify-content:center;
  font-family:'Plus Jakarta Sans',sans-serif;opacity:0;transition:opacity .3s ease;
  background:radial-gradient(120% 110% at 50% 35%,#1a4a2e,#0b2014 60%,#06120b)}
.apk-ploader.in{opacity:1}
.apk-ploader.out{opacity:0}
.apk-ploader .badge{position:relative;width:104px;height:104px;display:flex;align-items:center;justify-content:center;
  transform:scale(.85);opacity:0;transition:transform .35s cubic-bezier(.2,.8,.2,1),opacity .35s ease}
.apk-ploader.in .badge{transform:none;opacity:1}
.apk-ploader .ring{position:absolute;inset:0;border-radius:50%;animation:apk-plspin 1s linear infinite;
  background:conic-gradient(from 0deg,transparent 0 60%,#e1b23c 80%,#7fc88a 100%);
  -webkit-mask:radial-gradient(farthest-side,transparent calc(100% - 4px),#000 calc(100% - 3px));
          mask:radial-gradient(farthest-side,transparent calc(100% - 4px),#000 calc(100% - 3px))}
@keyframes apk-plspin{to{transform:rotate(360deg)}}
.apk-ploader .badge img{width:62px;height:auto;animation:apk-plpulse 1.6s ease-in-out infinite}
@keyframes apk-plpulse{0%,100%{transform:scale(1)}50%{transform:scale(1.06)}}
.apk-ploader .lbl{position:absolute;top:calc(50% + 78px);left:0;right:0;text-align:center;
  font-size:11px;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.55);font-weight:600}
@media(prefers-reduced-motion:reduce){.apk-ploader .ring,.apk-ploader .badge img{animation:none}}
`;
