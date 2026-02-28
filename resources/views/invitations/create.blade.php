<x-app-layout>
<div class="max-w-xl mx-auto space-y-7">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('colocations.show', $colocation) }}"
           class="w-10 h-10 rounded-full glass flex items-center justify-center hover:border-primary/40 transition-colors text-stone-400 hover:text-primary">
            <span class="material-icons-round text-lg">arrow_back</span>
        </a>
        <div>
            <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase">{{ $colocation->name }}</p>
            <h1 class="text-3xl font-serif font-bold text-white">Inviter un membre</h1>
        </div>
    </div>

    {{-- Invite link success --}}
    @if(session('status'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            <span class="material-icons-round">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    {{-- Form --}}
    <div class="glass rounded-2xl p-8 space-y-6">
        <div class="flex items-start gap-4 pb-5 border-b border-white/5">
            <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center">
                <span class="material-icons-round text-primary text-xl">person_add</span>
            </div>
            <div>
                <h2 class="font-bold text-white">Nouvelle invitation</h2>
                <p class="text-stone-500 text-sm mt-1">
                    Un lien d'invitation sera généré et envoyé à l'adresse renseignée.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('invitations.store', $colocation) }}" class="space-y-5">
            @csrf

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">
                    Adresse email du futur colocataire
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="bg-white/5 border border-white/10 rounded-xl h-12 px-5 text-stone-200 placeholder-stone-600 focus:border-primary focus:ring-1 focus:ring-primary/30 outline-none transition-all @error('email') border-red-500/50 @enderror"
                       placeholder="colocataire@email.fr">
                @error('email')
                    <p class="text-red-400 text-xs">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-primary text-black font-bold text-sm tracking-widest uppercase h-14 rounded-full hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20">
                <span class="material-icons-round align-middle mr-2 text-base">send</span>
                Créer l'invitation
            </button>
        </form>
    </div>

    {{-- Info --}}
    <div class="glass rounded-xl p-5 flex items-start gap-4">
        <span class="material-icons-round text-primary mt-0.5 text-base">info</span>
        <p class="text-stone-500 text-sm leading-relaxed">
            Le lien d'invitation est à usage unique. Une fois accepté, le membre rejoindra automatiquement la colocation <b class="text-stone-400">{{ $colocation->name }}</b>.
        </p>
    </div>

</div>
</x-app-layout>