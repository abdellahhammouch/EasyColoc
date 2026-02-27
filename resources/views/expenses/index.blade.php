<x-app-layout>
<div class="space-y-7">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('colocations.show', $colocation) }}" class="text-stone-600 hover:text-primary transition-colors">
                    <span class="material-icons-round text-lg">arrow_back</span>
                </a>
                <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase">{{ $colocation->name }}</p>
            </div>
            <h1 class="text-3xl font-serif font-bold text-white">Dépenses</h1>
        </div>
        @if(!$categories->isEmpty())
            <a href="{{ route('expenses.create', $colocation) }}"
               class="inline-flex items-center gap-2 bg-primary text-black font-bold px-6 py-3 rounded-full text-sm uppercase tracking-wider hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20">
                <span class="material-icons-round text-base">add</span>
                Ajouter une dépense
            </a>
        @endif
    </div>

    @if(session('status'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            <span class="material-icons-round">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    @if($categories->isEmpty())
        <div class="glass rounded-xl p-5 flex items-center gap-4 border-yellow-500/20 bg-yellow-500/5">
            <span class="material-icons-round text-yellow-500">warning</span>
            <p class="text-yellow-400 text-sm">L'owner doit créer des catégories avant d'ajouter des dépenses.</p>
        </div>
    @endif

    {{-- Filter --}}
    <div class="glass rounded-xl p-5">
        <form method="GET" action="{{ route('expenses.index', $colocation) }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-stone-500 block mb-2">Filtrer par mois</label>
                <input type="month" name="month" value="{{ request('month') }}"
                       class="bg-white/5 border border-white/10 rounded-xl h-11 px-4 text-stone-300 text-sm focus:border-primary outline-none transition-all">
            </div>
            <button type="submit" class="h-11 px-5 bg-primary/10 text-primary border border-primary/20 rounded-xl text-sm font-bold hover:bg-primary/20 transition-colors">
                Filtrer
            </button>
            @if(request('month'))
                <a href="{{ route('expenses.index', $colocation) }}" class="h-11 px-5 flex items-center bg-white/5 text-stone-400 border border-white/10 rounded-xl text-sm hover:bg-white/10 transition-colors">
                    Réinitialiser
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-white/5" style="background: rgba(255,255,255,0.02);">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-stone-500">Date</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-stone-500">Titre</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-stone-500 hidden md:table-cell">Catégorie</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-stone-500 hidden md:table-cell">Payeur</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-stone-500 text-right">Montant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @forelse($expenses as $e)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-6 py-4 text-stone-500 text-sm">
                                {{ \Illuminate\Support\Carbon::parse($e->expense_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-stone-200 text-sm group-hover:text-primary transition-colors">{{ $e->title }}</p>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                @if($e->category)
                                    <span class="bg-primary/10 text-primary text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $e->category->name }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <span class="text-stone-400 text-sm">{{ $e->payer?->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-white">{{ number_format($e->amount, 2) }}</span>
                                <span class="text-primary text-sm"> €</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-stone-600">
                                <span class="material-icons-round text-5xl mb-3 block">receipt_long</span>
                                <p class="text-sm">Aucune dépense trouvée.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($expenses->hasPages())
            <div class="px-6 py-4 border-t border-white/5" style="background: rgba(255,255,255,0.01);">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>

</div>
</x-app-layout>