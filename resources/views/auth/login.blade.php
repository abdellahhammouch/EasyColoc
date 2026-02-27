<x-guest-layout>
<div class="w-full max-w-5xl flex flex-col lg:flex-row items-center gap-16">

    {{-- Form --}}
    <div class="w-full lg:w-1/2 flex flex-col gap-8">
        <div>
            <span class="text-primary text-xs font-bold tracking-[0.25em] uppercase">Espace membre</span>
            <h1 class="text-4xl md:text-5xl font-black tracking-tighter uppercase leading-tight mt-2 text-white">
                Bon<br>Retour.
            </h1>
            <p class="text-stone-500 mt-3 text-sm">Connectez-vous pour gérer vos colocations et dépenses partagées.</p>
        </div>

        @if(session('status'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
                <span class="material-symbols-outlined text-base">check_circle</span>
                {{ session('status') }}
            </div>
        @endif

        @if($errors->has('banned'))
            <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                <span class="material-symbols-outlined text-base">block</span>
                {{ $errors->first('banned') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
            @csrf

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-4">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="input-field @error('email') border-red-500/50 @enderror"
                       placeholder="votre@email.fr">
                @error('email')
                    <p class="text-red-400 text-xs ml-4">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-4">Mot de passe</label>
                <input type="password" name="password" required autocomplete="current-password"
                       class="input-field @error('password') border-red-500/50 @enderror"
                       placeholder="••••••••••••">
                @error('password')
                    <p class="text-red-400 text-xs ml-4">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between px-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" id="remember_me"
                           class="w-4 h-4 rounded border-white/20 bg-white/5 text-primary focus:ring-primary/50">
                    <span class="text-xs text-stone-500">Se souvenir de moi</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-primary hover:underline underline-offset-4">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>

            <button type="submit"
                    class="mt-2 bg-primary text-black font-bold text-sm tracking-widest uppercase h-14 rounded-full border-2 border-primary hover:bg-transparent hover:text-primary transition-all duration-300 shadow-lg shadow-primary/20">
                Se connecter
            </button>

            <p class="text-center text-sm text-stone-600">
                Pas encore membre ?
                <a href="{{ route('register') }}" class="text-primary font-bold hover:underline underline-offset-2">Créer un compte</a>
            </p>
        </form>
    </div>

    {{-- Visual --}}
    <div class="w-full lg:w-1/2 hidden lg:flex justify-end">
        <div class="relative">
            <div class="relative z-10 w-80 h-80 rounded-2xl flex flex-col items-center justify-center p-8 text-center"
                 style="background: linear-gradient(135deg, rgba(212,175,55,0.12) 0%, transparent 100%); border: 1px solid rgba(212,175,55,0.2); backdrop-filter: blur(20px);">
                <div class="bg-primary/15 p-6 rounded-full mb-5 border border-primary/20">
                    <span class="material-symbols-outlined text-primary text-6xl">apartment</span>
                </div>
                <h3 class="text-xl font-bold mb-2 text-white">Gestion Premium</h3>
                <p class="text-stone-500 text-sm">Partagez vos dépenses en toute transparence avec vos colocataires.</p>
                <div class="absolute -top-4 -right-4 w-12 h-12 bg-primary rounded-xl flex items-center justify-center shadow-xl">
                    <span class="material-symbols-outlined text-black font-bold text-xl">star</span>
                </div>
            </div>
            <div class="absolute -top-16 -left-16 w-40 h-40 bg-primary/8 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-16 -right-16 w-56 h-56 bg-primary/5 rounded-full blur-3xl"></div>
        </div>
    </div>

</div>
</x-guest-layout>