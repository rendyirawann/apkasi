import { useEffect, useMemo, useRef, useState } from 'react';

/*
 *  SplashLoader.tsx
 *  Splash / preloader APKASI 2026 + HUT Deli Serdang.
 *  - Logo fade/scale masuk berurutan + shine sweep
 *  - Progress bar (digate ke durasi minimal & window load)
 *  - Exit: kotak-kotak (tile) burst radial dari tengah, halaman home keliatan di belakang
 *  - Self-unmount: balik null setelah selesai, gak butuh state dari parent
 *
 *  Taruh 4 file webp hasil cleaning di src/assets/ lalu sesuaikan import di bawah.
 */
import crest from './assets/logo_ds.webp';
import hut from './assets/logo_hut26.webp';
import aoe from './assets/logo_aoe2026.webp';
import apkasi from './assets/logo_apkasi.webp';

type Props = {
  /** durasi minimal splash tampil walau aset sudah ke-load (ms) */
  minDuration?: number;
  /** dipanggil tepat sebelum komponen unmount, kalau perlu */
  onDone?: () => void;
  /** true = cuma tampil sekali per sesi browser */
  oncePerSession?: boolean;
};

const COLS = 8;
const ROWS = 5;

export default function SplashLoader({ minDuration = 2200, onDone, oncePerSession = false }: Props) {
  const skip = oncePerSession && typeof sessionStorage !== 'undefined'
    && sessionStorage.getItem('apkasi_splash_seen') === '1';

  const [gone, setGone] = useState(skip);
  const [go, setGo] = useState(false);
  const [burst, setBurst] = useState(false);
  const [pct, setPct] = useState(0);
  const finishedRef = useRef(false);

  // grid tile + delay radial dari pusat
  const tiles = useMemo(() => {
    const cx = (COLS - 1) / 2, cy = (ROWS - 1) / 2;
    const maxd = Math.hypot(cx, cy);
    const arr: { pos: string; d: number; r: string }[] = [];
    for (let r = 0; r < ROWS; r++) {
      for (let c = 0; c < COLS; c++) {
        const d = Math.hypot(c - cx, r - cy) / maxd + Math.random() * 0.12;
        arr.push({
          pos: `left ${(c * 100) / (COLS - 1)}% top ${(r * 100) / (ROWS - 1)}%`,
          d,
          r: `${(Math.random() * 40 - 20).toFixed(1)}deg`,
        });
      }
    }
    return arr;
  }, []);

  const dust = useMemo(
    () => Array.from({ length: 22 }, () => ({
      left: `${Math.random() * 100}%`,
      dur: `${6 + Math.random() * 7}s`,
      delay: `${Math.random() * 6}s`,
      size: `${(2 + Math.random() * 3).toFixed(1)}px`,
    })),
    []
  );

  useEffect(() => {
    if (gone) return;
    const id = requestAnimationFrame(() => setGo(true)); // trigger animasi masuk

    const start = performance.now();
    let loaded = document.readyState === 'complete';
    const onLoad = () => { loaded = true; };
    if (!loaded) window.addEventListener('load', onLoad);

    let raf = 0;
    const tick = () => {
      const el = performance.now() - start;
      const p = Math.min(loaded ? 100 : 99, Math.round((el / minDuration) * 100));
      setPct(p);
      if (el >= minDuration && loaded) { finish(); return; }
      raf = requestAnimationFrame(tick);
    };
    raf = requestAnimationFrame(tick);

    function finish() {
      if (finishedRef.current) return;
      finishedRef.current = true;
      setPct(100);
      if (oncePerSession) try { sessionStorage.setItem('apkasi_splash_seen', '1'); } catch { /* ignore */ }
      setTimeout(() => setBurst(true), 180);     // logo fade -> tile burst
      setTimeout(() => { onDone?.(); setGone(true); }, 180 + 700);
    }

    return () => {
      cancelAnimationFrame(id);
      cancelAnimationFrame(raf);
      window.removeEventListener('load', onLoad);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  if (gone) return null;

  const cls = `apk-splash${go ? ' go' : ''}${burst ? ' burst' : ''}`;

  return (
    <div className={cls} aria-hidden="true">
      <style>{CSS}</style>

      {/* tiles = backdrop gradient yang sambung */}
      <div className="tiles" style={{ gridTemplateColumns: `repeat(${COLS},1fr)`, gridTemplateRows: `repeat(${ROWS},1fr)` }}>
        {tiles.map((t, i) => (
          <div key={i} className="tile"
            style={{ backgroundPosition: t.pos, ['--d' as string]: t.d.toFixed(3), ['--r' as string]: t.r }} />
        ))}
      </div>

      <div className="stage">
        <div className="glow" />
        <div className="dust">
          {dust.map((d, i) => (
            <span key={i} style={{ left: d.left, width: d.size, height: d.size, animationDuration: d.dur, animationDelay: d.delay }} />
          ))}
        </div>

        <div className="cluster">
          <img className="crest" src={crest} alt="" />
          <div className="sep" />
          <div className="hut-wrap">
            <img className="hut" src={hut} alt="" />
          </div>
        </div>

        <div className="title">
          <div className="main">HUT Ke-26 APKASI &amp; HUT Ke-80 Kabupaten Deli Serdang</div>
          <div className="date">1 &ndash; 3 Juli 2026 &middot; Deli Serdang, Sumatera Utara</div>
        </div>

        <div className="presenters">
          <span className="lbl">Bagian dari</span>
          <img className="aoe" src={aoe} alt="" />
          <div className="chip"><img src={apkasi} alt="" /></div>
        </div>

        <div className="progress">
          <div className="bar"><i style={{ width: `${pct}%` }} /></div>
          <div className="pmeta"><span>Memuat pengalaman</span><span>{pct}%</span></div>
        </div>
      </div>
    </div>
  );
}

const CSS = `
.apk-splash{position:fixed;inset:0;z-index:99500;font-family:'Plus Jakarta Sans',sans-serif}
.apk-splash .tiles{position:absolute;inset:0;display:grid;z-index:1}
.apk-splash .tile{
  background-image:radial-gradient(120% 110% at 50% 22%,#1a4a2e 0%,#0b2014 52%,#06120b 100%);
  background-size:100vw 100vh;background-repeat:no-repeat;will-change:transform,opacity;
}
.apk-splash.burst .tile{
  transition:transform .55s cubic-bezier(.6,.02,.5,1),opacity .55s ease;
  transition-delay:calc(var(--d) * .42s);
  transform:scale(.12) rotate(var(--r));opacity:0;
}
.apk-splash .stage{position:absolute;inset:0;z-index:2;display:flex;flex-direction:column;
  align-items:center;justify-content:center;gap:26px;padding:24px;
  transition:opacity .35s ease,transform .5s ease}
.apk-splash.burst .stage{opacity:0;transform:scale(1.06)}
.apk-splash .glow{position:absolute;width:680px;height:680px;border-radius:50%;z-index:-1;opacity:0;
  background:conic-gradient(from 0deg,rgba(225,178,60,0),rgba(225,178,60,.16),rgba(120,200,140,.10),rgba(225,178,60,0));
  filter:blur(30px);animation:apk-spin 18s linear infinite;animation-play-state:paused}
.apk-splash.go .glow{opacity:1;transition:opacity 1.2s ease .2s;animation-play-state:running}
@keyframes apk-spin{to{transform:rotate(360deg)}}
.apk-splash .cluster{display:flex;align-items:center;gap:clamp(16px,3vw,40px);animation:apk-bob 6s ease-in-out infinite alternate}
@keyframes apk-bob{from{transform:translateY(5px)}to{transform:translateY(-7px)}}
.apk-splash .crest{height:clamp(82px,12vw,124px);width:auto;opacity:0;transform:translateY(18px) scale(.8)}
.apk-splash .sep{width:1px;height:clamp(60px,9vw,92px);opacity:0;
  background:linear-gradient(transparent,rgba(255,255,255,.45),transparent)}
.apk-splash .hut{height:clamp(92px,13vw,140px);width:auto;opacity:0;transform:translateY(18px) scale(.85)}
.apk-splash.go .crest{animation:apk-pop .9s .15s cubic-bezier(.2,.8,.2,1) forwards}
.apk-splash.go .sep{animation:apk-fade .8s .35s ease forwards}
.apk-splash.go .hut{animation:apk-pop .95s .3s cubic-bezier(.2,.8,.2,1) forwards}
@keyframes apk-pop{to{opacity:1;transform:none}}
@keyframes apk-fade{to{opacity:.8}}
.apk-splash .hut-wrap{position:relative;overflow:hidden;border-radius:8px}
.apk-splash .hut-wrap::after{content:"";position:absolute;inset:0;transform:translateX(-130%);
  background:linear-gradient(105deg,transparent 35%,rgba(255,255,255,.55) 50%,transparent 65%)}
.apk-splash.go .hut-wrap::after{animation:apk-shine 1.4s 1.05s ease-out}
@keyframes apk-shine{to{transform:translateX(130%)}}
.apk-splash .title{color:#fff;text-align:center;opacity:0;transform:translateY(16px)}
.apk-splash .title .main{font-weight:700;font-size:clamp(15px,2.3vw,20px)}
.apk-splash .title .date{margin-top:6px;font-weight:600;font-size:clamp(12px,1.7vw,15px);color:#e1b23c;letter-spacing:.04em}
.apk-splash.go .title{animation:apk-up 1s .5s cubic-bezier(.2,.8,.2,1) forwards}
@keyframes apk-up{to{opacity:1;transform:none}}
.apk-splash .presenters{display:flex;align-items:center;gap:18px;opacity:0;transform:translateY(14px)}
.apk-splash .presenters .lbl{font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.5);font-weight:600}
.apk-splash .presenters .aoe{height:34px;width:auto}
.apk-splash .chip{background:#f5f2eb;padding:7px 12px;border-radius:10px;display:flex;align-items:center;box-shadow:0 6px 20px rgba(0,0,0,.25)}
.apk-splash .chip img{height:26px}
.apk-splash.go .presenters{animation:apk-up 1s .65s cubic-bezier(.2,.8,.2,1) forwards}
.apk-splash .progress{width:min(280px,70vw);opacity:0}
.apk-splash.go .progress{animation:apk-up .8s .55s ease forwards}
.apk-splash .bar{height:3px;border-radius:99px;background:rgba(255,255,255,.14);overflow:hidden}
.apk-splash .bar i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,#7fc88a,#e1b23c);transition:width .25s ease}
.apk-splash .pmeta{display:flex;justify-content:space-between;margin-top:9px;font-size:11px;color:rgba(255,255,255,.55);font-weight:600;letter-spacing:.05em}
.apk-splash .dust{position:absolute;inset:0;overflow:hidden;z-index:0;pointer-events:none}
.apk-splash .dust span{position:absolute;bottom:-10px;border-radius:50%;background:rgba(225,178,60,.7);opacity:0;animation:apk-dust linear infinite}
@keyframes apk-dust{0%{transform:translateY(0);opacity:0}10%{opacity:.8}90%{opacity:.5}100%{transform:translateY(-105vh);opacity:0}}
@media(prefers-reduced-motion:reduce){
  .apk-splash .cluster,.apk-splash .glow,.apk-splash .dust span{animation:none!important}
}
`;
