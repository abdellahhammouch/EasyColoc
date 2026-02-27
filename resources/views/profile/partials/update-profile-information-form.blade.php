<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        {{-- Name --}}
        <div class="flex flex-col gap-2">
            <label for="name" class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">
                Nom complet
            </label>
            <input id="name" name="name" type="text" required autofocus autocomplete="name"
                   value="{{ old('name', $user->name) }}"
                   class="w-full h-14 px-5 rounded-xl text-sm text-stone-200 placeholder-stone-600 outline-none transition-all
                          bg-[#2A2826] border border-white/10
                          focus:border-primary focus:ring-2 focus:ring-primary/20
                          @error('name') border-red-500/50 bg-red-950/30 @enderror">
            @error('name')
                <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="flex flex-col gap-2">
            <label for="email" class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">
                Adresse email
            </label>
            <input id="email" name="email" type="email" required autocomplete="username"
                   value="{{ old('email', $user->email) }}"
                   class="w-full h-14 px-5 rounded-xl text-sm text-stone-200 placeholder-stone-600 outline-none transition-all
                          bg-[#2A2826] border border-white/10
                          focus:border-primary focus:ring-2 focus:ring-primary/20
                          @error('email') border-red-500/50 bg-red-950/30 @enderror">
            @error('email')
                <p class="text-red-400 text-xs">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="flex items-center gap-3 p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-sm">
                    <span class="material-icons-round text-base">warning</span>
                    <div>
                        Adresse email non vérifiée.
                        <button form="send-verification"
                                class="underline hover:text-yellow-300 transition-colors ml-1">
                            Renvoyer l'email de vérification.
                        </button>
                    </div>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
                        <span class="material-icons-round text-base">check_circle</span>
                        Un nouveau lien de vérification a été envoyé.
                    </div>
                @endif
            @endif
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="bg-primary text-black font-bold text-xs tracking-widest uppercase px-8 h-12 rounded-full hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20">
                Sauvegarder
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="flex items-center gap-2 text-emerald-400 text-sm font-medium">
                    <span class="material-icons-round text-base">check_circle</span>
                    Sauvegardé !
                </p>
            @endif
        </div>
    </form>
</section>