<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EasyColoc') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#D4AF37",
                        "bg-dark": "#1A1918",
                        "card-dark": "#242321",
                    },
                    fontFamily: {
                        sans: ["Outfit", "sans-serif"],
                        serif: ["Playfair Display", "serif"],
                    },
                    borderRadius: { DEFAULT: "0.75rem", xl: "1.5rem", "2xl": "2rem", full: "9999px" },
                },
            },
        };
    </script>

    <style>
        body { font-family: 'Outfit', sans-serif; -webkit-font-smoothing: antialiased; }
        .glass-form { background: rgba(36,35,33,0.7); backdrop-filter: blur(20px); border: 1px solid rgba(212,175,55,0.12); }
        .noise { position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;opacity:0.03;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");z-index:9999; }
        .input-field { background: rgba(255,255,255,0.04); border: 1px solid rgba(212,175,55,0.15); border-radius: 9999px; height: 3.25rem; padding: 0 1.5rem; color: #e7e5e4; width: 100%; transition: border-color 0.2s; outline: none; }
        .input-field:focus { border-color: #D4AF37; box-shadow: 0 0 0 1px rgba(212,175,55,0.3); }
        .input-field::placeholder { color: rgba(255,255,255,0.2); }
    </style>
</head>
<body class="bg-bg-dark text-stone-200 min-h-screen">
    <div class="noise"></div>

    <!-- Ambient glow -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>

    <!-- Header -->
    <header class="flex items-center justify-between px-6 py-5 md:px-20 border-b border-primary/10">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-primary/20 rounded-lg flex items-center justify-center border border-primary/30">
                <span class="material-symbols-outlined text-primary text-lg">architecture</span>
            </div>
            <a href="/" class="text-lg font-bold tracking-tight uppercase">Easy<span class="text-primary">Coloc</span></a>
        </div>
        <div class="flex items-center gap-4 text-sm text-stone-400">
            <a href="{{ route('login') }}" class="hover:text-primary transition-colors">Connexion</a>
            <a href="{{ route('register') }}" class="bg-primary/10 border border-primary/20 text-primary hover:bg-primary hover:text-bg-dark transition-all rounded-full px-5 py-2 font-bold">Inscription</a>
        </div>
    </header>

    <div class="min-h-[calc(100vh-72px)] flex items-center justify-center px-4 py-12">
        {{ $slot }}
    </div>
</body>
</html>