<x-app-layout>
    <div class="max-w-2xl mx-auto p-6 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Ajouter une dépense</h1>
            <a class="underline" href="{{ route('expenses.index', $colocation) }}">Retour</a>
        </div>

        <p class="text-gray-600">Colocation : <b>{{ $colocation->name }}</b></p>

        @if($categories->isEmpty())
            <div class="p-4 bg-yellow-100 rounded">
                Aucune catégorie. Demande à l’owner de créer des catégories avant.
            </div>
        @else
            <form method="POST" action="{{ route('expenses.store', $colocation) }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-semibold mb-1">Titre</label>
                    <input name="title" value="{{ old('title') }}" class="w-full border rounded p-2" required>
                    @error('title') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Montant</label>
                        <input name="amount" type="number" step="0.01" min="0.01" value="{{ old('amount') }}" class="w-full border rounded p-2" required>
                        @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Date</label>
                        <input name="expense_date" type="date" value="{{ old('expense_date', now()->toDateString()) }}" class="w-full border rounded p-2" required>
                        @error('expense_date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Catégorie</label>
                    <select name="category_id" class="w-full border rounded p-2" required>
                        <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Choisir...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (string)old('category_id') === (string)$cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Notes (optionnel)</label>
                    <textarea name="notes" class="w-full border rounded p-2" rows="3">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <button class="px-4 py-2 bg-black text-white rounded">Enregistrer</button>
            </form>
        @endif
    </div>
</x-app-layout>