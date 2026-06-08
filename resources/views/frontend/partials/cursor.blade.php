{{-- GlassOrbCursor — port vanilla JS dari frontend/src/GlassOrbCursor.tsx --}}
<div id="gorb" style="position:fixed;inset:0;z-index:99999;pointer-events:none;opacity:0;transition:opacity .3s">
    <svg width="0" height="0" style="position:absolute">
        <defs>
            <linearGradient id="gcur-def" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="rgba(190,225,255,0.92)" />
                <stop offset="35%" stop-color="rgba(120,180,255,0.75)" />
                <stop offset="70%" stop-color="rgba(80,150,240,0.60)" />
                <stop offset="100%" stop-color="rgba(50,120,220,0.45)" />
            </linearGradient>
            <linearGradient id="gcur-hov" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="rgba(215,240,255,0.95)" />
                <stop offset="35%" stop-color="rgba(140,210,255,0.85)" />
                <stop offset="70%" stop-color="rgba(80,180,255,0.70)" />
                <stop offset="100%" stop-color="rgba(40,140,240,0.55)" />
            </linearGradient>
            <linearGradient id="gcur-clk" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="rgba(225,205,255,0.95)" />
                <stop offset="35%" stop-color="rgba(170,130,255,0.85)" />
                <stop offset="70%" stop-color="rgba(130,100,240,0.70)" />
                <stop offset="100%" stop-color="rgba(100,80,220,0.50)" />
            </linearGradient>
            <linearGradient id="gcur-trail" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="rgba(170,210,255,0.6)" />
                <stop offset="50%" stop-color="rgba(110,170,250,0.4)" />
                <stop offset="100%" stop-color="rgba(70,140,230,0.2)" />
            </linearGradient>
            <linearGradient id="gcur-hi" x1="30%" y1="0%" x2="70%" y2="60%">
                <stop offset="0%" stop-color="rgba(255,255,255,0.85)" />
                <stop offset="100%" stop-color="rgba(255,255,255,0)" />
            </linearGradient>
            <filter id="gcur-glow" x="-40%" y="-40%" width="180%" height="180%">
                <feGaussianBlur in="SourceGraphic" stdDeviation="1.5" result="blur" />
                <feMerge><feMergeNode in="blur" /><feMergeNode in="SourceGraphic" /></feMerge>
            </filter>
            <filter id="gcur-shd" x="-20%" y="-10%" width="150%" height="150%">
                <feDropShadow dx="1" dy="2" stdDeviation="2" flood-color="rgba(60,130,240,0.35)" />
            </filter>
        </defs>
    </svg>

    <div id="gorb-trails"></div>

    <div id="gorb-main" style="position:fixed;top:0;left:0;will-change:transform">
        <svg id="gorb-arrow" width="36" height="42" viewBox="0 0 28 36"
            style="filter:url(#gcur-shd);transition:transform .25s cubic-bezier(0.22,1,0.36,1),opacity .2s ease;position:absolute;top:0;left:0">
            <path id="gorb-arrow-path" d="M 4 0 L 4 28 L 10.5 21.5 L 17 32 L 21 30 L 14.5 19.5 L 23 18 Z"
                fill="url(#gcur-def)" stroke="rgba(255,255,255,0.6)" stroke-width="1.2" stroke-linejoin="round" filter="url(#gcur-glow)" />
            <path d="M 6 3 L 6 16 L 10 12 L 14 12 Z" fill="url(#gcur-hi)" opacity="0.7" />
            <circle cx="8" cy="6" r="1.5" fill="rgba(255,255,255,0.8)" />
        </svg>
        <svg id="gorb-hand" width="38" height="38" viewBox="2 -1 30 32"
            style="filter:url(#gcur-shd);transition:transform .25s cubic-bezier(0.22,1,0.36,1),opacity .2s ease;opacity:0;position:absolute;top:0;left:0">
            <path id="gorb-hand-path" d="M 14 2 C 14 0.5 16 0.5 16 2 L 16 14 M 18 4 C 18 2.5 20 2.5 20 4 L 20 14 M 22 5 C 22 3.5 24 3.5 24 5 L 24 14 M 10 10 L 10 2 C 10 0.5 12 0.5 12 2 L 12 18 L 8 14 C 6.5 12.5 5 13.5 6 15 L 12 24 C 14 27 16 28 20 28 C 25 28 28 25 28 20 L 28 14 C 28 12.5 26 12.5 26 14 L 26 14 L 24 14 L 22 14 L 20 14 L 18 14 L 16 14 L 14 14 Z"
                fill="url(#gcur-def)" stroke="rgba(255,255,255,0.6)" stroke-width="1" stroke-linejoin="round" stroke-linecap="round" filter="url(#gcur-glow)" />
            <ellipse cx="11" cy="4" rx="0.6" ry="2" fill="rgba(255,255,255,0.6)" />
            <ellipse cx="15" cy="4" rx="0.6" ry="1.8" fill="rgba(255,255,255,0.55)" />
            <ellipse cx="19" cy="5" rx="0.6" ry="1.6" fill="rgba(255,255,255,0.5)" />
            <ellipse cx="23" cy="6" rx="0.6" ry="1.4" fill="rgba(255,255,255,0.45)" />
            <ellipse cx="18" cy="20" rx="4" ry="3" fill="rgba(255,255,255,0.15)" />
        </svg>
    </div>
</div>

<script>
(function () {
    if (window.matchMedia && window.matchMedia('(pointer: coarse)').matches) return; // skip di layar sentuh

    var TRAIL_COUNT = 8;
    var ARROW = 'M 4 0 L 4 28 L 10.5 21.5 L 17 32 L 21 30 L 14.5 19.5 L 23 18 Z';
    var root = document.getElementById('gorb');
    var main = document.getElementById('gorb-main');
    var arrow = document.getElementById('gorb-arrow');
    var hand = document.getElementById('gorb-hand');
    var arrowPath = document.getElementById('gorb-arrow-path');
    var handPath = document.getElementById('gorb-hand-path');
    var trailWrap = document.getElementById('gorb-trails');

    var pos = { x: -100, y: -100 };
    var trailPos = [], trailEls = [];
    for (var i = 0; i < TRAIL_COUNT; i++) {
        trailPos.push({ x: -100, y: -100 });
        var d = document.createElement('div');
        d.style.cssText = 'position:fixed;top:0;left:0;will-change:transform,opacity';
        d.innerHTML = '<svg width="28" height="34" viewBox="0 0 28 36"><path d="' + ARROW + '" fill="url(#gcur-trail)" stroke="rgba(180,215,255,0.25)" stroke-width="0.8" stroke-linejoin="round" /></svg>';
        trailWrap.appendChild(d);
        trailEls.push(d);
    }

    var isPointer = false, isPressed = false, visible = false, overMap = false;

    function applyState() {
        var grad = isPressed ? 'gcur-clk' : (isPointer ? 'gcur-hov' : 'gcur-def');
        arrowPath.setAttribute('fill', 'url(#' + grad + ')');
        handPath.setAttribute('fill', 'url(#' + grad + ')');
        arrow.style.opacity = isPointer ? '0' : '1';
        arrow.style.transform = 'scale(' + (isPressed ? 0.88 : 1) + ')';
        hand.style.opacity = isPointer ? '1' : '0';
        hand.style.transform = 'scale(' + (isPressed ? 0.85 : 1) + ') translate(-4px, -2px)';
        root.style.opacity = (visible && !overMap) ? '1' : '0'; // sembunyikan orb di atas peta Mapbox (pakai kursor asli)
    }

    document.addEventListener('mousemove', function (e) {
        pos.x = e.clientX; pos.y = e.clientY;
        if (!visible) { visible = true; applyState(); }
    });
    document.addEventListener('mousedown', function () { isPressed = true; applyState(); });
    document.addEventListener('mouseup', function () { isPressed = false; applyState(); });
    document.addEventListener('mouseleave', function () { visible = false; applyState(); });
    document.addEventListener('mouseenter', function () { visible = true; applyState(); });
    document.addEventListener('mouseover', function (e) {
        var el = e.target;
        var clickable = el.closest && el.closest('a, button, [role="button"], input, select, textarea, label, [data-cursor="pointer"], [onclick], .cursor-pointer, summary, [tabindex]:not([tabindex="-1"])');
        var cs = el instanceof Element ? window.getComputedStyle(el).cursor : '';
        var np = !!(clickable || cs === 'pointer');
        var om = !!(el.closest && el.closest('.mapboxgl-map')); // di atas peta Mapbox → orb disembunyikan
        if (np !== isPointer || om !== overMap) { isPointer = np; overMap = om; applyState(); }
    });

    function animate() {
        main.style.transform = 'translate(' + pos.x + 'px,' + pos.y + 'px)';
        for (var i = 0; i < TRAIL_COUNT; i++) {
            var prev = i === 0 ? pos : trailPos[i - 1];
            var t = trailPos[i];
            var ease = 0.16 - i * 0.013;
            t.x += (prev.x - t.x) * ease;
            t.y += (prev.y - t.y) * ease;
            var scale = 1 - (i / TRAIL_COUNT) * 0.55;
            var op = (1 - i / TRAIL_COUNT) * 0.45;
            trailEls[i].style.transform = 'translate(' + t.x + 'px,' + t.y + 'px) scale(' + scale + ')';
            trailEls[i].style.opacity = String(op);
        }
        requestAnimationFrame(animate);
    }
    requestAnimationFrame(animate);
})();
</script>
