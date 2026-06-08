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
        /* ── Omni logo cycler (Ben 10 vibe) ── */
        .sp-omni{position:relative;width:clamp(150px,23vw,224px);height:clamp(150px,23vw,224px);display:flex;align-items:center;justify-content:center;opacity:0;transform:scale(.82)}
        .sp-go .sp-omni{animation:sp-pop .9s .15s cubic-bezier(.2,.8,.2,1) forwards}
        .sp-omni-ring{position:absolute;inset:0;border-radius:50%;background:conic-gradient(from 0deg,rgba(225,178,60,0) 0deg,rgba(225,178,60,.55) 90deg,rgba(127,200,138,.5) 180deg,rgba(225,178,60,0) 320deg);opacity:0;animation:sp-spin 3.4s linear infinite}
        .sp-go .sp-omni-ring{opacity:.8;transition:opacity 1s ease .2s}
        .sp-omni-ring::after{content:"";position:absolute;inset:12px;border-radius:50%;background:radial-gradient(circle,rgba(12,36,23,.6),rgba(10,31,21,.4) 68%,transparent)}
        .sp-omni-flash{position:absolute;inset:-12%;border-radius:50%;pointer-events:none;opacity:0;background:radial-gradient(circle,rgba(140,225,160,.95),rgba(225,178,60,.45) 45%,transparent 70%)}
        .sp-omni-flash.go{animation:sp-flash .6s ease-out}
        @keyframes sp-flash{0%{opacity:0;transform:scale(.55)}28%{opacity:1}100%{opacity:0;transform:scale(1.3)}}
        .sp-omni-stage{position:relative;width:74%;height:74%;perspective:820px}
        .sp-omni-logo{position:absolute;inset:0;margin:auto;max-width:100%;max-height:100%;width:auto;height:auto;opacity:0;transform:rotateY(85deg) scale(.7);filter:blur(3px) drop-shadow(0 4px 16px rgba(0,0,0,.4));transition:opacity .55s ease,transform .62s cubic-bezier(.3,1.3,.5,1),filter .5s ease;backface-visibility:hidden;will-change:transform,opacity}
        .sp-omni-logo.is-on{opacity:1;transform:rotateY(0deg) scale(1);filter:blur(0) drop-shadow(0 6px 20px rgba(0,0,0,.45))}
        .sp-omni-logo.is-out{opacity:0;transform:rotateY(-85deg) scale(.7);filter:blur(3px)}
        .sp-title{color:#fff;text-align:center;opacity:0;transform:translateY(18px)}
        .sp-main{font-weight:700;font-size:clamp(14px,2.4vw,22px);letter-spacing:.01em}
        .sp-sub{margin-top:6px;font-weight:500;font-size:clamp(11px,1.6vw,15px);color:rgba(255,255,255,.65);font-style:italic}
        .sp-date{margin-top:8px;font-weight:600;font-size:clamp(12px,1.8vw,16px);color:#e1b23c;letter-spacing:.04em}
        .sp-go .sp-title{animation:sp-up 1s .45s cubic-bezier(.2,.8,.2,1) forwards}
        @keyframes sp-up{to{opacity:1;transform:none}}
        .sp-bottom{display:flex;align-items:flex-end;gap:clamp(14px,3vw,32px);opacity:0;transform:translateY(16px)}
        .sp-go .sp-bottom{animation:sp-up 1s .65s cubic-bezier(.2,.8,.2,1) forwards}
        /* Maskot berlari — APNG transparan (animasi jalan di semua browser termasuk Safari, tak seperti WebM). */
        .sp-maskot{width:clamp(96px,12vw,132px);height:clamp(120px,15vw,168px);object-fit:cover;animation:sp-mascotBounce 2.4s 1.2s ease-out infinite alternate;transform-origin:bottom center;pointer-events:none}
        @keyframes sp-mascotBounce{0%{transform:translateY(0) rotate(-1deg)}50%{transform:translateY(-7px) rotate(1deg)}100%{transform:translateY(0) rotate(-1deg)}}
        .sp-partners{display:flex;align-items:center;gap:14px}
        .sp-lbl{font-size:9px;letter-spacing:.2em;text-transform:uppercase;color:rgba(255,255,255,.45);font-weight:600}
        .sp-aoe{height:clamp(28px,4vw,38px);width:auto}
        .sp-chip{background:#f5f2eb;padding:6px 10px;border-radius:8px;display:flex;align-items:center;box-shadow:0 6px 20px rgba(0,0,0,.3)}
        .sp-chip img{height:clamp(20px,3vw,28px)}
        .sp-progress{position:relative;width:min(300px,75vw);opacity:0}
        .sp-go .sp-progress{animation:sp-up .8s .5s ease forwards}
        .sp-loadwrap{transition:opacity .4s ease,transform .4s ease}
        .sp-progress.is-welcome .sp-loadwrap{opacity:0;transform:translateY(-8px);pointer-events:none}
        .sp-welcome{position:absolute;top:50%;left:50%;width:max-content;max-width:92vw;text-align:center;opacity:0;transform:translate(-50%,calc(-50% + 16px)) scale(.9);transition:opacity .7s ease .18s,transform .8s cubic-bezier(.2,.8,.2,1) .18s;font-family:'Plus Jakarta Sans',sans-serif;font-weight:800;font-size:clamp(24px,4vw,40px);letter-spacing:.005em;line-height:1.15;white-space:nowrap;pointer-events:none;background:linear-gradient(90deg,#7fc88a,#e1b23c);-webkit-background-clip:text;background-clip:text;color:transparent;filter:drop-shadow(0 3px 14px rgba(0,0,0,.4))}
        .sp-progress.is-welcome .sp-welcome{opacity:1;transform:translate(-50%,-50%) scale(1)}
        .sp-bar{height:3px;border-radius:99px;background:rgba(255,255,255,.12);overflow:hidden}
        .sp-bar i{display:block;height:100%;border-radius:99px;background:linear-gradient(90deg,#7fc88a,#e1b23c);transition:width .2s ease;width:0}
        .sp-meta{display:flex;justify-content:space-between;margin-top:8px;font-size:11px;color:rgba(255,255,255,.5);font-weight:600;letter-spacing:.05em}
        @media(prefers-reduced-motion:reduce){.sp-cluster,.sp-glow,.sp-particles span,.sp-maskot{animation:none!important}}
    </style>

    <div class="sp-tiles" id="sp-tiles"></div>

    <div class="sp-stage">
        <div class="sp-glow"></div>
        <div class="sp-particles" id="sp-particles"></div>

        <div class="sp-omni">
            <div class="sp-omni-ring"></div>
            <div class="sp-omni-flash"></div>
            <div class="sp-omni-stage">
                <img class="sp-omni-logo is-on" src="{{ asset('logos/logo_ds.png') }}" alt="" />
                <img class="sp-omni-logo" src="{{ asset('logos/logo_hut26.png') }}" alt="" />
                <img class="sp-omni-logo" src="{{ asset('logos/hutds80.png') }}" alt="" />
                <img class="sp-omni-logo" src="{{ asset('logos/logo_aoe2026.png') }}" alt="" />
                <img class="sp-omni-logo" src="{{ asset('logos/apkasi-official.png') }}" alt="" />
                <img class="sp-omni-logo" src="{{ asset('logos/poi.png') }}" alt="" />
            </div>
        </div>

        <div class="sp-title">
            <div class="sp-main">HUT Ke-26 APKASI &amp; HUT Ke-80 Kabupaten Deli Serdang</div>
            <div class="sp-sub">Bersinergi Membangun Daerah, Memperkuat Otonomi Indonesia</div>
            <div class="sp-date">1 &ndash; 3 Juli 2026 &middot; Deli Serdang, Sumatera Utara</div>
        </div>

        <div class="sp-bottom">
            {{-- WebP animasi (transparan, dirender benar oleh Safari 14+ & Chrome) dgn APNG sbg fallback.
                 Safari punya bug render alpha APNG (latar jadi hitam) → WebP menghindarinya. --}}
            <picture aria-hidden="true">
                <source srcset="{{ asset('assets/apkasi/maskot-run.webp') }}" type="image/webp" />
                <img class="sp-maskot" src="{{ asset('assets/apkasi/maskot-run.png') }}" alt="" aria-hidden="true" />
            </picture>
            <div class="sp-partners">
                <span class="sp-lbl">Bagian dari</span>
                <img class="sp-aoe" src="{{ asset('logos/logo_aoe2026.png') }}" alt="" />
                <div class="sp-chip"><img src="{{ asset('logos/logo_apkasi.png') }}" alt="" /></div>
            </div>
        </div>

        <div class="sp-progress" id="sp-progress">
            <div class="sp-loadwrap">
                <div class="sp-bar"><i id="sp-bar-fill"></i></div>
                <div class="sp-meta"><span>Memuat pengalaman...</span><span id="sp-pct">0%</span></div>
            </div>
            <div class="sp-welcome">Selamat Datang</div>
        </div>
    </div>
</div>

<script>
(function () {
    var MIN = 5500, COLS = 10, ROWS = 6;
    var root = document.getElementById('splash');
    if (!root) return;

    // Jika datang dari halaman lain menuju section tertentu (mis. /#poi),
    // lewati splash dan langsung arahkan ke section-nya.
    var _hash = window.location.hash;
    if (_hash && _hash.length > 1) {
        // Catatan: section-nya mungkin belum diparse saat skrip ini jalan (skrip ada di atas
        // dokumen). Jadi cukup deteksi ADANYA hash utk melewati splash; cari elemen & scroll
        // setelah DOM siap, lalu perkuat lagi setelah semua aset 'load'.
        if (root.parentNode) root.parentNode.removeChild(root);
        var goToSection = function () {
            var t = document.getElementById(_hash.slice(1));
            if (t) t.scrollIntoView({ behavior: 'instant', block: 'start' });
        };
        var runGo = function () { requestAnimationFrame(goToSection); };
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', runGo);
        else runGo();
        window.addEventListener('load', function () { setTimeout(goToSection, 60); });
        return;
    }

    // Pastikan halaman mulai dari paling atas & kunci scroll selama splash
    // (mencegah scroll-anchoring menggeser posisi saat aset/font dimuat di belakang splash).
    try { if ('scrollRestoration' in history) history.scrollRestoration = 'manual'; } catch (e) {}
    window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
    document.documentElement.style.overflow = 'hidden';
    document.body.style.overflow = 'hidden';

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

    // ── Omni logo cycler: logo berputar + flash, berganti satu-satu (Ben 10 vibe) ──
    var omniLogos = root.querySelectorAll('.sp-omni-logo');
    var omniFlash = root.querySelector('.sp-omni-flash');
    var oi = 0;
    var omniTimer = setInterval(function () {
        if (finished || omniLogos.length < 2) { clearInterval(omniTimer); return; }
        var prev = oi;
        omniLogos[prev].classList.remove('is-on');
        omniLogos[prev].classList.add('is-out');
        (function (el) { setTimeout(function () { el.classList.remove('is-out'); }, 680); })(omniLogos[prev]);
        oi = (oi + 1) % omniLogos.length;
        omniLogos[oi].classList.add('is-on');
        if (omniFlash) { omniFlash.classList.remove('go'); void omniFlash.offsetWidth; omniFlash.classList.add('go'); }
    }, 980);

    var start = performance.now();
    var loaded = document.readyState === 'complete';
    if (!loaded) window.addEventListener('load', function () { loaded = true; });

    function finish() {
        if (finished) return;
        finished = true;
        bar.style.width = '100%'; pctEl.textContent = '100%';
        var prog = document.getElementById('sp-progress');
        var PAUSE = 560, HOLD = 1450;   // jeda di 100% -> transisi jadi "Selamat Datang" -> tahan -> burst
        setTimeout(function () { if (prog) prog.classList.add('is-welcome'); }, PAUSE);
        setTimeout(function () { root.classList.add('sp-burst'); }, PAUSE + HOLD);
        setTimeout(function () {
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
            window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
            root.parentNode && root.parentNode.removeChild(root);
        }, PAUSE + HOLD + 800);
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
