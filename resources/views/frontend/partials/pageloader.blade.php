{{-- PageLoader — port vanilla JS dari frontend/src/PageLoader.tsx --}}
{{-- Loader ringkas untuk perpindahan ke halaman dalam (peta, agenda, dll). Beda dari SplashLoader (tanpa tile-burst). --}}
<div id="pageloader" class="apk-ploader in" aria-hidden="true">
    <style>
        .apk-ploader{position:fixed;inset:0;z-index:99400;display:flex;align-items:center;justify-content:center;font-family:'Plus Jakarta Sans',sans-serif;opacity:0;transition:opacity .3s ease;background:radial-gradient(120% 110% at 50% 35%,#1a4a2e,#0b2014 60%,#06120b)}
        .apk-ploader.in{opacity:1}
        .apk-ploader.out{opacity:0}
        .apk-ploader .badge{position:relative;width:104px;height:104px;display:flex;align-items:center;justify-content:center;transform:scale(.85);opacity:0;transition:transform .35s cubic-bezier(.2,.8,.2,1),opacity .35s ease}
        .apk-ploader.in .badge{transform:none;opacity:1}
        .apk-ploader .ring{position:absolute;inset:0;border-radius:50%;animation:apk-plspin 1s linear infinite;background:conic-gradient(from 0deg,transparent 0 60%,#e1b23c 80%,#7fc88a 100%);-webkit-mask:radial-gradient(farthest-side,transparent calc(100% - 4px),#000 calc(100% - 3px));mask:radial-gradient(farthest-side,transparent calc(100% - 4px),#000 calc(100% - 3px))}
        @keyframes apk-plspin{to{transform:rotate(360deg)}}
        .apk-ploader .badge img{width:62px;height:auto;animation:apk-plpulse 1.6s ease-in-out infinite}
        @keyframes apk-plpulse{0%,100%{transform:scale(1)}50%{transform:scale(1.06)}}
        .apk-ploader .lbl{position:absolute;top:calc(50% + 78px);left:0;right:0;text-align:center;font-size:11px;letter-spacing:.22em;text-transform:uppercase;color:rgba(255,255,255,.55);font-weight:600}
        @media(prefers-reduced-motion:reduce){.apk-ploader .ring,.apk-ploader .badge img{animation:none}}
    </style>
    <div class="badge">
        <div class="ring"></div>
        <img src="{{ asset('logos/logo_hut26.webp') }}" alt="" />
    </div>
    <div class="lbl">Memuat</div>
</div>

<script>
(function () {
    var el = document.getElementById('pageloader');
    if (!el) return;

    // Mulai dari atas & kunci scroll selama loader (mencegah posisi ter-geser saat peta/aset dimuat).
    try { if ('scrollRestoration' in history) history.scrollRestoration = 'manual'; } catch (e) {}
    window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
    document.documentElement.style.overflow = 'hidden';
    document.body.style.overflow = 'hidden';

    var MIN = 700, start = performance.now();
    // Tidak menunggu window 'load' penuh (peta/tiles bisa lama) — cukup durasi minimal.
    function tick() {
        if (performance.now() - start >= MIN) {
            el.classList.remove('in');
            el.classList.add('out');
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
            var h = window.location.hash;
            var tgt = (h && h.length > 1) ? document.getElementById(h.slice(1)) : null;
            if (tgt) tgt.scrollIntoView({ behavior: 'instant', block: 'start' });
            else window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
            setTimeout(function () { el.parentNode && el.parentNode.removeChild(el); }, 320);
            return;
        }
        requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
})();
</script>
