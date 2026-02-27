<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EasyColoc') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#D4AF37",
                        "bg-dark":   "#1A1918",
                        "card-dark": "#242321",
                        "surf-dark": "#2D2B28",
                    },
                    fontFamily: {
                        sans:    ["Outfit", "sans-serif"],
                        display: ["Outfit", "sans-serif"],
                        serif:   ["Playfair Display", "serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.75rem",
                        xl: "1.5rem",
                        "2xl": "2rem",
                        "3xl": "2.5rem",
                    },
                },
            },
        };
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; -webkit-font-smoothing: antialiased; }
        .glass { background: rgba(45,43,40,0.55); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.06); }
        .text-gradient { background: linear-gradient(to right,#fff,#a1a1aa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .gold-glow { box-shadow: 0 0 24px rgba(212,175,55,0.25); }
        .noise { position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;opacity:0.025;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");z-index:9999; }
        ::-webkit-scrollbar { width: 4px; } ::-webkit-scrollbar-track { background: transparent; } ::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 10px; }
    </style>
</head>
<body class="bg-bg-dark text-stone-200 min-h-screen">
    <div class="noise"></div>

    @include('layouts.navigation')

    @isset($header)
        <header class="py-6 px-6 max-w-7xl mx-auto">
            {{ $header }}
        </header>
    @endisset

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-24">
        {{ $slot }}
    </main>
</body>
</html>