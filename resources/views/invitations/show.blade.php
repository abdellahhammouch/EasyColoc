<x-app-layout>
    <div class="max-w-xl mx-auto p-6 space-y-4">
        <h1 class="text-2xl font-bold">Invitation</h1>

        @if($invitation->status !== 'pending')
            <div class="p-3 bg-gray-100 rounded">
                Invitation : <strong>{{ $invitation->status }}</strong>
            </div>
        @else
            <div class="p-4 border rounded space-y-2">
                <p>Colocation : <strong>{{ $invitation->colocation->name }}</strong></p>
                <p>Email invité : <strong>{{ $invitation->email }}</strong></p>
            </div>

            @error('token')
                <div class="p-3 bg-red-100 text-red-700 rounded">{{ $message }}</div>
            @enderror

            @auth
                <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white rounded">Accepter</button>
                </form>

                <form method="POST" action="{{ route('invitations.decline', $invitation->token) }}">
                    @csrf
                    <button class="px-4 py-2 bg-gray-700 text-white rounded">Refuser</button>
                </form>
            @else
                <a class="underline" href="{{ route('login') }}">Connecte-toi pour accepter</a>
            @endauth
        @endif
    </div>
</x-app-layout>