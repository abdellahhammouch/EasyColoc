<x-app-layout>
    <div class="max-w-xl mx-auto p-6 space-y-4">
        <h1 class="text-2xl font-bold">Créer une colocation</h1>

        @if($errors->has('colocation'))
            <div class="p-3 bg-red-100 text-red-700 rounded">
                {{ $errors->first('colocation') }}
            </div>
        @endif

        <form method="POST" action="{{ route('colocations.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-1">Nom</label>
                <input name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button class="px-4 py-2 bg-black text-white rounded">Créer</button>
        </form>
    </div>
</x-app-layout>