{{-- reset-password.blade.php --}}
<x-guest-layout>
<div class="w-full max-w-md">
    <div class="glass-form rounded-2xl p-10 space-y-7">
        <div class="text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-5 border border-primary/20">
                <span class="material-symbols-outlined text-primary text-3xl">key</span>
            </div>
            <span class="text-primary text-xs font-bold tracking-[0.25em] uppercase">Sécurité</span>
            <h1 class="text-3xl font-black tracking-tighter uppercase text-white mt-2">Nouveau<br>Mot de passe</h1>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-4">Email</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                       class="input-field @error('email') border-red-500/50 @enderror" placeholder="votre@email.fr">
                @error('email') <p class="text-red-400 text-xs ml-4">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-4">Nouveau mot de passe</label>
                <input type="password" name="password" required autocomplete="new-password"
                       class="input-field @error('password') border-red-500/50 @enderror" placeholder="••••••••••••">
                @error('password') <p class="text-red-400 text-xs ml-4">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70 ml-4">Confirmer</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       class="input-field" placeholder="••••••••••••">
            </div>

            <button type="submit"
                    class="w-full bg-primary text-black font-bold text-sm tracking-widest uppercase h-14 rounded-full border-2 border-primary hover:bg-transparent hover:text-primary transition-all duration-300">
                Réinitialiser le mot de passe
            </button>
        </form>
    </div>
</div>
</x-guest-layout>