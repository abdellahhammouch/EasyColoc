<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        {{-- Current password --}}
        <div class="flex flex-col gap-2">
            <label for="update_password_current_password"
                   class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">
                Mot de passe actuel
            </label>
            <input id="update_password_current_password" name="current_password"
                   type="password" autocomplete="current-password"
                   placeholder="••••••••••••"
                   class="w-full h-14 px-5 rounded-xl text-sm text-stone-200 placeholder-stone-600 outline-none transition-all
                          bg-[#2A2826] border border-white/10
                          focus:border-primary focus:ring-2 focus:ring-primary/20
                          @if($errors->updatePassword->get('current_password')) border-red-500/50 bg-red-950/30 @endif">
            @foreach ($errors->updatePassword->get('current_password') as $message)
                <p class="text-red-400 text-xs">{{ $message }}</p>
            @endforeach
        </div>

        {{-- New password --}}
        <div class="flex flex-col gap-2">
            <label for="update_password_password"
                   class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">
                Nouveau mot de passe
            </label>
            <input id="update_password_password" name="password"
                   type="password" autocomplete="new-password"
                   placeholder="••••••••••••"
                   class="w-full h-14 px-5 rounded-xl text-sm text-stone-200 placeholder-stone-600 outline-none transition-all
                          bg-[#2A2826] border border-white/10
                          focus:border-primary focus:ring-2 focus:ring-primary/20
                          @if($errors->updatePassword->get('password')) border-red-500/50 bg-red-950/30 @endif">
            @foreach ($errors->updatePassword->get('password') as $message)
                <p class="text-red-400 text-xs">{{ $message }}</p>
            @endforeach
        </div>

        {{-- Confirm password --}}
        <div class="flex flex-col gap-2">
            <label for="update_password_password_confirmation"
                   class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">
                Confirmer le nouveau mot de passe
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation"
                   type="password" autocomplete="new-password"
                   placeholder="••••••••••••"
                   class="w-full h-14 px-5 rounded-xl text-sm text-stone-200 placeholder-stone-600 outline-none transition-all
                          bg-[#2A2826] border border-white/10
                          focus:border-primary focus:ring-2 focus:ring-primary/20">
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="bg-primary text-black font-bold text-xs tracking-widest uppercase px-8 h-12 rounded-full hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20">
                Mettre à jour
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2500)"
                   class="flex items-center gap-2 text-emerald-400 text-sm font-medium">
                    <span class="material-icons-round text-base">check_circle</span>
                    Mot de passe mis à jour !
                </p>
            @endif
        </div>
    </form>
</section>