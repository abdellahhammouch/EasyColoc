<x-app-layout>
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-1">Mes espaces</p>
            <h1 class="text-4xl font-serif font-bold text-white">Mes Colocations</h1>
        </div>
        <a href="{{ route('colocations.create') }}"
           class="inline-flex items-center gap-2 bg-primary text-black font-bold px-6 py-3 rounded-full text-sm uppercase tracking-wider hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20">
            <span class="material-icons-round text-base">add</span>
            Créer une colocation
        </a>
    </div>

    @if(session('status'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            <span class="material-icons-round">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    @if($colocations->isEmpty())
        {{-- Empty state --}}
        <div class="glass rounded-2xl p-16 text-center">
            <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-primary/20">
                <span class="material-icons-round text-primary text-4xl">home</span>
            </div>
            <h2 class="text-xl font-bold text-white mb-2">Aucune colocation</h2>
            <p class="text-stone-500 text-sm mb-8 max-w-sm mx-auto">
                Vous ne faites partie d'aucune colocation pour le moment. Créez-en une ou attendez une invitation.
            </p>
            <a href="{{ route('colocations.create') }}"
               class="inline-flex items-center gap-2 bg-primary text-black font-bold px-8 py-3 rounded-full text-sm uppercase tracking-wider hover:bg-yellow-400 transition-all">
                <span class="material-icons-round text-base">add_home</span>
                Créer ma première colocation
            </a>
        </div>

    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($colocations as $colocation)
                <a href="{{ route('colocations.show', $colocation) }}"
                   class="glass rounded-2xl p-6 hover:border-primary/40 transition-all group block">

                    {{-- Top bar --}}
                    <div class="flex items-start justify-between mb-5">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                            <span class="material-icons-round text-primary text-xl">home</span>
                        </div>
                        <span class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full
                            {{ $colocation->status === 'active'
                                ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20'
                                : 'bg-stone-500/10 text-stone-500 border border-stone-500/20' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $colocation->status === 'active' ? 'bg-emerald-400' : 'bg-stone-500' }}"></span>
                            {{ $colocation->status }}
                        </span>
                    </div>

                    {{-- Name --}}
                    <h3 class="text-xl font-bold text-white mb-1 group-hover:text-primary transition-colors">
                        {{ $colocation->name }}
                    </h3>
                    <p class="text-stone-600 text-xs mb-5">
                        Owner : {{ $colocation->owner?->name }}
                        @if($colocation->owner_id === auth()->id())
                            <span class="text-primary ml-1">(vous)</span>
                        @endif
                    </p>

                    {{-- Members avatars --}}
                    <div class="flex items-center justify-between">
                        <div class="flex -space-x-2">
                            @foreach($colocation->users->take(4) as $member)
                                <div class="w-8 h-8 rounded-full bg-primary/15 border-2 border-bg-dark flex items-center justify-center text-primary font-bold text-xs">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                            @endforeach
                            @if($colocation->users->count() > 4)
                                <div class="w-8 h-8 rounded-full bg-white/5 border-2 border-bg-dark flex items-center justify-center text-stone-500 font-bold text-[10px]">
                                    +{{ $colocation->users->count() - 4 }}
                                </div>
                            @endif
                        </div>
                        <span class="text-stone-600 text-xs">
                            {{ $colocation->users->count() }} membre{{ $colocation->users->count() > 1 ? 's' : '' }}
                        </span>
                    </div>

                    {{-- Arrow --}}
                    <div class="mt-5 pt-4 border-t border-white/5 flex items-center justify-between">
                        <span class="text-stone-600 text-xs">
                            {{ $colocation->created_at->diffForHumans() }}
                        </span>
                        <span class="material-icons-round text-stone-600 group-hover:text-primary transition-colors">
                            arrow_forward
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

</div>
</x-app-layout>