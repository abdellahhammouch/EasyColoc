<x-app-layout>
<div class="max-w-2xl mx-auto space-y-7">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('colocations.show', $colocation) }}" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:border-primary/40 transition-colors text-stone-400 hover:text-primary">
                <span class="material-icons-round text-lg">arrow_back</span>
            </a>
            <div>
                <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase">{{ $colocation->name }}</p>
                <h1 class="text-3xl font-serif font-bold text-white">Catégories</h1>
            </div>
        </div>
    </div>

    @if(session('status'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            <span class="material-icons-round">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    {{-- Add Category --}}
    <div class="glass rounded-2xl p-7">
        <h2 class="font-bold text-white mb-5 flex items-center gap-2">
            <span class="material-icons-round text-primary">add_circle</span>
            Ajouter une catégorie
        </h2>
        <form method="POST" action="{{ route('categories.store', $colocation) }}" class="flex gap-3">
            @csrf
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="flex-1 bg-white/5 border border-white/10 rounded-xl h-12 px-5 text-stone-200 placeholder-stone-600 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-all @error('name') border-red-500/50 @enderror"
                   placeholder="Ex: Loyer, Courses, Énergie...">
            <button type="submit"
                    class="px-6 h-12 bg-primary text-black font-bold rounded-xl hover:bg-yellow-400 transition-all text-sm uppercase tracking-wide whitespace-nowrap">
                Ajouter
            </button>
        </form>
        @error('name')
            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Categories List --}}
    <div class="glass rounded-2xl overflow-hidden">
        <div class="px-7 py-5 border-b border-white/5">
            <h2 class="font-bold text-white flex items-center gap-2">
                <span class="material-icons-round text-primary">category</span>
                Liste des catégories
            </h2>
        </div>

        @if($categories->isEmpty())
            <div class="p-12 text-center text-stone-600">
                <span class="material-icons-round text-5xl mb-3 block">category</span>
                <p class="text-sm">Aucune catégorie. Ajoutez-en une ci-dessus.</p>
            </div>
        @else
            <div class="divide-y divide-white/[0.04]">
                @foreach($categories as $cat)
                    <div class="flex items-center gap-4 px-7 py-4 hover:bg-white/[0.02] transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                            <span class="material-icons-round text-primary text-sm">label</span>
                        </div>
                        <span class="flex-1 font-medium text-stone-200">{{ $cat->name }}</span>

                        <form method="POST" action="{{ route('categories.update', [$colocation, $cat]) }}" class="flex gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="name" class="bg-white/5 border border-white/10 rounded-xl h-9 px-4 text-stone-300 text-sm focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-all w-40" value="{{ $cat->name }}" required>
                            <button type="submit" class="h-9 px-4 bg-white/5 text-stone-400 border border-white/10 rounded-xl text-xs font-bold hover:bg-primary/10 hover:text-primary hover:border-primary/20 transition-all">
                                <span class="material-icons-round text-sm align-middle">edit</span>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
</x-app-layout>