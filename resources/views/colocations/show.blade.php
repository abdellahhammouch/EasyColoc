<x-app-layout>
    <div class="max-w-3xl mx-auto p-6 space-y-4">
        <h1 class="text-2xl font-bold">{{ $colocation->name }}</h1>

        <div class="p-4 border rounded">
            <p><strong>Status :</strong> {{ $colocation->status }}</p>
            <p><strong>Owner :</strong> {{ $colocation->owner->name }}</p>
        </div>

        <div class="p-4 border rounded">
            <h2 class="font-bold mb-2">Membres</h2>
            <ul class="list-disc pl-5">
                @foreach($colocation->users as $u)
                    <li>
                        {{ $u->name }}
                        <span class="text-sm text-gray-600">({{ $u->pivot->role }})</span>
                    </li>
                @endforeach
            </ul>
        </div>

        @if(auth()->id() === $colocation->owner_id)
            <a class="underline" href="{{ route('invitations.create', $colocation) }}">
                Inviter un membre
            </a>
        @endif
    </div>
</x-app-layout>