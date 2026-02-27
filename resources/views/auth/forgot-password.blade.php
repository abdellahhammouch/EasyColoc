<x-guest-layout>
<div class="w-full max-w-md">
    <div class="glass-form rounded-2xl p-10 space-y-7">
        <div class="text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-5 border border-primary/20">
                <span class="material-symbols-outlined text-primary text-3xl">lock_reset</span>
            </div>
            <span class="text-primary text-xs font-bold tracking-[0.25em] uppercase">Récupération</span>
            <h1 class="text-3xl font-black tracking-tighter uppercase text-white mt-2">Mot de passe<br>oublié ?</h1>
            <p class="text-stone-500 text-sm mt-3 leading-relaxed">
                Renseignez votre adresse email et nous vous enverrons un lien de réinitialisation.
            </p>
        </div>

        @if (session('status'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
                <span class="material-symbols-outlined text-base">check_circle</span>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-4">Votre email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="input-field @error('email') border-red-500/50 @enderror"
                       placeholder="votre@email.fr">
                @error('email')
                    <p class="text-red-400 text-xs ml-4">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-primary text-black font-bold text-sm tracking-widest uppercase h-14 rounded-full border-2 border-primary hover:bg-transparent hover:text-primary transition-all duration-300 shadow-lg shadow-primary/20">
                Envoyer le lien
            </button>

            <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 text-stone-500 hover:text-primary transition-colors text-sm">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Retour à la connexion
            </a>
        </form>
    </div>
</div>
</x-guest-layout>