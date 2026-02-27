<x-app-layout>
<div class="space-y-10">

    {{-- Hero Header --}}
    <div class="relative py-10">
        <div class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent rounded-3xl pointer-events-none"></div>
        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-3">Tableau de bord</p>
        <h1 class="text-4xl md:text-5xl font-serif font-bold text-white mb-2">
            Bonjour, <span class="text-primary">{{ Auth::user()->name }}</span>
        </h1>
        <p class="text-stone-500 text-sm">Gérez vos colocations et dépenses partagées.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-primary/30 transition-colors">
            <div class="absolute -right-3 -bottom-3 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-icons-round text-8xl">people</span>
            </div>
            <p class="text-xs uppercase tracking-widest text-stone-500 font-bold mb-1">Colocations</p>
            <p class="text-3xl font-bold text-white">—</p>
        </div>
        <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-primary/30 transition-colors">
            <div class="absolute -right-3 -bottom-3 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-icons-round text-8xl">payments</span>
            </div>
            <p class="text-xs uppercase tracking-widest text-stone-500 font-bold mb-1">Dépenses</p>
            <p class="text-3xl font-bold text-white">—</p>
        </div>
        <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-primary/30 transition-colors">
            <div class="absolute -right-3 -bottom-3 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-icons-round text-8xl">receipt_long</span>
            </div>
            <p class="text-xs uppercase tracking-widest text-stone-500 font-bold mb-1">Paiements</p>
            <p class="text-3xl font-bold text-white">—</p>
        </div>
        <div class="glass p-6 rounded-2xl relative overflow-hidden group hover:border-primary/30 transition-colors">
            <div class="absolute -right-3 -bottom-3 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-icons-round text-8xl">account_balance_wallet</span>
            </div>
            <p class="text-xs uppercase tracking-widest text-stone-500 font-bold mb-1">Solde</p>
            <p class="text-3xl font-bold text-primary">0.00<span class="text-lg">€</span></p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div>
        <h2 class="text-xs uppercase tracking-[0.3em] font-bold opacity-50 mb-6">Actions rapides</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('colocations.index') ?? '#' }}"
               class="glass p-6 rounded-2xl flex items-center gap-5 hover:border-primary/40 transition-all group cursor-pointer">
                <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center group-hover:bg-primary transition-colors">
                    <span class="material-icons-round text-primary group-hover:text-black text-2xl transition-colors">home</span>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-widest text-stone-500">Accéder</p>
                    <p class="font-bold text-stone-200">Mes Colocations</p>
                </div>
                <span class="material-icons-round ml-auto text-stone-600 group-hover:text-primary transition-colors">chevron_right</span>
            </a>

            <a href="{{ route('colocations.create') ?? '#' }}"
               class="glass p-6 rounded-2xl flex items-center gap-5 hover:border-primary/40 transition-all group cursor-pointer">
                <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center group-hover:bg-primary transition-colors">
                    <span class="material-icons-round text-primary group-hover:text-black text-2xl transition-colors">add_home</span>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-widest text-stone-500">Nouveau</p>
                    <p class="font-bold text-stone-200">Créer une Colocation</p>
                </div>
                <span class="material-icons-round ml-auto text-stone-600 group-hover:text-primary transition-colors">chevron_right</span>
            </a>

            <a href="{{ route('profile.edit') }}"
               class="glass p-6 rounded-2xl flex items-center gap-5 hover:border-primary/40 transition-all group cursor-pointer">
                <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center group-hover:bg-primary transition-colors">
                    <span class="material-icons-round text-primary group-hover:text-black text-2xl transition-colors">manage_accounts</span>
                </div>
                <div>
                    <p class="text-[10px] uppercase font-bold tracking-widest text-stone-500">Modifier</p>
                    <p class="font-bold text-stone-200">Mon Profil</p>
                </div>
                <span class="material-icons-round ml-auto text-stone-600 group-hover:text-primary transition-colors">chevron_right</span>
            </a>
        </div>
    </div>

</div>
</x-app-layout>