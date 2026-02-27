<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-white/5" style="background: rgba(26,25,24,0.85); backdrop-filter: blur(12px);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo + Links -->
            <div class="flex items-center gap-10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary/20 rounded-lg flex items-center justify-center border border-primary/30">
                        <span class="material-icons-round text-primary text-base">apartment</span>
                    </div>
                    <span class="font-bold tracking-tight uppercase text-white">Easy<span class="text-primary">Coloc</span></span>
                </a>

                <div class="hidden sm:flex items-center gap-6 text-xs font-bold uppercase tracking-widest opacity-60">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-primary opacity-100' : 'hover:text-primary hover:opacity-100 transition-all' }}">Dashboard</a>
                    <a href="{{ route('colocations.index') ?? '#' }}" class="{{ request()->routeIs('colocations.*') ? 'text-primary opacity-100' : 'hover:text-primary hover:opacity-100 transition-all' }}">Colocations</a>
                </div>
            </div>

            <!-- Right side -->
            <div class="hidden sm:flex items-center gap-4">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold uppercase tracking-widest text-primary opacity-70 hover:opacity-100 transition-opacity">Admin</a>
                    @endif

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 bg-surf-dark px-3 py-2 rounded-full border border-white/10 text-sm hover:border-primary/40 transition-all">
                            <div class="w-6 h-6 rounded-full bg-primary/20 border border-primary/40 flex items-center justify-center">
                                <span class="text-primary font-bold text-xs">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                            <span class="text-stone-300 text-xs font-medium">{{ Auth::user()->name }}</span>
                            <span class="material-icons-round text-sm text-stone-500">expand_more</span>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                             class="absolute right-0 mt-2 w-44 rounded-xl border border-white/10 shadow-2xl py-1 z-50"
                             style="background: #2D2B28;">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-stone-300 hover:bg-white/5 hover:text-primary transition-colors">
                                <span class="material-icons-round text-base">person</span> Profil
                            </a>
                            <hr class="border-white/5 my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-400 hover:bg-red-400/10 transition-colors w-full text-left">
                                    <span class="material-icons-round text-base">logout</span> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Mobile hamburger -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="text-stone-400 hover:text-primary transition-colors">
                    <span class="material-icons-round" x-show="!open">menu</span>
                    <span class="material-icons-round" x-show="open" x-cloak>close</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" x-cloak class="sm:hidden border-t border-white/5 px-4 py-4 space-y-2" style="background: #1A1918;">
        <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-stone-300 hover:bg-white/5' }}">Dashboard</a>
        <a href="#" class="block px-4 py-3 rounded-xl text-sm font-medium text-stone-300 hover:bg-white/5">Colocations</a>
        @auth
            <hr class="border-white/5 my-2">
            <div class="px-4 py-2">
                <p class="font-semibold text-stone-200">{{ Auth::user()->name }}</p>
                <p class="text-xs text-stone-500">{{ Auth::user()->email }}</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 rounded-xl text-sm text-stone-300 hover:bg-white/5">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-3 rounded-xl text-sm text-red-400 hover:bg-red-400/10">Déconnexion</button>
            </form>
        @endauth
    </div>
</nav>