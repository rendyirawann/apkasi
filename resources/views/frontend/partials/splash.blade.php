{{-- SplashLoader — port vanilla JS dari frontend/src/SplashLoader.tsx (oncePerSession=false, minDuration=3000) --}}
<div id="splash" class="sp-root" aria-hidden="true">
    <style>
        .sp-root{position:fixed;inset:0;z-index:99500;font-family:'Inter','Plus Jakarta Sans',sans-serif;overflow:hidden}
        .sp-tiles{position:absolute;inset:0;z-index:1}
        .sp-tile{position:absolute;background:radial-gradient(120% 110% at 50% 22%,#143d28 0%,#0a1f15 52%,#050e09 100%);background-size:100vw 100vh;will-change:transform,opacity}
        .sp-burst .sp-tile{margin:2px;border-radius:6px;transition:transform .65s cubic-bezier(.55,.02,.4,1),opacity .65s ease,margin .15s ease,border-radius .15s ease;transition-delay:calc(var(--d) * .45s);transform:scale(.08) rotate(var(--r));opacity:0}
        .sp-stage{position:absolute;inset:0;z-index:2;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:22px;padding:24px;transition:opacity .3s ease,transform .45s ease}
        .sp-burst .sp-stage{opacity:0;transform:scale(1.08)}
        .sp-glow{position:absolute;width:700px;height:700px;border-radius:50%;z-index:-1;opacity:0;background:conic-gradient(from 0deg,rgba(225,178,60,0),rgba(225,178,60,.14),rgba(100,200,140,.08),rgba(225,178,60,0));filter:blur(35px);animation:sp-spin 20s linear infinite;animation-play-state:paused}
        .sp-go .sp-glow{opacity:1;transition:opacity 1.4s ease .2s;animation-play-state:running}
        @keyframes sp-spin{to{transform:rotate(360deg)}}
        .sp-particles{position:absolute;inset:0;overflow:hidden;z-index:0;pointer-events:none}
        .sp-particles span{position:absolute;bottom:-10px;border-radius:50%;background:rgba(225,178,60,.65);opacity:0;animation:sp-float linear infinite}
        @keyframes sp-float{0%{transform:translateY(0) scale(0);opacity:0}8%{opacity:.7;transform:translateY(-8vh) scale(1)}85%{opacity:.4}100%{transform:translateY(-105vh) scale(.6);opacity:0}}
        .sp-cluster{display:flex;align-items:center;gap:clamp(18px,3.5vw,44px);animation:sp-bob 6s ease-in-out infinite alternate}
        @keyframes sp-bob{from{transform:translateY(4px)}to{transform:translateY(-6px)}}
        .sp-logo{will-change:transform,opacity;opacity:0;transform:translateY(20px) scale(.82)}
        .sp-crest{height:clamp(80px,13vw,130px);width:auto}
        .sp-hut{height:clamp(90px,14vw,150px);width:auto}
        .sp-sep{width:1.5px;height:clamp(56px,9vw,95px);opacity:0;background:linear-gradient(transparent,rgba(255,255,255,.5),transparent)}
        .sp-hut-wrap{position:relative;overflow:hidden;border-radius:8px}
        .sp-hut-wrap::after{content:"";position:absolute;inset:0;transform:translateX(-140%);background:linear-gradient(105deg,transparent 30%,rgba(255,255,255,.6) 50%,transparent 70%)}
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
        .sp-title{color:#fff;text-align:center;opacity:0;transform:translateY(18px)}
        .sp-main{font-weight:700;font-size:clamp(14px,2.4vw,22px);letter-spacing:.01em}
        .sp-sub{margin-top:6px;font-weight:500;font-size:clamp(11px,1.6vw,15px);color:rgba(255,255,255,.65);font-style:italic}
        .sp-date{margin-top:8px;font-weight:600;font-size:clamp(12px,1.8vw,16px);color:#e1b23c;letter-spacing:.04em}
        .sp-go .sp-title{animation:sp-up 1s .45s cubic-bezier(.2,.8,.2,1) forwards}
        @keyframes sp-up{to{opacity:1;transform:none}}
        .sp-bottom{display:flex;align-items:flex-end;gap:clamp(14px,3vw,32px);opacity:0;transform:translateY(16px)}
        .sp-go .sp-bottom{animation:sp-up 1s .65s cubic-bezier(.2,.8,.2,1) forwards}
        .sp-maskot{height:clamp(60px,10vw,100px);width:auto;animation:sp-mascotBounce 2s 1.2s ease-out infinite alternate;transform-origin:bottom center}
        @keyframes sp-mascotBounce{0%{transform:translateY(0) rotate(-1deg)}50%{transform:translateY(-8px) rotate(1deg)}100%{transform:translateY(0) rotate(-1deg)}}
        .sp-partners{display:flex;align-items:center;gap:14px}
        .sp-lbl{font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.45);font-weight:600}
        .sp-aoe{height:clamp(28px,4vw,38px);width:auto}
        .sp-chip{background:#f5f2eb;padding:6px 10px;border-radius:8px;display:flex;align-items:center;box-shadow:0 6px 20px rgba(0,0,0,.3)}
        .sp-chip img{height:clamp(20px,3vw,28px)}
        .sp-progress{width:min(300px,75vw);opacity:0}
        .sp-go .sp-progress{animation:sp-up .8s .5s ease forwards}
        .sp-bar{height:3px;border-radius:99px;background:rgba(255,255,255,.12);overflow:hidden}
        .sp-bar i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,#7fc88a,#e1b23c);transition:width .2s ease;width:0}
        .sp-meta{display:flex;justify-content:space-between;margin-top:8px;font-size:11px;color:rgba(255,255,255,.5);font-weight:600;letter-spacing:.05em}
        @media(prefers-reduced-motion:reduce){.sp-cluster,.sp-glow,.sp-particles span,.sp-maskot{animation:none!important}}
    </style>

    <div class="sp-tiles" id="sp-tiles"></div>

    <div class="sp-stage">
        <div class="sp-glow"></div>
        <div class="sp-particles" id="sp-particles"></div>

        <div class="sp-cluster">
            <img class="sp-logo sp-crest" src="{{ asset('logos/logo_ds.webp') }}" alt="" />
            <div class="sp-sep"></div>
            <div class="sp-hut-wrap"><img class="sp-logo sp-hut" src="{{ asset('logos/logo_hut26.webp') }}" alt="" /></div>
            <div class="sp-sep sp-sep2"></div>
            <img class="sp-logo sp-ds80top" src="{{ asset('logos/hutds80.png') }}" alt="" />
        </div>

        <div class="sp-title">
            <div class="sp-main">HUT Ke-26 APKASI &amp; HUT Ke-80 Kabupaten Deli Serdang</div>
            <div class="sp-sub">Bersinergi Membangun Daerah, Memperkuat Otonomi Indonesia</div>
            <div class="sp-date">1 &ndash; 3 Juli 2026 &middot; Deli Serdang, Sumatera Utara</div>
        </div>

        <div class="sp-bottom">
            <img class="sp-maskot" src="{{ asset('logos/maskot.png') }}" alt="" />
            <div class="sp-partners">
                <span class="sp-lbl">Bagian dari</span>
                <img class="sp-aoe" src="{{ asset('logos/logo_aoe2026.webp') }}" alt="" />
                <div class="sp-chip"><img src="{{ asset('logos/logo_apkasi.webp') }}" alt="" /></div>
            </div>
        </div>

        <div class="sp-progress">
            <div class="sp-bar"><i id="sp-bar-fill"></i></div>
            <div class="sp-meta"><span>Memuat pengalaman...</span><span id="sp-pct">0%</span></div>
        </div>
    </div>
</div>

<script>
(function () {
    var MIN = 3000, COLS = 10, ROWS = 6;
    var root = document.getElementById('splash');
    if (!root) return;

    // Build tiles
    var tilesWrap = document.getElementById('sp-tiles');
    var cx = (COLS - 1) / 2, cy = (ROWS - 1) / 2, maxd = Math.hypot(cx, cy);
    for (var r = 0; r < ROWS; r++) {
        for (var c = 0; c < COLS; c++) {
            var d = Math.hypot(c - cx, r - cy) / maxd + Math.random() * 0.1;
            var lPct = (c / COLS) * 100, tPct = (r / ROWS) * 100;
            var t = document.createElement('div');
            t.className = 'sp-tile';
            t.style.left = lPct + '%'; t.style.top = tPct + '%';
            t.style.width = (100 / COLS + 0.5) + '%'; t.style.height = (100 / ROWS + 0.5) + '%';
            t.style.backgroundPosition = (-lPct) + 'vw ' + (-tPct) + 'vh';
            t.style.setProperty('--d', d.toFixed(3));
            t.style.setProperty('--r', (Math.random() * 60 - 30).toFixed(1) + 'deg');
            tilesWrap.appendChild(t);
        }
    }

    // Build particles
    var pWrap = document.getElementById('sp-particles');
    for (var i = 0; i < 28; i++) {
        var s = document.createElement('span');
        var size = (1.5 + Math.random() * 3).toFixed(1) + 'px';
        s.style.left = (Math.random() * 100) + '%';
        s.style.width = size; s.style.height = size;
        s.style.animationDuration = (5 + Math.random() * 8) + 's';
        s.style.animationDelay = (Math.random() * 5) + 's';
        pWrap.appendChild(s);
    }

    var bar = document.getElementById('sp-bar-fill');
    var pctEl = document.getElementById('sp-pct');
    var finished = false;

    requestAnimationFrame(function () { root.classList.add('sp-go'); });

    var start = performance.now();
    var loaded = document.readyState === 'complete';
    if (!loaded) window.addEventListener('load', function () { loaded = true; });

    function finish() {
        if (finished) return;
        finished = true;
        bar.style.width = '100%'; pctEl.textContent = '100%';
        setTimeout(function () { root.classList.add('sp-burst'); }, 250);
        setTimeout(function () { root.parentNode && root.parentNode.removeChild(root); }, 250 + 800);
    }

    function tick() {
        var el = performance.now() - start;
        var p = Math.min(loaded ? 100 : 98, Math.round((el / MIN) * 100));
        bar.style.width = p + '%'; pctEl.textContent = p + '%';
        if (el >= MIN && loaded) { finish(); return; }
        requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
})();
</script>
