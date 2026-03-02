<x-app-layout>
<div class="max-w-2xl mx-auto space-y-7">

    {{-- Header --}}
    <div>
        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-1">Paramètres</p>
        <h1 class="text-4xl font-serif font-bold text-white">Mon Profil</h1>
    </div>

    {{-- Réputation --}}
    @php $rep = auth()->user()->reputation ?? 0; @endphp
    <div class="glass rounded-2xl p-6 flex items-center gap-6">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0
            {{ $rep > 0 ? 'bg-emerald-500/10 border-2 border-emerald-500/30'
            : ($rep < 0 ? 'bg-red-500/10 border-2 border-red-500/30'
            : 'bg-white/5 border-2 border-white/10') }}">
            <span class="material-icons-round text-3xl {{ $rep > 0 ? 'text-emerald-400' : ($rep < 0 ? 'text-red-400' : 'text-stone-500') }}">
                {{ $rep < 0 ? 'thumb_down' : 'thumb_up' }}
            </span>
        </div>
        <div>
            <p class="text-xs uppercase tracking-widest font-bold text-stone-500 mb-1">Réputation</p>
            <p class="text-3xl font-bold {{ $rep > 0 ? 'text-emerald-400' : ($rep < 0 ? 'text-red-400' : 'text-stone-400') }}">
                {{ $rep > 0 ? '+' : '' }}{{ $rep }}
            </p>
            <p class="text-xs text-stone-600 mt-1">
                @if($rep > 0)
                    Excellent — vous avez toujours réglé vos comptes en partant.
                @elseif($rep < 0)
                    Mauvaise réputation — vous avez quitté des colocations sans rembourser.
                @else
                    Neutre — pas encore d'historique de départ.
                @endif
            </p>
        </div>
    </div>

    {{-- Profile Info --}}
    <div class="glass rounded-2xl p-8">
        <h2 class="font-bold text-white mb-6 flex items-center gap-2">
            <span class="material-icons-round text-primary">person</span>
            Informations personnelles
        </h2>
        @include('profile.partials.update-profile-information-form')
    </div>

    {{-- Password --}}
    <div class="glass rounded-2xl p-8">
        <h2 class="font-bold text-white mb-6 flex items-center gap-2">
            <span class="material-icons-round text-primary">lock</span>
            Changer le mot de passe
        </h2>
        @include('profile.partials.update-password-form')
    </div>

</div>
</x-app-layout>
