<x-app-layout>
<div class="space-y-10">

    {{-- Header --}}
    <div>
        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">Système d'administration</p>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <h1 class="text-4xl font-serif font-bold text-white">Tableau de Bord <span class="text-stone-600">Admin</span></h1>
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-2 bg-primary text-black font-bold px-6 py-3 rounded-full text-sm uppercase tracking-wider hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20">
                <span class="material-icons-round text-base">group</span>
                Gérer les utilisateurs
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="glass p-7 rounded-2xl relative overflow-hidden group hover:border-primary/30 transition-colors">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-icons-round text-9xl">people</span>
            </div>
            <p class="text-stone-500 text-xs font-bold uppercase tracking-widest mb-2">Utilisateurs Totaux</p>
            <h3 class="text-4xl font-bold text-white">{{ number_format($stats['users']) }}</h3>
            <div class="flex items-center gap-2 mt-4 text-emerald-500 text-xs font-bold">
                <span class="material-icons-round text-sm">trending_up</span>
                <span>Actifs sur la plateforme</span>
            </div>
        </div>

        <div class="glass p-7 rounded-2xl relative overflow-hidden group hover:border-red-500/30 transition-colors">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-red-500">
                <span class="material-icons-round text-9xl">gavel</span>
            </div>
            <p class="text-stone-500 text-xs font-bold uppercase tracking-widest mb-2">Utilisateurs Bannis</p>
            <h3 class="text-4xl font-bold text-white">{{ number_format($stats['banned_users']) }}</h3>
            <div class="flex items-center gap-2 mt-4 text-red-400 text-xs font-bold">
                <span class="material-icons-round text-sm">block</span>
                <span>Accès révoqué</span>
            </div>
        </div>

        <div class="glass p-7 rounded-2xl relative overflow-hidden group hover:border-primary/30 transition-colors">
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-icons-round text-9xl">home</span>
            </div>
            <p class="text-stone-500 text-xs font-bold uppercase tracking-widest mb-2">Colocations Actives</p>
            <h3 class="text-4xl font-bold text-white">{{ number_format($stats['colocations']) }}</h3>
            <div class="flex items-center gap-2 mt-4 text-primary text-xs font-bold">
                <span class="material-icons-round text-sm">auto_graph</span>
                <span>En cours</span>
            </div>
        </div>
    </div>

    {{-- Secondary stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="glass p-7 rounded-2xl flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center">
                <span class="material-icons-round text-primary text-3xl">receipt_long</span>
            </div>
            <div>
                <p class="text-stone-500 text-xs font-bold uppercase tracking-widest mb-1">Dépenses totales</p>
                <p class="text-3xl font-bold text-white">{{ number_format($stats['expenses']) }}</p>
            </div>
        </div>

        <div class="glass p-7 rounded-2xl flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
                <span class="material-icons-round text-emerald-500 text-3xl">payments</span>
            </div>
            <div>
                <p class="text-stone-500 text-xs font-bold uppercase tracking-widest mb-1">Paiements effectués</p>
                <p class="text-3xl font-bold text-white">{{ number_format($stats['payments']) }}</p>
            </div>
        </div>
    </div>

    {{-- Admin CTA Card --}}
    <div class="relative overflow-hidden rounded-3xl p-8 md:p-10" style="background: linear-gradient(135deg, #2D2B28 0%, #1A1918 100%); border: 1px solid rgba(212,175,55,0.15);">
        <div class="absolute -right-8 -bottom-8 text-primary opacity-5">
            <span class="material-icons-round text-[200px]">admin_panel_settings</span>
        </div>
        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-3">Contrôle de la plateforme</p>
        <h3 class="text-2xl font-serif font-bold text-white mb-6">Gestion des membres</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-2 bg-primary text-black font-bold px-6 py-3 rounded-full text-sm hover:bg-yellow-400 transition-all">
                <span class="material-icons-round text-base">group</span> Utilisateurs
            </a>
        </div>
    </div>

</div>
</x-app-layout>