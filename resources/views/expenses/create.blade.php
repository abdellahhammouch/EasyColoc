<x-app-layout>
<div class="max-w-2xl mx-auto space-y-7">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('expenses.index', $colocation) }}" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:border-primary/40 transition-colors text-stone-400 hover:text-primary">
            <span class="material-icons-round text-lg">arrow_back</span>
        </a>
        <div>
            <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase">{{ $colocation->name }}</p>
            <h1 class="text-3xl font-serif font-bold text-white">Ajouter une dépense</h1>
        </div>
    </div>

    @if($categories->isEmpty())
        <div class="glass rounded-xl p-5 flex items-center gap-4 border-yellow-500/20 bg-yellow-500/5">
            <span class="material-icons-round text-yellow-500">warning</span>
            <p class="text-yellow-400 text-sm">Aucune catégorie disponible. Demandez à l'owner d'en créer d'abord.</p>
        </div>
    @else
        <div class="glass rounded-2xl p-8">
            <form method="POST" action="{{ route('expenses.store', $colocation) }}" class="space-y-6">
                @csrf

                {{-- Title --}}
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">Titre</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="bg-white/5 border border-white/10 rounded-xl h-12 px-5 text-stone-200 placeholder-stone-600 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-all @error('title') border-red-500/50 @enderror"
                           placeholder="Ex: Courses hebdomadaires, Facture EDF...">
                    @error('title') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                </div>

                {{-- Amount + Date --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">Montant (€)</label>
                        <input type="number" name="amount" step="0.01" min="0.01" value="{{ old('amount') }}" required
                               class="bg-white/5 border border-white/10 rounded-xl h-12 px-5 text-stone-200 placeholder-stone-600 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-all @error('amount') border-red-500/50 @enderror"
                               placeholder="0.00">
                        @error('amount') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">Date</label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', now()->toDateString()) }}" required
                               class="bg-white/5 border border-white/10 rounded-xl h-12 px-5 text-stone-200 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-all @error('expense_date') border-red-500/50 @enderror">
                        @error('expense_date') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Category --}}
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">Catégorie</label>
                    <div class="relative">
                        <select name="category_id" required
                                class="w-full bg-white/5 border border-white/10 rounded-xl h-12 px-5 text-stone-200 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-all appearance-none @error('category_id') border-red-500/50 @enderror">
                            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Choisir une catégorie...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (string)old('category_id') === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <span class="material-icons-round absolute right-4 top-1/2 -translate-y-1/2 text-stone-500 pointer-events-none text-sm">expand_more</span>
                    </div>
                    @error('category_id') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                </div>

                {{-- Notes --}}
                <div class="flex flex-col gap-2">
                    <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">Notes <span class="text-stone-600 normal-case">(optionnel)</span></label>
                    <textarea name="notes" rows="3"
                              class="bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-stone-200 placeholder-stone-600 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-all resize-none @error('notes') border-red-500/50 @enderror"
                              placeholder="Détails supplémentaires...">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-400 text-xs">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                        class="w-full bg-primary text-black font-bold text-sm tracking-widest uppercase h-14 rounded-full hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20">
                    <span class="material-icons-round align-middle mr-2 text-base">save</span>
                    Enregistrer la dépense
                </button>
            </form>
        </div>
    @endif

</div>
</x-app-layout>