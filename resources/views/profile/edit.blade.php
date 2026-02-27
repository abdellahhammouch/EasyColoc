<x-app-layout>
<div class="max-w-2xl mx-auto space-y-7">

    {{-- Header --}}
    <div>
        <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-1">Paramètres</p>
        <h1 class="text-4xl font-serif font-bold text-white">Mon Profil</h1>
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

    {{-- Delete Account --}}
    <div class="rounded-2xl p-8 border border-red-500/20 bg-red-500/5">
        <h2 class="font-bold text-red-400 mb-2 flex items-center gap-2">
            <span class="material-icons-round">warning</span>
            Zone dangereuse
        </h2>
        <p class="text-stone-500 text-sm mb-6">La suppression de votre compte est irréversible. Toutes vos données seront perdues.</p>
        @include('profile.partials.delete-user-form')
    </div>

</div>
</x-app-layout>