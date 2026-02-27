<x-app-layout>
<div class="max-w-lg mx-auto space-y-7">

    {{-- Header --}}
    <div>
        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">Invitation reçue</p>
        <h1 class="text-4xl font-serif font-bold text-white">Rejoindre une<br>Colocation</h1>
    </div>

    @if($invitation->status !== 'pending')

        {{-- Already used --}}
        <div class="glass rounded-2xl p-8 text-center space-y-4">
            <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center
                {{ $invitation->status === 'accepted' ? 'bg-emerald-500/10 border border-emerald-500/20' : 'bg-stone-500/10 border border-stone-500/20' }}">
                <span class="material-icons-round text-3xl {{ $invitation->status === 'accepted' ? 'text-emerald-400' : 'text-stone-500' }}">
                    {{ $invitation->status === 'accepted' ? 'check_circle' : 'cancel' }}
                </span>
            </div>
            <div>
                <p class="text-stone-400 text-sm uppercase tracking-widest font-bold">Invitation</p>
                <p class="text-2xl font-bold text-white mt-1 capitalize">{{ $invitation->status }}</p>
                <p class="text-stone-500 text-sm mt-2">
                    @if($invitation->status === 'accepted')
                        Cette invitation a déjà été acceptée.
                    @else
                        Cette invitation a été refusée ou a expiré.
                    @endif
                </p>
            </div>
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 bg-primary/10 text-primary border border-primary/20 px-6 py-2.5 rounded-full text-sm font-bold hover:bg-primary/20 transition-colors">
                <span class="material-icons-round text-base">home</span>
                Retour au dashboard
            </a>
        </div>

    @else

        {{-- Invitation card --}}
        <div class="glass rounded-2xl overflow-hidden">
            {{-- Gold top bar --}}
            <div class="h-1.5 bg-gradient-to-r from-primary/80 to-primary/20"></div>

            <div class="p-8 space-y-6">
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-primary/15 border border-primary/25 flex items-center justify-center">
                        <span class="material-icons-round text-primary text-3xl">home</span>
                    </div>
                    <div>
                        <p class="text-stone-500 text-xs uppercase tracking-widest font-bold mb-1">Vous êtes invité à rejoindre</p>
                        <h2 class="text-2xl font-serif font-bold text-white">{{ $invitation->colocation->name }}</h2>
                    </div>
                </div>

                <div class="bg-white/[0.03] rounded-xl px-5 py-4 border border-white/5 flex items-center gap-3">
                    <span class="material-icons-round text-stone-500 text-base">mail</span>
                    <div>
                        <p class="text-[10px] uppercase tracking-widest font-bold text-stone-600">Invitation envoyée à</p>
                        <p class="text-stone-300 text-sm font-medium mt-0.5">{{ $invitation->email }}</p>
                    </div>
                </div>

                @error('token')
                    <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                        <span class="material-icons-round">error</span>
                        {{ $message }}
                    </div>
                @enderror

                @auth
                    <div class="flex flex-col gap-3 pt-2">
                        <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-primary text-black font-bold text-sm tracking-widest uppercase h-14 rounded-full hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                                <span class="material-icons-round text-base">check_circle</span>
                                Accepter l'invitation
                            </button>
                        </form>

                        <form method="POST" action="{{ route('invitations.decline', $invitation->token) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-transparent text-stone-500 hover:text-white font-bold text-sm tracking-widest uppercase h-12 rounded-full border border-white/10 hover:border-white/20 transition-all flex items-center justify-center gap-2">
                                <span class="material-icons-round text-base">close</span>
                                Refuser
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-white/[0.03] rounded-xl p-5 border border-white/5 text-center space-y-3">
                        <p class="text-stone-400 text-sm">Vous devez être connecté pour accepter cette invitation.</p>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-2 bg-primary text-black font-bold px-8 py-3 rounded-full text-sm uppercase tracking-wider hover:bg-yellow-400 transition-all">
                            <span class="material-icons-round text-base">login</span>
                            Se connecter
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        {{-- Info --}}
        <div class="glass rounded-xl p-4 flex items-start gap-3">
            <span class="material-icons-round text-primary text-base mt-0.5">info</span>
            <p class="text-stone-500 text-xs leading-relaxed">
                En acceptant, vous rejoindrez la colocation et participerez au partage des dépenses. Cette invitation est à usage unique.
            </p>
        </div>

    @endif

</div>
</x-app-layout>