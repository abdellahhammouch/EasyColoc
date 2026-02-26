<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Catégories</h1>
            <a class="underline" href="{{ route('colocations.show', $colocation) }}">Retour</a>
        </div>

        <p class="text-gray-600">Colocation : <b>{{ $colocation->name }}</b></p>

        @if(session('status'))
            <div class="p-3 bg-green-100 rounded">{{ session('status') }}</div>
        @endif

        <div class="p-4 border rounded space-y-3">
            <h2 class="font-semibold">Ajouter une catégorie</h2>

            <form method="POST" action="{{ route('categories.store', $colocation) }}" class="flex gap-2">
                @csrf
                <input name="name" value="{{ old('name') }}" class="flex-1 border rounded p-2" required>
                <button class="px-4 py-2 bg-black text-white rounded">Ajouter</button>
            </form>

            @error('name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="p-4 border rounded space-y-3">
            <h2 class="font-semibold">Liste</h2>

            @if($categories->isEmpty())
                <p class="text-gray-600 text-sm">Aucune catégorie.</p>
            @else
                @foreach($categories as $cat)
                    <div class="flex items-center justify-between gap-4 border rounded p-3">
                        <div class="font-medium">{{ $cat->name }}</div>

                        <form method="POST" action="{{ route('categories.update', [$colocation, $cat]) }}" class="flex gap-2">
                            @csrf
                            @method('PATCH')
                            <input name="name" class="border rounded p-2" value="{{ $cat->name }}" required>
                            <button class="px-3 py-2 bg-gray-800 text-white rounded">Renommer</button>
                        </form>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>