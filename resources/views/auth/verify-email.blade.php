<x-guest-layout>
<div class="w-full max-w-md">
    <div class="glass-form rounded-2xl p-10 space-y-7">
        <div class="text-center">
            <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-5 border border-primary/20">
                <span class="material-symbols-outlined text-primary text-3xl">mark_email_unread</span>
            </div>
            <span class="text-primary text-xs font-bold tracking-[0.25em] uppercase">Vérification</span>
            <h1 class="text-3xl font-black tracking-tighter uppercase text-white mt-2">Vérifiez<br>votre email</h1>
            <p class="text-stone-500 text-sm mt-3 leading-relaxed">
                Un lien de vérification a été envoyé à votre adresse. Cliquez dessus pour activer votre compte.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
                <span class="material-symbols-outlined text-base">check_circle</span>
                Un nouveau lien de vérification a été envoyé.
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                        class="w-full bg-primary text-black font-bold text-sm tracking-widest uppercase h-14 rounded-full border-2 border-primary hover:bg-transparent hover:text-primary transition-all duration-300">
                    Renvoyer l'email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full bg-transparent text-stone-500 hover:text-white font-bold text-sm tracking-widest uppercase h-12 rounded-full border border-white/10 hover:border-white/20 transition-all">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
</div>
</x-guest-layout>