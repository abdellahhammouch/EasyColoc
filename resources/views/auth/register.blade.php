<x-guest-layout>
<div class="w-full max-w-5xl flex flex-col lg:flex-row items-center gap-16">

    {{-- Form --}}
    <div class="w-full lg:w-1/2 flex flex-col gap-8">
        <div>
            <span class="text-primary text-xs font-bold tracking-[0.25em] uppercase">Inscription Premium</span>
            <h1 class="text-4xl md:text-5xl font-black tracking-tighter uppercase leading-tight mt-2 text-white">
                Rejoindre<br>la Coloc.
            </h1>
            <p class="text-stone-500 mt-3 text-sm">Créez votre compte et gérez vos colocations avec élégance.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5">
            @csrf

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-1">Nom complet</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       placeholder="Jean-Baptiste Dupont"
                       class="w-full h-14 px-5 rounded-xl text-sm text-stone-200 placeholder-stone-600 outline-none transition-all
                              bg-[#2A2826] border border-white/10
                              focus:border-primary focus:ring-2 focus:ring-primary/20
                              @error('name') border-red-500/50 bg-red-950/30 @enderror">
                @error('name')
                    <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       placeholder="votre@email.fr"
                       class="w-full h-14 px-5 rounded-xl text-sm text-stone-200 placeholder-stone-600 outline-none transition-all
                              bg-[#2A2826] border border-white/10
                              focus:border-primary focus:ring-2 focus:ring-primary/20
                              @error('email') border-red-500/50 bg-red-950/30 @enderror">
                @error('email')
                    <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-1">Mot de passe</label>
                <input type="password" name="password" required autocomplete="new-password"
                       placeholder="••••••••••••"
                       class="w-full h-14 px-5 rounded-xl text-sm text-stone-200 placeholder-stone-600 outline-none transition-all
                              bg-[#2A2826] border border-white/10
                              focus:border-primary focus:ring-2 focus:ring-primary/20
                              @error('password') border-red-500/50 bg-red-950/30 @enderror">
                @error('password')
                    <p class="text-red-400 text-xs ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-1">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       placeholder="••••••••••••"
                       class="w-full h-14 px-5 rounded-xl text-sm text-stone-200 placeholder-stone-600 outline-none transition-all
                              bg-[#2A2826] border border-white/10
                              focus:border-primary focus:ring-2 focus:ring-primary/20">
            </div>

            <button type="submit"
                    class="mt-2 bg-primary text-black font-bold text-sm tracking-widest uppercase h-14 rounded-full border-2 border-primary hover:bg-transparent hover:text-primary transition-all duration-300 shadow-lg shadow-primary/20">
                Créer mon compte
            </button>

            <p class="text-center text-sm text-stone-600">
                Déjà membre ?
                <a href="{{ route('login') }}" class="text-primary font-bold hover:underline underline-offset-2">Se connecter</a>
            </p>
        </form>
    </div>

    {{-- Visual --}}
    <div class="w-full lg:w-1/2 hidden lg:flex justify-end">
        <div class="relative">
            <div class="relative z-10 w-80 h-80 rounded-2xl flex flex-col items-center justify-center p-8 text-center"
                 style="background: linear-gradient(135deg, rgba(212,175,55,0.12) 0%, transparent 100%); border: 1px solid rgba(212,175,55,0.2); backdrop-filter: blur(20px);">
                <div class="bg-primary/15 p-6 rounded-full mb-5 border border-primary/20">
                    <span class="material-symbols-outlined text-primary text-6xl">verified_user</span>
                </div>
                <h3 class="text-xl font-bold mb-2 text-white">Réputation Premium</h3>
                <p class="text-stone-500 text-sm">Badge exclusif pour les résidents vérifiés de la plateforme.</p>
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