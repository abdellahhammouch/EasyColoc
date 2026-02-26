<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 space-y-6">
        <h1 class="text-2xl font-bold">Admin — Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 border rounded">
                <p class="text-sm text-gray-600">Utilisateurs</p>
                <p class="text-2xl font-bold">{{ $stats['users'] }}</p>
            </div>

            <div class="p-4 border rounded">
                <p class="text-sm text-gray-600">Utilisateurs bannis</p>
                <p class="text-2xl font-bold">{{ $stats['banned_users'] }}</p>
            </div>

            <div class="p-4 border rounded">
                <p class="text-sm text-gray-600">Colocations</p>
                <p class="text-2xl font-bold">{{ $stats['colocations'] }}</p>
            </div>

            <div class="p-4 border rounded">
                <p class="text-sm text-gray-600">Dépenses</p>
                <p class="text-2xl font-bold">{{ $stats['expenses'] }}</p>
            </div>

            <div class="p-4 border rounded">
                <p class="text-sm text-gray-600">Paiements</p>
                <p class="text-2xl font-bold">{{ $stats['payments'] }}</p>
            </div>
        </div>

        <div>
            <a href="{{ route('admin.users.index') }}" class="underline">
                Gérer les utilisateurs (ban/unban)
            </a>
        </div>
    </div>
</x-app-layout>