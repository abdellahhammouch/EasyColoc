
Copier

<x-app-layout>
<div class="max-w-xl mx-auto space-y-8">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ Route::has('colocations.index') ? route('colocations.index') : '#' }}" class="w-10 h-10 rounded-full glass flex items-center justify-center hover:border-primary/40 transition-colors text-stone-400 hover:text-primary">
            <span class="material-icons-round text-lg">arrow_back</span>
        </a>
        <div>
            <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase">Nouvelle</p>
            <h1 class="text-3xl font-serif font-bold text-white">Créer une Colocation</h1>
        </div>
    </div>

    {{-- Errors --}}
    @if($errors->has('colocation'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
            <span class="material-icons-round">error</span>
            {{ $errors->first('colocation') }}
        </div>
    @endif

    {{-- Form Card --}}
    <div class="glass rounded-2xl p-8 space-y-6">
        <form method="POST" action="{{ route('colocations.store') }}" class="space-y-6">
            @csrf

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-1">Nom de la colocation</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="bg-white/5 border border-white/10 rounded-xl h-14 px-5 text-stone-200 placeholder-stone-600 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-all @error('name') border-red-500/50 @enderror"
                       placeholder="Ex: La Villa des Arts, Le Loft Haussmannien...">
                @error('name')
                    <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-primary text-black font-bold text-sm tracking-widest uppercase h-14 rounded-full hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20">
                <span class="material-icons-round align-middle mr-2 text-base">add_home</span>
                Créer la colocation
            </button>
        </form>
    </div>

    {{-- Info card --}}
    <div class="glass rounded-xl p-5 flex items-start gap-4">
        <span class="material-icons-round text-primary mt-0.5">info</span>
        <p class="text-stone-500 text-sm leading-relaxed">
            Après la création, vous pourrez inviter des colocataires, créer des catégories de dépenses et commencer à enregistrer vos dépenses partagées.
        </p>
    </div>

</div>
</x-app-layout>