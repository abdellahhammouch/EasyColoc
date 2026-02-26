<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 space-y-4">
        <h1 class="text-2xl font-bold">Admin — Utilisateurs</h1>

        @if(session('status'))
            <div class="p-3 bg-green-100 rounded">{{ session('status') }}</div>
        @endif

        @if($errors->has('ban'))
            <div class="p-3 bg-red-100 rounded">{{ $errors->first('ban') }}</div>
        @endif

        <div class="border rounded overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Nom</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Rôle</th>
                        <th class="p-3">Banni</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($users as $u)
                    <tr class="border-t">
                        <td class="p-3">{{ $u->id }}</td>
                        <td class="p-3">{{ $u->name }}</td>
                        <td class="p-3">{{ $u->email }}</td>
                        <td class="p-3">{{ $u->role }}</td>
                        <td class="p-3">{{ $u->is_banned ? 'Oui' : 'Non' }}</td>

                        <td class="p-3">
                            @if($u->is_banned)
                                <form method="POST" action="{{ route('admin.users.unban', $u) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="px-3 py-1 rounded bg-black text-white">Débannir</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.users.ban', $u) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="px-3 py-1 rounded bg-red-600 text-white">Bannir</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>