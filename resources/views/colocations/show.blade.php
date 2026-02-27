<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold">{{ $colocation->name }}</h1>
                <p class="text-gray-600">Status : <b>{{ $colocation->status }}</b></p>
            </div>

            <div class="text-sm text-gray-600">
                Owner : <b>{{ $colocation->owner?->name }}</b>
            </div>
        </div>

        {{-- Membres --}}
        <div class="p-4 border rounded space-y-2">
            <div class="flex items-center justify-between">
                <h2 class="font-bold">Membres</h2>
                @if(auth()->id() === $colocation->owner_id)
                    <a class="underline" href="{{ route('invitations.create', $colocation) }}">Inviter</a>
                @endif
            </div>

            <ul class="list-disc pl-5">
                @foreach($colocation->users as $u)
                    @if(is_null($u->pivot->left_at))
                        <li>
                            {{ $u->name }}
                            <span class="text-xs text-gray-600">({{ $u->pivot->role }})</span>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        {{-- Balances --}}
        <div class="p-4 border rounded space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="font-bold">Balances (membres actifs)</h2>

                @php
                    $me = $colocation->users->firstWhere('id', auth()->id());
                    $myBalance = $me?->pivot?->balance ?? 0;
                @endphp

                @if($me && is_null($me->pivot->left_at) && (float)$myBalance < 0)
                    <form method="POST" action="{{ route('payments.settle', $colocation) }}"
                        onsubmit="return confirm('Confirmer : Marquer payé ?')">
                        @csrf
                        <button class="px-4 py-2 bg-black text-white rounded">
                            Marquer payé
                        </button>
                    </form>
                @endif
            </div>

            <ul class="space-y-2">
                @foreach($colocation->users as $u)
                    @if(is_null($u->pivot->left_at))
                        @php $bal = (float) $u->pivot->balance; @endphp

                        <li class="flex items-center justify-between border rounded p-3">
                            <div>
                                <b>{{ $u->name }}</b>
                                <span class="text-xs text-gray-600">({{ $u->pivot->role }})</span>
                            </div>

                            <div>
                                @if($bal > 0)
                                    <span class="text-green-700 font-semibold">
                                        +{{ number_format($bal, 2) }} (doit recevoir)
                                    </span>
                                @elseif($bal < 0)
                                    <span class="text-red-700 font-semibold">
                                        {{ number_format($bal, 2) }} (doit payer)
                                    </span>
                                @else
                                    <span class="text-gray-700 font-semibold">0.00</span>
                                @endif
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            

            {{-- Catégories --}}
            <div class="p-4 border rounded space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold">Catégories</h2>
                    @if(auth()->id() === $colocation->owner_id)
                        <a class="underline" href="{{ route('categories.index', $colocation) }}">Gérer</a>
                    @endif
                </div>

                @if($colocation->categories->isEmpty())
                    <p class="text-gray-600 text-sm">Aucune catégorie. (L’owner doit en créer avant d’ajouter des dépenses)</p>
                @else
                    <ul class="list-disc pl-5">
                        @foreach($colocation->categories->sortBy('name') as $cat)
                            <li>{{ $cat->name }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Dépenses --}}
            <div class="p-4 border rounded space-y-3">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold">Dépenses</h2>
                    <div class="flex items-center gap-3">
                        <a class="underline" href="{{ route('expenses.index', $colocation) }}">Voir tout</a>
                        <a class="underline" href="{{ route('expenses.create', $colocation) }}">Ajouter</a>
                    </div>
                </div>

                @if(session('status'))
                    <div class="p-3 bg-green-100 rounded">{{ session('status') }}</div>
                @endif

                @if($colocation->categories->isEmpty())
                    <p class="text-gray-600 text-sm">Crée d’abord des catégories.</p>
                @elseif($colocation->expenses->isEmpty())
                    <p class="text-gray-600 text-sm">Aucune dépense pour le moment.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="p-2">Date</th>
                                    <th class="p-2">Titre</th>
                                    <th class="p-2">Catégorie</th>
                                    <th class="p-2">Payeur</th>
                                    <th class="p-2">Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($colocation->expenses->sortByDesc('expense_date')->take(10) as $e)
                                    <tr class="border-t">
                                        <td class="p-2">{{ \Illuminate\Support\Carbon::parse($e->expense_date)->format('Y-m-d') }}</td>
                                        <td class="p-2">{{ $e->title }}</td>
                                        <td class="p-2">{{ $e->category?->name }}</td>
                                        <td class="p-2">{{ $e->payer?->name }}</td>
                                        <td class="p-2">{{ number_format($e->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>