<x-app-layout>
    <div class="max-w-xl mx-auto p-6 space-y-4">
        <h1 class="text-2xl font-bold">Inviter un membre</h1>

        <p class="text-gray-600">Colocation : <b>{{ $colocation->name }}</b></p>

        @if(session('invite_link'))
            <div class="p-3 bg-green-100 rounded">
                <p class="font-semibold">Lien à copier :</p>
                <p class="break-all text-sm">{{ session('invite_link') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('invitations.store', $colocation) }}" class="space-y-3">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-1">Email</label>
                <input name="email" value="{{ old('email') }}" class="w-full border rounded p-2" required>
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button class="px-4 py-2 bg-black text-white rounded">Créer invitation</button>
        </form>
    </div>
</x-app-layout>