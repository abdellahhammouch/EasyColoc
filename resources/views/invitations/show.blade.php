<x-app-layout>
<div class="max-w-lg mx-auto space-y-7">

    {{-- Header --}}
    <div>
        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">Invitation reçue</p>
        <h1 class="text-4xl font-serif font-bold text-white">Rejoindre une<br>Colocation</h1>
    </div>

    {{-- Status message (ex: après refus redirigé ici) --}}
    @if(session('status'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            <span class="material-icons-round">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    @if($invitation->status !== 'pending')

        {{-- ── Invitation déjà utilisée / expirée ── --}}
        <div class="glass rounded-2xl p-8 text-center space-y-4">
            <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center
                @if($invitation->status === 'accepted') bg-emerald-500/10 border border-emerald-500/20
                @elseif($invitation->status === 'expired') bg-yellow-500/10 border border-yellow-500/20
                @else bg-stone-500/10 border border-stone-500/20 @endif">
                <span class="material-icons-round text-3xl
                    @if($invitation->status === 'accepted') text-emerald-400
                    @elseif($invitation->status === 'expired') text-yellow-400
                    @else text-stone-500 @endif">
                    @if($invitation->status === 'accepted') check_circle
                    @elseif($invitation->status === 'expired') schedule
                    @else cancel @endif
                </span>
            </div>
            <div>
                <p class="text-stone-500 text-xs uppercase tracking-widest font-bold">Invitation</p>
                <p class="text-2xl font-bold text-white mt-1 capitalize">
                    @if($invitation->status === 'accepted') Acceptée
                    @elseif($invitation->status === 'expired') Expirée
                    @else Refusée @endif
                </p>
                <p class="text-stone-500 text-sm mt-2">
                    @if($invitation->status === 'accepted') Cette invitation a déjà été acceptée.
                    @elseif($invitation->status === 'expired') Ce lien d'invitation a expiré.
                    @else Cette invitation a été refusée. @endif
                </p>
            </div>
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 bg-primary/10 text-primary border border-primary/20 px-6 py-2.5 rounded-full text-sm font-bold hover:bg-primary/20 transition-colors">
                    <span class="material-icons-round text-base">home</span>
                    Retour au dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 bg-primary/10 text-primary border border-primary/20 px-6 py-2.5 rounded-full text-sm font-bold hover:bg-primary/20 transition-colors">
                    <span class="material-icons-round text-base">login</span>
                    Se connecter
                </a>
            @endauth
        </div>

    @else

        {{-- ── Invitation en attente ── --}}

        {{-- Erreur token (mauvais email, déjà en coloc, etc.) --}}
        @error('token')
            <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                <span class="material-icons-round">error</span>
                {{ $message }}
            </div>
        @enderror

        {{-- Card invitation --}}
        <div class="glass rounded-2xl overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-primary/80 to-primary/20"></div>

            <div class="p-8 space-y-6">

                {{-- Colocation info --}}
                <div class="flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-primary/15 border border-primary/25 flex items-center justify-center">
                        <span class="material-icons-round text-primary text-3xl">home</span>
                    </div>
                    <div>
                        <p class="text-stone-500 text-xs uppercase tracking-widest font-bold mb-1">Vous êtes invité à rejoindre</p>
                        <h2 class="text-2xl font-serif font-bold text-white">{{ $invitation->colocation->name }}</h2>
                    </div>
                </div>

                {{-- Details --}}
                <div class="space-y-3">
                    <div class="bg-white/[0.03] rounded-xl px-5 py-3 border border-white/5 flex items-center gap-3">
                        <span class="material-icons-round text-stone-500 text-base">person</span>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest font-bold text-stone-600">Invité par</p>
                            <p class="text-stone-300 text-sm font-medium">{{ $invitation->inviter->name ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="bg-white/[0.03] rounded-xl px-5 py-3 border border-white/5 flex items-center gap-3">
                        <span class="material-icons-round text-stone-500 text-base">mail</span>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest font-bold text-stone-600">Pour l'adresse</p>
                            <p class="text-stone-300 text-sm font-medium">{{ $invitation->email }}</p>
                        </div>
                    </div>

                    @if($invitation->expires_at)
                        <div class="bg-white/[0.03] rounded-xl px-5 py-3 border border-white/5 flex items-center gap-3">
                            <span class="material-icons-round text-stone-500 text-base">schedule</span>
                            <div>
                                <p class="text-[10px] uppercase tracking-widest font-bold text-stone-600">Expire le</p>
                                <p class="text-stone-300 text-sm font-medium">{{ \Carbon\Carbon::parse($invitation->expires_at)->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- ── Guest : doit se connecter ── --}}
                @guest
                    <div class="bg-yellow-500/5 border border-yellow-500/20 rounded-xl p-5 space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="material-icons-round text-yellow-400">info</span>
                            <p class="text-yellow-400 text-sm font-medium">
                                Connectez-vous avec <span class="font-bold">{{ $invitation->email }}</span> pour accepter cette invitation.
                            </p>
                        </div>
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('login') }}"
                               class="w-full flex items-center justify-center gap-2 bg-primary text-black font-bold px-8 py-3 rounded-full text-sm uppercase tracking-wider hover:bg-yellow-400 transition-all">
                                <span class="material-icons-round text-base">login</span>
                                Se connecter
                            </a>
                            <a href="{{ route('register') }}"
                               class="w-full flex items-center justify-center gap-2 text-stone-400 hover:text-white font-bold px-8 py-3 rounded-full text-sm uppercase tracking-wider border border-white/10 hover:border-white/20 transition-all">
                                <span class="material-icons-round text-base">person_add</span>
                                Créer un compte
                            </a>
                        </div>
                    </div>
                @endguest

                {{-- ── Auth : boutons accepter / refuser ── --}}
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

                    <p class="text-stone-600 text-xs text-center">
                        Vous devez être connecté avec <span class="text-stone-400 font-medium">{{ $invitation->email }}</span> pour accepter.
                    </p>
                @endauth

            </div>
        </div>

        {{-- Info --}}
        <div class="glass rounded-xl p-4 flex items-start gap-3">
            <span class="material-icons-round text-primary text-base mt-0.5">info</span>
            <p class="text-stone-500 text-xs leading-relaxed">
                En acceptant, vous rejoindrez la colocation et participerez au partage des dépenses. Ce lien est à usage unique.
            </p>
        </div>

    @endif

</div>
</x-app-layout>