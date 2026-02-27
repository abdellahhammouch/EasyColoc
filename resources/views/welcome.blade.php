<!DOCTYPE html>
<html lang="fr" class="dark scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EasyColoc — Gestion de colocation premium</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
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
                        serif:   ["Playfair Display", "serif"],
                    },
                },
            },
        };
    </script>

    <style>
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Outfit', sans-serif;
            -webkit-font-smoothing: antialiased;
            background: #1A1918;
            color: #e7e5e4;
            overflow-x: hidden;
        }

        /* ── Noise overlay ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            opacity: 0.028;
            pointer-events: none;
            z-index: 9999;
        }

        /* ── Glass ── */
        .glass {
            background: rgba(45, 43, 40, 0.5);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.07);
        }

        /* ── Text gradient ── */
        .text-gradient {
            background: linear-gradient(135deg, #ffffff 0%, #a8a29e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .text-gold-gradient {
            background: linear-gradient(135deg, #D4AF37 0%, #f0d060 50%, #b8932a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ── Animated hero orbs ── */
        .orb {
            position: absolute;
            border-radius: 9999px;
            filter: blur(80px);
            pointer-events: none;
            animation: drift 12s ease-in-out infinite;
        }
        .orb-1 { width: 600px; height: 600px; background: rgba(212,175,55,0.07); top: -200px; left: -150px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: rgba(212,175,55,0.05); top: 100px; right: -100px; animation-delay: 4s; }
        .orb-3 { width: 300px; height: 300px; background: rgba(212,175,55,0.04); bottom: 50px; left: 40%; animation-delay: 8s; }

        @keyframes drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(30px, -20px) scale(1.05); }
            66%       { transform: translate(-20px, 30px) scale(0.97); }
        }

        /* ── Scroll reveal ── */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── Feature card hover ── */
        .feature-card {
            transition: transform 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            border-color: rgba(212, 175, 55, 0.35);
            box-shadow: 0 20px 60px rgba(212, 175, 55, 0.08);
        }

        /* ── Gold line ── */
        .gold-line {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(212,175,55,0.4), transparent);
        }

        /* ── Stat card ── */
        .stat-card {
            border-top: 1px solid rgba(212,175,55,0.2);
        }

        /* ── CTA glow ── */
        .cta-glow {
            box-shadow: 0 0 40px rgba(212,175,55,0.25), 0 4px 20px rgba(0,0,0,0.4);
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 10px; }

        /* ── Nav link underline ── */
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0;
            width: 0; height: 1px;
            background: #D4AF37;
            transition: width 0.25s ease;
        }
        .nav-link:hover::after { width: 100%; }

        /* ── Floating badge ── */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }
        .floating { animation: float 4s ease-in-out infinite; }
        .floating-slow { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body>

<!-- ═══════════════════════════════════════════
     NAVIGATION
═══════════════════════════════════════════ -->
<nav class="sticky top-0 z-50 border-b border-white/5" style="background: rgba(26,25,24,0.88); backdrop-filter: blur(16px);">
    <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <!-- Logo -->
        <a href="#" class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-primary/20 rounded-lg flex items-center justify-center border border-primary/30">
                <span class="material-icons-round text-primary text-base">apartment</span>
            </div>
            <span class="font-bold tracking-tight text-white text-lg">Easy<span class="text-primary">Coloc</span></span>
        </a>

        <!-- Links -->
        <div class="hidden md:flex items-center gap-8 text-xs font-bold uppercase tracking-widest text-stone-400">
            <a href="#features"   class="nav-link hover:text-white transition-colors">Fonctionnalités</a>
            <a href="#how"        class="nav-link hover:text-white transition-colors">Comment ça marche</a>
            <a href="#pricing"    class="nav-link hover:text-white transition-colors">Tarifs</a>
        </div>

        <!-- CTAs -->
        <div class="flex items-center gap-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="text-xs font-bold uppercase tracking-widest text-stone-400 hover:text-primary transition-colors hidden sm:block">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-xs font-bold uppercase tracking-widest text-stone-400 hover:text-white transition-colors hidden sm:block">
                        Connexion
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="bg-primary text-black text-xs font-black uppercase tracking-widest px-5 py-2.5 rounded-full hover:bg-yellow-400 transition-all cta-glow">
                            Commencer
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</nav>


<!-- ═══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ -->
<section class="relative min-h-[92vh] flex items-center overflow-hidden px-6">
    <!-- Orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-2 gap-16 items-center py-24">

        <!-- Left: Copy -->
        <div class="space-y-8 z-10">
            <div class="inline-flex items-center gap-2 bg-primary/10 border border-primary/20 text-primary rounded-full px-4 py-2 text-xs font-black uppercase tracking-widest">
                <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                Gestion de colocation premium
            </div>

            <h1 class="text-6xl md:text-7xl lg:text-7xl font-black tracking-tighter leading-[0.9] uppercase">
                <span class="text-gradient">Vivez</span><br>
                <span class="text-gradient">Ensemble,</span><br>
                <span class="text-gold-gradient">Sans Stress.</span>
            </h1>

            <p class="text-stone-400 text-lg leading-relaxed max-w-md font-light">
                Partagez vos dépenses, gérez vos colocataires et suivez vos finances communes avec élégance.
            </p>

            <div class="flex flex-wrap items-center gap-4">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-3 bg-primary text-black font-black text-sm uppercase tracking-widest px-8 py-4 rounded-full hover:bg-yellow-400 transition-all cta-glow">
                        <span class="material-icons-round text-lg">rocket_launch</span>
                        Créer mon compte
                    </a>
                @endif
                <a href="#how"
                   class="inline-flex items-center gap-2 text-stone-400 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest">
                    Voir comment ça marche
                    <span class="material-icons-round text-base">arrow_forward</span>
                </a>
            </div>

            <!-- Trust badges -->
            <div class="flex flex-wrap items-center gap-6 pt-4">
                <div class="flex items-center gap-2 text-stone-500 text-xs font-bold">
                    <span class="material-icons-round text-primary text-base">verified</span>
                    100% gratuit
                </div>
                <div class="flex items-center gap-2 text-stone-500 text-xs font-bold">
                    <span class="material-icons-round text-primary text-base">lock</span>
                    Données sécurisées
                </div>
                <div class="flex items-center gap-2 text-stone-500 text-xs font-bold">
                    <span class="material-icons-round text-primary text-base">group</span>
                    Multi-colocataires
                </div>
            </div>
        </div>

        <!-- Right: Visual card -->
        <div class="hidden lg:flex justify-center items-center z-10">
            <div class="relative w-full max-w-md">

                <!-- Main card -->
                <div class="glass rounded-3xl p-8 shadow-2xl">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-stone-500 font-bold">Colocation</p>
                            <p class="text-xl font-bold text-white">La Villa des Arts</p>
                        </div>
                        <span class="flex items-center gap-1.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Active
                        </span>
                    </div>

                    <!-- Members row -->
                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex -space-x-2">
                            <div class="w-9 h-9 rounded-full bg-primary/20 border-2 border-bg-dark flex items-center justify-center text-primary font-bold text-sm">M</div>
                            <div class="w-9 h-9 rounded-full bg-blue-500/20 border-2 border-bg-dark flex items-center justify-center text-blue-400 font-bold text-sm">S</div>
                            <div class="w-9 h-9 rounded-full bg-purple-500/20 border-2 border-bg-dark flex items-center justify-center text-purple-400 font-bold text-sm">T</div>
                            <div class="w-9 h-9 rounded-full bg-rose-500/20 border-2 border-bg-dark flex items-center justify-center text-rose-400 font-bold text-sm">L</div>
                        </div>
                        <span class="text-stone-500 text-xs ml-1">4 colocataires actifs</span>
                    </div>

                    <!-- Expense entries -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between bg-white/[0.03] rounded-xl px-4 py-3 border border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                                    <span class="material-icons-round text-primary text-sm">receipt</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-stone-200">Loyer Novembre</p>
                                    <p class="text-[10px] text-stone-600">Par Marc · 02/11</p>
                                </div>
                            </div>
                            <span class="font-bold text-white">820 <span class="text-primary text-sm">€</span></span>
                        </div>
                        <div class="flex items-center justify-between bg-white/[0.03] rounded-xl px-4 py-3 border border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                                    <span class="material-icons-round text-emerald-400 text-sm">shopping_cart</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-stone-200">Courses semaine</p>
                                    <p class="text-[10px] text-stone-600">Par Sophie · 05/11</p>
                                </div>
                            </div>
                            <span class="font-bold text-white">74 <span class="text-primary text-sm">€</span></span>
                        </div>
                        <div class="flex items-center justify-between bg-white/[0.03] rounded-xl px-4 py-3 border border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                                    <span class="material-icons-round text-blue-400 text-sm">bolt</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-stone-200">Facture EDF</p>
                                    <p class="text-[10px] text-stone-600">Par Thomas · 08/11</p>
                                </div>
                            </div>
                            <span class="font-bold text-white">118 <span class="text-primary text-sm">€</span></span>
                        </div>
                    </div>

                    <!-- Balance summary -->
                    <div class="gold-line mb-5"></div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] text-stone-500 uppercase tracking-widest font-bold">Votre solde</p>
                            <p class="text-2xl font-black text-primary mt-0.5">+42.50 €</p>
                        </div>
                        <div class="bg-primary text-black text-xs font-black uppercase tracking-wider px-4 py-2 rounded-full">
                            À recevoir
                        </div>
                    </div>
                </div>

                <!-- Floating badges -->
                <div class="absolute -top-5 -right-5 floating glass rounded-2xl px-4 py-3 flex items-center gap-2 shadow-xl border border-primary/20">
                    <span class="material-icons-round text-primary text-lg">notifications</span>
                    <div>
                        <p class="text-white text-xs font-bold">Sophie a payé</p>
                        <p class="text-stone-500 text-[10px]">145 € · à l'instant</p>
                    </div>
                </div>

                <div class="absolute -bottom-5 -left-5 floating-slow glass rounded-2xl px-4 py-3 flex items-center gap-2 shadow-xl border border-emerald-500/20">
                    <span class="material-icons-round text-emerald-400 text-lg">check_circle</span>
                    <div>
                        <p class="text-white text-xs font-bold">Dette soldée</p>
                        <p class="text-stone-500 text-[10px]">Équilibre parfait</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom fade -->
    <div class="absolute bottom-0 left-0 right-0 h-32 pointer-events-none"
         style="background: linear-gradient(to bottom, transparent, #1A1918)"></div>
</section>


<!-- ═══════════════════════════════════════════
     STATS BAR
═══════════════════════════════════════════ -->
<section class="py-6 border-y border-white/5" style="background: rgba(36,35,33,0.4);">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-0 divide-x divide-white/5">
        <div class="px-8 py-4 text-center">
            <p class="text-3xl font-black text-gold-gradient">100%</p>
            <p class="text-[10px] uppercase tracking-widest text-stone-500 font-bold mt-1">Gratuit</p>
        </div>
        <div class="px-8 py-4 text-center">
            <p class="text-3xl font-black text-white">∞</p>
            <p class="text-[10px] uppercase tracking-widest text-stone-500 font-bold mt-1">Colocataires</p>
        </div>
        <div class="px-8 py-4 text-center">
            <p class="text-3xl font-black text-white">Auto</p>
            <p class="text-[10px] uppercase tracking-widest text-stone-500 font-bold mt-1">Calcul des dettes</p>
        </div>
        <div class="px-8 py-4 text-center">
            <p class="text-3xl font-black text-white">5<span class="text-primary">min</span></p>
            <p class="text-[10px] uppercase tracking-widest text-stone-500 font-bold mt-1">Pour démarrer</p>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════
     FEATURES
═══════════════════════════════════════════ -->
<section id="features" class="py-28 px-6">
    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-20 reveal">
            <p class="text-primary text-xs font-black uppercase tracking-[0.3em] mb-4">Tout ce dont vous avez besoin</p>
            <h2 class="text-5xl md:text-6xl font-black tracking-tighter uppercase text-gradient">
                Fonctionnalités
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            <!-- Feature 1 -->
            <div class="glass feature-card rounded-2xl p-8 reveal">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center mb-6">
                    <span class="material-icons-round text-primary text-2xl">receipt_long</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Dépenses partagées</h3>
                <p class="text-stone-500 leading-relaxed text-sm">
                    Enregistrez chaque dépense, assignez un payeur et catégorisez automatiquement. Chaque centime est tracé.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="glass feature-card rounded-2xl p-8 reveal" style="transition-delay: 0.1s">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-6">
                    <span class="material-icons-round text-emerald-400 text-2xl">account_balance_wallet</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Calcul automatique</h3>
                <p class="text-stone-500 leading-relaxed text-sm">
                    Les balances de chaque membre se mettent à jour en temps réel. Sachez exactement qui doit combien à qui.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="glass feature-card rounded-2xl p-8 reveal" style="transition-delay: 0.2s">
                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center mb-6">
                    <span class="material-icons-round text-blue-400 text-2xl">person_add</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Invitations sécurisées</h3>
                <p class="text-stone-500 leading-relaxed text-sm">
                    Invitez vos colocataires via un lien unique et sécurisé. Ils rejoignent la colocation en un clic.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="glass feature-card rounded-2xl p-8 reveal" style="transition-delay: 0.15s">
                <div class="w-14 h-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center mb-6">
                    <span class="material-icons-round text-purple-400 text-2xl">category</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Catégories custom</h3>
                <p class="text-stone-500 leading-relaxed text-sm">
                    Créez vos propres catégories : loyer, courses, énergie, loisirs… Organisez comme vous le souhaitez.
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="glass feature-card rounded-2xl p-8 reveal" style="transition-delay: 0.25s">
                <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center mb-6">
                    <span class="material-icons-round text-rose-400 text-2xl">history</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Historique complet</h3>
                <p class="text-stone-500 leading-relaxed text-sm">
                    Filtrez par mois, par catégorie ou par membre. Tout l'historique de vos dépenses est accessible à tout moment.
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="glass feature-card rounded-2xl p-8 reveal" style="transition-delay: 0.3s">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center mb-6">
                    <span class="material-icons-round text-primary text-2xl">admin_panel_settings</span>
                </div>
                <h3 class="text-xl font-bold text-white mb-3">Espace admin</h3>
                <p class="text-stone-500 leading-relaxed text-sm">
                    Un dashboard d'administration complet pour gérer les utilisateurs, consulter les statistiques et maintenir la plateforme.
                </p>
            </div>

        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════
     HOW IT WORKS
═══════════════════════════════════════════ -->
<section id="how" class="py-28 px-6 border-t border-white/5" style="background: rgba(36,35,33,0.3);">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-20 reveal">
            <p class="text-primary text-xs font-black uppercase tracking-[0.3em] mb-4">Simple & rapide</p>
            <h2 class="text-5xl md:text-6xl font-black tracking-tighter uppercase text-gradient">
                Comment ça<br>marche ?
            </h2>
        </div>

        <div class="space-y-5">

            <!-- Step 1 -->
            <div class="glass rounded-2xl p-8 flex flex-col md:flex-row items-start md:items-center gap-6 reveal hover:border-primary/20 transition-colors">
                <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-primary text-black flex items-center justify-center font-black text-2xl shadow-lg shadow-primary/20">
                    01
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-white mb-1">Créez votre compte</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Inscription gratuite en 30 secondes. Pas de carte bancaire requise.</p>
                </div>
                <span class="material-icons-round text-primary text-3xl hidden md:block">person_add</span>
            </div>

            <!-- Arrow -->
            <div class="flex justify-center">
                <span class="material-icons-round text-stone-700 text-2xl">arrow_downward</span>
            </div>

            <!-- Step 2 -->
            <div class="glass rounded-2xl p-8 flex flex-col md:flex-row items-start md:items-center gap-6 reveal hover:border-primary/20 transition-colors">
                <div class="flex-shrink-0 w-16 h-16 rounded-2xl text-black flex items-center justify-center font-black text-2xl shadow-lg" style="background: linear-gradient(135deg, #D4AF37, #b8932a);">
                    02
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-white mb-1">Créez votre colocation</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Nommez votre colocation, créez vos catégories de dépenses et invitez vos colocataires.</p>
                </div>
                <span class="material-icons-round text-primary text-3xl hidden md:block">home</span>
            </div>

            <!-- Arrow -->
            <div class="flex justify-center">
                <span class="material-icons-round text-stone-700 text-2xl">arrow_downward</span>
            </div>

            <!-- Step 3 -->
            <div class="glass rounded-2xl p-8 flex flex-col md:flex-row items-start md:items-center gap-6 reveal hover:border-primary/20 transition-colors">
                <div class="flex-shrink-0 w-16 h-16 rounded-2xl text-black flex items-center justify-center font-black text-2xl shadow-lg" style="background: linear-gradient(135deg, #D4AF37, #f0d060);">
                    03
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-white mb-1">Enregistrez vos dépenses</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Chaque dépense est répartie équitablement. Les balances se calculent automatiquement.</p>
                </div>
                <span class="material-icons-round text-primary text-3xl hidden md:block">receipt_long</span>
            </div>

            <!-- Arrow -->
            <div class="flex justify-center">
                <span class="material-icons-round text-stone-700 text-2xl">arrow_downward</span>
            </div>

            <!-- Step 4 -->
            <div class="glass rounded-2xl p-8 flex flex-col md:flex-row items-start md:items-center gap-6 reveal" style="border-color: rgba(212,175,55,0.2);">
                <div class="flex-shrink-0 w-16 h-16 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black text-2xl shadow-lg shadow-emerald-500/20">
                    ✓
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-white mb-1">Soldez les dettes en un clic</h3>
                    <p class="text-stone-500 text-sm leading-relaxed">Quand un membre rembourse, marquez la dette comme payée. Tout le monde repart à zéro.</p>
                </div>
                <span class="material-icons-round text-emerald-400 text-3xl hidden md:block">check_circle</span>
            </div>

        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════
     CTA
═══════════════════════════════════════════ -->
<section id="pricing" class="py-28 px-6">
    <div class="max-w-4xl mx-auto reveal">
        <div class="relative overflow-hidden rounded-3xl p-12 md:p-16 text-center"
             style="background: linear-gradient(135deg, #2D2B28 0%, #1A1918 60%); border: 1px solid rgba(212,175,55,0.2);">

            <!-- BG decoration -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/8 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-4 right-4 text-primary opacity-10">
                <span class="material-icons-round text-[120px]">euro_symbol</span>
            </div>

            <div class="relative z-10 space-y-6">
                <span class="inline-flex items-center gap-2 bg-primary/10 border border-primary/20 text-primary text-xs font-black uppercase tracking-[0.2em] px-4 py-2 rounded-full">
                    <span class="material-icons-round text-sm">star</span>
                    100% Gratuit pour toujours
                </span>

                <h2 class="text-5xl md:text-6xl font-black tracking-tighter uppercase">
                    <span class="text-gradient">Prêt à</span><br>
                    <span class="text-gold-gradient">démarrer ?</span>
                </h2>

                <p class="text-stone-400 text-lg max-w-md mx-auto leading-relaxed font-light">
                    Créez votre colocation en moins de 5 minutes. Aucune carte bancaire, aucun engagement.
                </p>

                <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-3 bg-primary text-black font-black text-sm uppercase tracking-widest px-10 py-4 rounded-full hover:bg-yellow-400 transition-all cta-glow">
                            <span class="material-icons-round text-lg">rocket_launch</span>
                            Créer mon compte gratuit
                        </a>
                    @endif
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 text-stone-400 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest border border-white/10 px-8 py-4 rounded-full hover:border-white/20">
                            J'ai déjà un compte
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ═══════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════ -->
<footer class="border-t border-white/5 py-12 px-6" style="background: rgba(26,25,24,0.8);">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-2.5">
            <div class="w-7 h-7 bg-primary/20 rounded-lg flex items-center justify-center border border-primary/30">
                <span class="material-icons-round text-primary text-sm">apartment</span>
            </div>
            <span class="font-bold tracking-tight text-stone-400 text-sm">Easy<span class="text-primary">Coloc</span></span>
        </div>

        <div class="flex items-center gap-8 text-[10px] font-black uppercase tracking-widest text-stone-600">
            <a href="#" class="hover:text-primary transition-colors">Mentions légales</a>
            <a href="#" class="hover:text-primary transition-colors">Confidentialité</a>
            <a href="#" class="hover:text-primary transition-colors">Contact</a>
        </div>

        <p class="text-[10px] text-stone-700 uppercase tracking-widest font-bold">
            © {{ date('Y') }} EasyColoc — Tous droits réservés
        </p>
    </div>
</footer>


<!-- ═══════════════════════════════════════════
     JS: Scroll reveal
═══════════════════════════════════════════ -->
<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>

</body>
</html>