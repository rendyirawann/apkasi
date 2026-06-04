import { useEffect, useMemo, useRef, useState } from 'react';

/*
 *  SplashLoader – APKASI 2026 + HUT Deli Serdang
 *  - Dark green radial gradient background
 *  - Logo cluster: DS crest + separator + HUT APKASI 26 (fade/scale in sequence)
 *  - Title text + sub logos (AOE2026, APKASI official)
 *  - Maskot pops in from bottom with bounce
 *  - Progress bar tied to window load + min duration
 *  - Exit: tile-burst (grid squares radial-out from center)
 */

// Webp assets (bundled via Vite)
import crest from './assets/logo_ds.webp';
import hut from './assets/logo_hut26.webp';
import aoe from './assets/logo_aoe2026.webp';
import apkasi from './assets/logo_apkasi.webp';

type Props = {
  minDuration?: number;
  onDone?: () => void;
  oncePerSession?: boolean;
};

const COLS = 10;
const ROWS = 6;

export default function SplashLoader({ minDuration = 3000, onDone, oncePerSession = true }: Props) {
  const skip = oncePerSession && typeof sessionStorage !== 'undefined'
    && sessionStorage.getItem('apkasi_splash_seen') === '1';

  const [gone, setGone] = useState(skip);
  const [go, setGo] = useState(false);
  const [burst, setBurst] = useState(false);
  const [pct, setPct] = useState(0);
  const finishedRef = useRef(false);

  const tiles = useMemo(() => {
    const cx = (COLS - 1) / 2, cy = (ROWS - 1) / 2;
    const maxd = Math.hypot(cx, cy);
    const arr: { left: string; top: string; w: string; h: string; bgPos: string; d: number; r: string }[] = [];
    for (let r = 0; r < ROWS; r++) {
      for (let c = 0; c < COLS; c++) {
        const d = Math.hypot(c - cx, r - cy) / maxd + Math.random() * 0.1;
        const lPct = (c / COLS) * 100;
        const tPct = (r / ROWS) * 100;
        arr.push({
          left: `${lPct}%`,
          top: `${tPct}%`,
          w: `${100 / COLS + 0.5}%`,
          h: `${100 / ROWS + 0.5}%`,
          bgPos: `${-lPct}vw ${-tPct}vh`,
          d,
          r: `${(Math.random() * 60 - 30).toFixed(1)}deg`,
        });
      }
    }
    return arr;
  }, []);

  const particles = useMemo(
    () => Array.from({ length: 28 }, () => ({
      left: `${Math.random() * 100}%`,
      dur: `${5 + Math.random() * 8}s`,
      delay: `${Math.random() * 5}s`,
      size: `${(1.5 + Math.random() * 3).toFixed(1)}px`,
    })),
    []
  );

  useEffect(() => {
    if (gone) return;
    const id = requestAnimationFrame(() => setGo(true));

    const start = performance.now();
    let loaded = document.readyState === 'complete';
    const onLoad = () => { loaded = true; };
    if (!loaded) window.addEventListener('load', onLoad);

    let raf = 0;
    const tick = () => {
      const el = performance.now() - start;
      const p = Math.min(loaded ? 100 : 98, Math.round((el / minDuration) * 100));
      setPct(p);
      if (el >= minDuration && loaded) { finish(); return; }
      raf = requestAnimationFrame(tick);
    };
    raf = requestAnimationFrame(tick);

    function finish() {
      if (finishedRef.current) return;
      finishedRef.current = true;
      setPct(100);
      if (oncePerSession) try { sessionStorage.setItem('apkasi_splash_seen', '1'); } catch { /* */ }
      setTimeout(() => setBurst(true), 250);
      setTimeout(() => { onDone?.(); setGone(true); }, 250 + 800);
    }

    return () => {
      cancelAnimationFrame(id);
      cancelAnimationFrame(raf);
      window.removeEventListener('load', onLoad);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  if (gone) return null;

  const cls = `sp-root${go ? ' sp-go' : ''}${burst ? ' sp-burst' : ''}`;

  return (
    <div className={cls} aria-hidden="true">
      <style>{SPLASH_CSS}</style>

      {/* Tile grid for burst exit */}
      <div className="sp-tiles">
        {tiles.map((t, i) => (
          <div key={i} className="sp-tile"
            style={{
              left: t.left, top: t.top, width: t.w, height: t.h,
              backgroundPosition: t.bgPos,
              '--d': t.d.toFixed(3), '--r': t.r,
            } as React.CSSProperties}
          />
        ))}
      </div>

      {/* Main stage */}
      <div className="sp-stage">
        {/* Ambient glow */}
        <div className="sp-glow" />

        {/* Floating particles */}
        <div className="sp-particles">
          {particles.map((p, i) => (
            <span key={i} style={{
              left: p.left, width: p.size, height: p.size,
              animationDuration: p.dur, animationDelay: p.delay,
            }} />
          ))}
        </div>

        {/* Logo cluster: DS crest | HUT APKASI 26 | HUT DS 80 */}
        <div className="sp-cluster">
          <img className="sp-logo sp-crest" src={crest} alt="" />
          <div className="sp-sep" />
          <div className="sp-hut-wrap">
            <img className="sp-logo sp-hut" src={hut} alt="" />
          </div>
          <div className="sp-sep sp-sep2" />
          <img className="sp-logo sp-ds80top" src="/logos/hutds80.png" alt="" />
        </div>

        {/* Title text */}
        <div className="sp-title">
          <div className="sp-main">HUT Ke-26 APKASI &amp; HUT Ke-80 Kabupaten Deli Serdang</div>
          <div className="sp-sub">Bersinergi Membangun Daerah, Memperkuat Otonomi Indonesia</div>
          <div className="sp-date">1 &ndash; 3 Juli 2026 &middot; Deli Serdang, Sumatera Utara</div>
        </div>

        {/* Bottom logos row: Maskot + AOE2026 + APKASI Official + HUT DS 80 */}
        <div className="sp-bottom">
          <img className="sp-maskot" src="/logos/maskot.png" alt="" />
          <div className="sp-partners">
            <span className="sp-lbl">Bagian dari</span>
            <img className="sp-aoe" src={aoe} alt="" />
            <div className="sp-chip"><img src={apkasi} alt="" /></div>

          </div>
        </div>

        {/* Progress bar */}
        <div className="sp-progress">
          <div className="sp-bar"><i style={{ width: `${pct}%` }} /></div>
          <div className="sp-meta"><span>Memuat pengalaman...</span><span>{pct}%</span></div>
        </div>
      </div>
    </div>
  );
}

const SPLASH_CSS = `
/* ═══ ROOT ═══ */
.sp-root{position:fixed;inset:0;z-index:99500;font-family:'Inter','Plus Jakarta Sans',sans-serif;overflow:hidden}

/* ═══ TILES (burst exit) ═══ */
.sp-tiles{position:absolute;inset:0;z-index:1}
.sp-tile{
  position:absolute;
  background:radial-gradient(120% 110% at 50% 22%,#143d28 0%,#0a1f15 52%,#050e09 100%);
  background-size:100vw 100vh;
  will-change:transform,opacity;
}
.sp-burst .sp-tile{
  margin:2px;border-radius:6px;
  transition:transform .65s cubic-bezier(.55,.02,.4,1),opacity .65s ease,margin .15s ease,border-radius .15s ease;
  transition-delay:calc(var(--d) * .45s);
  transform:scale(.08) rotate(var(--r));opacity:0;
}

/* ═══ STAGE ═══ */
.sp-stage{
  position:absolute;inset:0;z-index:2;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  gap:22px;padding:24px;
  transition:opacity .3s ease,transform .45s ease;
}
.sp-burst .sp-stage{opacity:0;transform:scale(1.08)}

/* ═══ GLOW ═══ */
.sp-glow{
  position:absolute;width:700px;height:700px;border-radius:50%;z-index:-1;opacity:0;
  background:conic-gradient(from 0deg,rgba(225,178,60,0),rgba(225,178,60,.14),rgba(100,200,140,.08),rgba(225,178,60,0));
  filter:blur(35px);animation:sp-spin 20s linear infinite;animation-play-state:paused;
}
.sp-go .sp-glow{opacity:1;transition:opacity 1.4s ease .2s;animation-play-state:running}
@keyframes sp-spin{to{transform:rotate(360deg)}}

/* ═══ PARTICLES ═══ */
.sp-particles{position:absolute;inset:0;overflow:hidden;z-index:0;pointer-events:none}
.sp-particles span{
  position:absolute;bottom:-10px;border-radius:50%;
  background:rgba(225,178,60,.65);opacity:0;
  animation:sp-float linear infinite;
}
@keyframes sp-float{
  0%{transform:translateY(0) scale(0);opacity:0}
  8%{opacity:.7;transform:translateY(-8vh) scale(1)}
  85%{opacity:.4}
  100%{transform:translateY(-105vh) scale(.6);opacity:0}
}

/* ═══ LOGO CLUSTER ═══ */
.sp-cluster{
  display:flex;align-items:center;gap:clamp(18px,3.5vw,44px);
  animation:sp-bob 6s ease-in-out infinite alternate;
}
@keyframes sp-bob{from{transform:translateY(4px)}to{transform:translateY(-6px)}}

.sp-logo{will-change:transform,opacity;opacity:0;transform:translateY(20px) scale(.82)}
.sp-crest{height:clamp(80px,13vw,130px);width:auto}
.sp-hut{height:clamp(90px,14vw,150px);width:auto}

.sp-sep{
  width:1.5px;height:clamp(56px,9vw,95px);opacity:0;
  background:linear-gradient(transparent,rgba(255,255,255,.5),transparent);
}

.sp-hut-wrap{position:relative;overflow:hidden;border-radius:8px}
.sp-hut-wrap::after{
  content:"";position:absolute;inset:0;transform:translateX(-140%);
  background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,.6) 50%,transparent 70%);
}

/* Entrance animations */
.sp-go .sp-crest{animation:sp-pop .9s .12s cubic-bezier(.2,.8,.2,1) forwards}
.sp-go .sp-sep{animation:sp-fadeIn .8s .3s ease forwards}
.sp-go .sp-hut{animation:sp-pop .95s .25s cubic-bezier(.2,.8,.2,1) forwards}
.sp-go .sp-sep2{animation:sp-fadeIn .8s .4s ease forwards}
.sp-go .sp-ds80top{animation:sp-pop .95s .45s cubic-bezier(.2,.8,.2,1) forwards}
.sp-go .sp-hut-wrap::after{animation:sp-shine 1.5s 1s ease-out}

.sp-ds80top{height:clamp(80px,13vw,130px);width:auto;filter:drop-shadow(0 2px 10px rgba(0,0,0,.4))}

@keyframes sp-pop{to{opacity:1;transform:none}}
@keyframes sp-fadeIn{to{opacity:.85}}
@keyframes sp-shine{to{transform:translateX(140%)}}

/* ═══ TITLE ═══ */
.sp-title{color:#fff;text-align:center;opacity:0;transform:translateY(18px)}
.sp-main{font-weight:700;font-size:clamp(14px,2.4vw,22px);letter-spacing:.01em}
.sp-sub{margin-top:6px;font-weight:500;font-size:clamp(11px,1.6vw,15px);color:rgba(255,255,255,.65);font-style:italic}
.sp-date{margin-top:8px;font-weight:600;font-size:clamp(12px,1.8vw,16px);color:#e1b23c;letter-spacing:.04em}
.sp-go .sp-title{animation:sp-up 1s .45s cubic-bezier(.2,.8,.2,1) forwards}
@keyframes sp-up{to{opacity:1;transform:none}}

/* ═══ BOTTOM ROW ═══ */
.sp-bottom{
  display:flex;align-items:flex-end;gap:clamp(14px,3vw,32px);
  opacity:0;transform:translateY(16px);
}
.sp-go .sp-bottom{animation:sp-up 1s .65s cubic-bezier(.2,.8,.2,1) forwards}

.sp-maskot{
  height:clamp(60px,10vw,100px);width:auto;
  animation:sp-mascotBounce 2s 1.2s ease-out infinite alternate;
  transform-origin:bottom center;
}
@keyframes sp-mascotBounce{
  0%{transform:translateY(0) rotate(-1deg)}
  50%{transform:translateY(-8px) rotate(1deg)}
  100%{transform:translateY(0) rotate(-1deg)}
}

.sp-partners{display:flex;align-items:center;gap:14px}
.sp-lbl{font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.45);font-weight:600}
.sp-aoe{height:clamp(28px,4vw,38px);width:auto}
.sp-chip{
  background:#f5f2eb;padding:6px 10px;border-radius:8px;
  display:flex;align-items:center;box-shadow:0 6px 20px rgba(0,0,0,.3);
}
.sp-chip img{height:clamp(20px,3vw,28px)}

/* ═══ PROGRESS ═══ */
.sp-progress{width:min(300px,75vw);opacity:0}
.sp-go .sp-progress{animation:sp-up .8s .5s ease forwards}
.sp-bar{height:3px;border-radius:99px;background:rgba(255,255,255,.12);overflow:hidden}
.sp-bar i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,#7fc88a,#e1b23c);transition:width .2s ease}
.sp-meta{display:flex;justify-content:space-between;margin-top:8px;font-size:11px;color:rgba(255,255,255,.5);font-weight:600;letter-spacing:.05em}

/* ═══ REDUCED MOTION ═══ */
@media(prefers-reduced-motion:reduce){
  .sp-cluster,.sp-glow,.sp-particles span,.sp-maskot{animation:none!important}
}
`;
