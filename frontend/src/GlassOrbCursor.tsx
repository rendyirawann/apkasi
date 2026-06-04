import { useEffect, useRef, useState, useCallback } from 'react';

const TRAIL_COUNT = 8;

/* Classic arrow cursor path */
const ARROW_PATH = 'M 4 0 L 4 28 L 10.5 21.5 L 17 32 L 21 30 L 14.5 19.5 L 23 18 Z';

/* Pointing hand cursor path — index finger up */
const HAND_PATH = `M 14 2 C 14 0.5 16 0.5 16 2 L 16 14
  M 18 4 C 18 2.5 20 2.5 20 4 L 20 14
  M 22 5 C 22 3.5 24 3.5 24 5 L 24 14
  M 10 10 L 10 2 C 10 0.5 12 0.5 12 2 L 12 18
  L 8 14 C 6.5 12.5 5 13.5 6 15 L 12 24 C 14 27 16 28 20 28
  C 25 28 28 25 28 20 L 28 14 C 28 12.5 26 12.5 26 14 L 26 14
  L 24 14 L 22 14 L 20 14 L 18 14 L 16 14 L 14 14 Z`;

interface Trail { x: number; y: number; }

export default function GlassOrbCursor() {
  const cursorRef = useRef<HTMLDivElement>(null);
  const trailRefs = useRef<HTMLDivElement[]>([]);
  const pos = useRef({ x: -100, y: -100 });
  const trailPos = useRef<Trail[]>(
    Array.from({ length: TRAIL_COUNT }, () => ({ x: -100, y: -100 }))
  );
  const [isPointer, setIsPointer] = useState(false);
  const [isPressed, setIsPressed] = useState(false);
  const [visible, setVisible] = useState(false);
  const raf = useRef(0);

  const onMove = useCallback((e: MouseEvent) => {
    pos.current = { x: e.clientX, y: e.clientY };
    if (!visible) setVisible(true);
  }, [visible]);

  const onDown = useCallback(() => setIsPressed(true), []);
  const onUp = useCallback(() => setIsPressed(false), []);
  const onLeave = useCallback(() => setVisible(false), []);
  const onEnter = useCallback(() => setVisible(true), []);

  /* Detect clickable elements */
  useEffect(() => {
    const check = (e: MouseEvent) => {
      const el = e.target as HTMLElement;
      const clickable = el.closest(
        'a, button, [role="button"], input, select, textarea, label, ' +
        '[data-cursor="pointer"], [onclick], .cursor-pointer, ' +
        'summary, [tabindex]:not([tabindex="-1"])'
      );
      // Also check computed cursor style
      const cs = window.getComputedStyle(el).cursor;
      setIsPointer(!!(clickable || cs === 'pointer'));
    };
    document.addEventListener('mouseover', check);
    return () => document.removeEventListener('mouseover', check);
  }, []);

  useEffect(() => {
    document.addEventListener('mousemove', onMove);
    document.addEventListener('mousedown', onDown);
    document.addEventListener('mouseup', onUp);
    document.addEventListener('mouseleave', onLeave);
    document.addEventListener('mouseenter', onEnter);
    return () => {
      document.removeEventListener('mousemove', onMove);
      document.removeEventListener('mousedown', onDown);
      document.removeEventListener('mouseup', onUp);
      document.removeEventListener('mouseleave', onLeave);
      document.removeEventListener('mouseenter', onEnter);
    };
  }, [onMove, onDown, onUp, onLeave, onEnter]);

  useEffect(() => {
    const animate = () => {
      if (cursorRef.current) {
        cursorRef.current.style.transform = `translate(${pos.current.x}px, ${pos.current.y}px)`;
      }
      for (let i = 0; i < TRAIL_COUNT; i++) {
        const prev = i === 0 ? pos.current : trailPos.current[i - 1];
        const t = trailPos.current[i];
        const ease = 0.16 - i * 0.013;
        t.x += (prev.x - t.x) * ease;
        t.y += (prev.y - t.y) * ease;
        const el = trailRefs.current[i];
        if (el) {
          const scale = 1 - (i / TRAIL_COUNT) * 0.55;
          const opacity = (1 - i / TRAIL_COUNT) * 0.45;
          el.style.transform = `translate(${t.x}px, ${t.y}px) scale(${scale})`;
          el.style.opacity = String(opacity);
        }
      }
      raf.current = requestAnimationFrame(animate);
    };
    raf.current = requestAnimationFrame(animate);
    return () => cancelAnimationFrame(raf.current);
  }, []);

  const cursorScale = isPressed ? 0.88 : 1;
  const activeGrad = isPressed ? 'gcur-clk' : isPointer ? 'gcur-hov' : 'gcur-def';

  return (
    <div style={{ position: 'fixed', inset: 0, zIndex: 99999, pointerEvents: 'none', opacity: visible ? 1 : 0, transition: 'opacity 0.3s' }}>
      {/* SVG defs */}
      <svg width="0" height="0" style={{ position: 'absolute' }}>
        <defs>
          {/* Default arrow gradient */}
          <linearGradient id="gcur-def" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stopColor="rgba(190,225,255,0.92)" />
            <stop offset="35%" stopColor="rgba(120,180,255,0.75)" />
            <stop offset="70%" stopColor="rgba(80,150,240,0.60)" />
            <stop offset="100%" stopColor="rgba(50,120,220,0.45)" />
          </linearGradient>
          {/* Hover hand gradient — brighter cyan */}
          <linearGradient id="gcur-hov" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stopColor="rgba(215,240,255,0.95)" />
            <stop offset="35%" stopColor="rgba(140,210,255,0.85)" />
            <stop offset="70%" stopColor="rgba(80,180,255,0.70)" />
            <stop offset="100%" stopColor="rgba(40,140,240,0.55)" />
          </linearGradient>
          {/* Click gradient — purple shift */}
          <linearGradient id="gcur-clk" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stopColor="rgba(225,205,255,0.95)" />
            <stop offset="35%" stopColor="rgba(170,130,255,0.85)" />
            <stop offset="70%" stopColor="rgba(130,100,240,0.70)" />
            <stop offset="100%" stopColor="rgba(100,80,220,0.50)" />
          </linearGradient>
          {/* Trail gradient */}
          <linearGradient id="gcur-trail" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stopColor="rgba(170,210,255,0.6)" />
            <stop offset="50%" stopColor="rgba(110,170,250,0.4)" />
            <stop offset="100%" stopColor="rgba(70,140,230,0.2)" />
          </linearGradient>
          {/* Highlight */}
          <linearGradient id="gcur-hi" x1="30%" y1="0%" x2="70%" y2="60%">
            <stop offset="0%" stopColor="rgba(255,255,255,0.85)" />
            <stop offset="100%" stopColor="rgba(255,255,255,0)" />
          </linearGradient>
          <filter id="gcur-glow" x="-40%" y="-40%" width="180%" height="180%">
            <feGaussianBlur in="SourceGraphic" stdDeviation="1.5" result="blur" />
            <feMerge><feMergeNode in="blur" /><feMergeNode in="SourceGraphic" /></feMerge>
          </filter>
          <filter id="gcur-shd" x="-20%" y="-10%" width="150%" height="150%">
            <feDropShadow dx="1" dy="2" stdDeviation="2" floodColor="rgba(60,130,240,0.35)" />
          </filter>
        </defs>
      </svg>

      {/* Trail cursors — arrow shaped (always arrow for trails) */}
      {Array.from({ length: TRAIL_COUNT }).map((_, i) => (
        <div
          key={i}
          ref={(el) => { if (el) trailRefs.current[i] = el; }}
          style={{ position: 'fixed', top: 0, left: 0, willChange: 'transform, opacity' }}
        >
          <svg width="28" height="34" viewBox="0 0 28 36">
            <path
              d={ARROW_PATH}
              fill="url(#gcur-trail)"
              stroke="rgba(180,215,255,0.25)"
              strokeWidth="0.8"
              strokeLinejoin="round"
            />
          </svg>
        </div>
      ))}

      {/* Main cursor — switches between arrow and hand */}
      <div
        ref={cursorRef}
        style={{ position: 'fixed', top: 0, left: 0, willChange: 'transform' }}
      >
        {/* Arrow cursor (default) */}
        <svg
          width="36" height="42" viewBox="0 0 28 36"
          style={{
            filter: 'url(#gcur-shd)',
            transition: 'transform 0.25s cubic-bezier(0.22,1,0.36,1), opacity 0.2s ease',
            transform: `scale(${cursorScale})`,
            opacity: isPointer ? 0 : 1,
            position: 'absolute',
            top: 0, left: 0,
          }}
        >
          <path
            d={ARROW_PATH}
            fill={`url(#${activeGrad})`}
            stroke="rgba(255,255,255,0.6)"
            strokeWidth="1.2"
            strokeLinejoin="round"
            filter="url(#gcur-glow)"
          />
          <path d="M 6 3 L 6 16 L 10 12 L 14 12 Z" fill="url(#gcur-hi)" opacity="0.7" />
          <circle cx="8" cy="6" r="1.5" fill="rgba(255,255,255,0.8)" />
        </svg>

        {/* Hand cursor (hover/pointer) */}
        <svg
          width="38" height="38" viewBox="2 -1 30 32"
          style={{
            filter: 'url(#gcur-shd)',
            transition: 'transform 0.25s cubic-bezier(0.22,1,0.36,1), opacity 0.2s ease',
            transform: `scale(${isPressed ? 0.85 : 1}) translate(-4px, -2px)`,
            opacity: isPointer ? 1 : 0,
            position: 'absolute',
            top: 0, left: 0,
          }}
        >
          <path
            d={HAND_PATH}
            fill={`url(#${activeGrad})`}
            stroke="rgba(255,255,255,0.6)"
            strokeWidth="1"
            strokeLinejoin="round"
            strokeLinecap="round"
            filter="url(#gcur-glow)"
          />
          {/* Finger highlights */}
          <ellipse cx="11" cy="4" rx="0.6" ry="2" fill="rgba(255,255,255,0.6)" />
          <ellipse cx="15" cy="4" rx="0.6" ry="1.8" fill="rgba(255,255,255,0.55)" />
          <ellipse cx="19" cy="5" rx="0.6" ry="1.6" fill="rgba(255,255,255,0.5)" />
          <ellipse cx="23" cy="6" rx="0.6" ry="1.4" fill="rgba(255,255,255,0.45)" />
          {/* Palm highlight */}
          <ellipse cx="18" cy="20" rx="4" ry="3" fill="rgba(255,255,255,0.15)" />
        </svg>
      </div>
    </div>
  );
}
