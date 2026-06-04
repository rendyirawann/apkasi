<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'HUT Ke-26 APKASI & Deli Serdang Ke-80 — 1-3 Juli 2026')</title>
    <meta name="description" content="@yield('description', 'HUT Ke-26 APKASI & HUT Ke-80 Kabupaten Deli Serdang — Bersinergi Membangun Daerah, Memperkuat Otonomi Untuk Indonesia Maju. 1–3 Juli 2026.')" />
    <link rel="icon" type="image/png" href="{{ asset('logos/apkasi-logo.png') }}" />

    {{-- Fonts (sama dengan React index.html) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

    {{-- Tailwind via CDN + config 'apkasi' (identik dengan frontend/tailwind.config.js) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Inter"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                        display: ['"Outfit"', '"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        apkasi: {
                            dark: '#1f2a1d', medium: '#2d3a2a', hover: '#2a3827', body: '#4b5b47',
                            heading: '#336443', accent: '#85AB8B', cta: '#3d5638', ctahov: '#2d4228',
                            gold: '#D4AF37', goldlt: '#FFE07D', cream: '#fdfcf8', leaf: '#e8f0ea',
                        },
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.7s ease-out forwards',
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeUp: { '0%': { opacity: '0', transform: 'translateY(24px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                    },
                },
            },
        };
    </script>

    {{-- Lucide icons (versi sama dgn lucide-react di SPA) --}}
    <script src="https://unpkg.com/lucide@0.344.0/dist/umd/lucide.min.js"></script>

    {{-- CSS global (port dari frontend/src/index.css) --}}
    <style>
        html { scroll-behavior: smooth; }
        html, body { height: 100%; margin: 0;
            font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; cursor: none; }
        [id] { scroll-margin-top: 80px; }

        /* Premium scrollbar */
        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: linear-gradient(180deg, #f5f2eb 0%, #e8f0ea 50%, #f5f2eb 100%); border-left: 1px solid rgba(133,171,139,.15); }
        ::-webkit-scrollbar-thumb { background: linear-gradient(180deg, #85AB8B 0%, #5d8c65 25%, #336443 50%, #5d8c65 75%, #D4AF37 100%); border-radius: 100px; border: 2.5px solid rgba(253,252,248,.8); box-shadow: inset 0 0 6px rgba(255,255,255,.3); transition: background .3s; }
        ::-webkit-scrollbar-thumb:hover { background: linear-gradient(180deg, #6a9470 0%, #4a7a52 25%, #2d4228 50%, #4a7a52 75%, #c5a028 100%); border: 2px solid rgba(253,252,248,.6); }
        ::-webkit-scrollbar-corner { background: #f5f2eb; }
        * { scrollbar-width: thin; scrollbar-color: #5d8c65 #f5f2eb; }

        /* Sembunyikan kursor default (diganti GlassOrbCursor) */
        *, *::before, *::after { cursor: none !important; }
    </style>

    @stack('head')
</head>
<body class="bg-apkasi-cream">

    @include('frontend.partials.cursor')

    @yield('content')

    <script>
        // Render semua ikon lucide statis
        if (window.lucide) lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
