<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">Dépenses</h1>
                <p class="text-gray-600">Colocation : <b>{{ $colocation->name }}</b></p>
            </div>

            <div class="flex items-center gap-3">
                <a class="underline" href="{{ route('colocations.show', $colocation) }}">Retour</a>
                <a class="underline" href="{{ route('expenses.create', $colocation) }}">Ajouter</a>
            </div>
        </div>

        @if(session('status'))
            <div class="p-3 bg-green-100 rounded">{{ session('status') }}</div>
        @endif

        {{-- Filtre par mois --}}
        <div class="p-4 border rounded">
            <form method="GET" action="{{ route('expenses.index', $colocation) }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-sm font-semibold mb-1">Mois (optionnel)</label>
                    <input type="month" name="month" value="{{ request('month') }}" class="border rounded p-2">
                </div>
                <button class="px-4 py-2 bg-gray-800 text-white rounded">Filtrer</button>
                <a class="underline" href="{{ route('expenses.index', $colocation) }}">Réinitialiser</a>
            </form>
        </div>

        @if($categories->isEmpty())
            <div class="p-4 bg-yellow-100 rounded">
                Aucune catégorie. L’owner doit créer des catégories avant d’ajouter des dépenses.
            </div>
        @endif

        <div class="border rounded overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3">Date</th>
                        <th class="p-3">Titre</th>
                        <th class="p-3">Catégorie</th>
                        <th class="p-3">Payeur</th>
                        <th class="p-3">Montant</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($expenses as $e)
                    <tr class="border-t">
                        <td class="p-3">{{ \Illuminate\Support\Carbon::parse($e->expense_date)->format('Y-m-d') }}</td>
                        <td class="p-3">{{ $e->title }}</td>
                        <td class="p-3">{{ $e->category?->name }}</td>
                        <td class="p-3">{{ $e->payer?->name }}</td>
                        <td class="p-3">{{ number_format($e->amount, 2) }}</td>
                    </tr>
                @empty
                    <tr class="border-t">
                        <td class="p-3 text-gray-600" colspan="5">Aucune dépense.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $expenses->links() }}
        </div>
    </div>
</x-app-layout>