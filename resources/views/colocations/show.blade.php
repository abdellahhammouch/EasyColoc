<x-app-layout>
<div class="space-y-8" x-data="{ settleOpen: false }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-1">Colocation</p>
            <h1 class="text-4xl font-serif font-bold text-white">{{ $colocation->name }}</h1>
            <div class="flex items-center gap-4 mt-2 text-stone-500 text-sm">
                <span class="flex items-center gap-1">
                    <span class="material-icons-round text-base">person</span>
                    Owner : <b class="text-stone-300 ml-1">{{ $colocation->owner?->name }}</b>
                </span>
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $colocation->status === 'active' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-stone-500/10 text-stone-400 border border-stone-500/20' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $colocation->status === 'active' ? 'bg-emerald-500' : 'bg-stone-500' }}"></span>
                    {{ $colocation->status }}
                </span>
            </div>
        </div>
        @if(auth()->id() === $colocation->owner_id)
            <a href="{{ route('invitations.create', $colocation) }}"
               class="inline-flex items-center gap-2 bg-primary text-black font-bold px-6 py-3 rounded-full text-sm uppercase tracking-wider hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20">
                <span class="material-icons-round text-base">person_add</span>
                Inviter
            </a>
        @endif
    </div>

    @if(session('status'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm">
            <span class="material-icons-round">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main column --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Balances --}}
            <div class="glass rounded-2xl p-7">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="material-icons-round text-primary">account_balance_wallet</span>
                        Balances
                    </h2>
                    @php
                        $myBalance = isset($balances[auth()->id()]) ? $balances[auth()->id()]['balance'] : 0;
                    @endphp
                    @if($myBalance < 0)
                        <button @click="settleOpen = true"
                                class="inline-flex items-center gap-2 bg-primary text-black font-bold px-5 py-2.5 rounded-full text-xs uppercase tracking-wider hover:bg-yellow-400 transition-all">
                            <span class="material-icons-round text-sm">payments</span>
                            Marquer payé
                        </button>
                    @endif
                </div>

                <div class="space-y-3">
                    @foreach($balances as $userId => $data)
                        @php $bal = round($data['balance'], 2); @endphp
                        <div class="flex items-center justify-between p-4 rounded-xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.04] transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm
                                    {{ $userId === auth()->id() ? 'bg-primary/20 border border-primary/30 text-primary' : 'bg-white/5 border border-white/10 text-stone-400' }}">
                                    {{ strtoupper(substr($data['user']->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-stone-200 text-sm">
                                        {{ $data['user']->name }}
                                        @if($userId === auth()->id())
                                            <span class="text-primary text-[10px] ml-1">(vous)</span>
                                        @endif
                                    </p>
                                    <p class="text-[10px] uppercase tracking-wider text-stone-600">
                                        {{ $colocation->users->firstWhere('id', $userId)?->pivot->role }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                @if($bal > 0)
                                    <span class="text-emerald-400 font-bold">+{{ number_format($bal, 2) }} €</span>
                                    <span class="text-[10px] text-emerald-600 block">à recevoir</span>
                                @elseif($bal < 0)
                                    <span class="text-red-400 font-bold">{{ number_format($bal, 2) }} €</span>
                                    <span class="text-[10px] text-red-600 block">à payer</span>
                                @else
                                    <span class="text-stone-500 font-bold">0.00 €</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Expenses --}}
            <div class="glass rounded-2xl p-7">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="material-icons-round text-primary">receipt_long</span>
                        Dernières dépenses
                    </h2>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('expenses.index', $colocation) }}" class="text-xs text-primary hover:underline underline-offset-2">Voir tout</a>
                        @if($colocation->categories->isEmpty())
                            @if(auth()->id() === $colocation->owner_id)
                                <a href="{{ route('categories.index', $colocation) }}"
                                   class="inline-flex items-center gap-1 bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 px-4 py-1.5 rounded-full text-xs font-bold hover:bg-yellow-500/20 transition-colors">
                                    <span class="material-icons-round text-sm">category</span> Créer des catégories
                                </a>
                            @endif
                        @else
                            <a href="{{ route('expenses.create', $colocation) }}"
                               class="inline-flex items-center gap-1 bg-primary/10 text-primary border border-primary/20 px-4 py-1.5 rounded-full text-xs font-bold hover:bg-primary/20 transition-colors">
                                <span class="material-icons-round text-sm">add</span> Ajouter
                            </a>
                        @endif
                    </div>
                </div>

                @if($colocation->categories->isEmpty())
                    <div class="text-center py-8 text-stone-600">
                        <span class="material-icons-round text-4xl mb-2 block">category</span>
                        <p class="text-sm mb-3">Aucune catégorie créée.</p>
                        @if(auth()->id() === $colocation->owner_id)
                            <a href="{{ route('categories.index', $colocation) }}"
                               class="inline-flex items-center gap-2 bg-primary/10 text-primary border border-primary/20 px-4 py-2 rounded-full text-xs font-bold hover:bg-primary/20 transition-colors">
                                <span class="material-icons-round text-sm">add</span> Créer des catégories
                            </a>
                        @else
                            <p class="text-xs text-stone-600">Demandez à l'owner de créer des catégories.</p>
                        @endif
                    </div>
                @elseif($colocation->expenses->isEmpty())
                    <div class="text-center py-8 text-stone-600">
                        <span class="material-icons-round text-4xl mb-2 block">receipt</span>
                        <p class="text-sm">Aucune dépense pour le moment.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($colocation->expenses->sortByDesc('expense_date')->take(8) as $e)
                            <div class="flex items-center justify-between p-4 rounded-xl border border-white/5 bg-white/[0.02]">
                                <div class="flex items-center gap-3">
                                    <div class="text-right">
                                        <p class="font-semibold text-stone-200 text-sm group-hover:text-primary transition-colors">{{ $e->title }}</p>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="bg-primary/10 text-primary text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">{{ $e->category?->name }}</span>
                                            <span class="text-stone-600 text-xs">{{ \Illuminate\Support\Carbon::parse($e->expense_date)->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-white">{{ number_format($e->amount, 2) }} <span class="text-primary text-sm">€</span></p>
                                    <p class="text-[10px] text-stone-600">{{ $e->payer?->name }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">

            {{-- Members --}}
            <div class="glass rounded-2xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <span class="material-icons-round text-primary text-lg">group</span>
                        Membres
                    </h3>
                    <span class="text-primary font-bold text-xs">
                        {{ $colocation->users->where('pivot.left_at', null)->count() }} actifs
                    </span>
                </div>
                <div class="space-y-3">
                    @foreach($colocation->users as $u)
                        @if(is_null($u->pivot->left_at))
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl {{ $u->id === $colocation->owner_id ? 'border-2 border-primary bg-primary/15' : 'border border-white/10 bg-white/5' }} flex items-center justify-center font-bold text-sm {{ $u->id === $colocation->owner_id ? 'text-primary' : 'text-stone-400' }}">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-stone-200">{{ $u->name }}</p>
                                    <span class="text-[10px] uppercase tracking-wider {{ $u->id === $colocation->owner_id ? 'text-primary' : 'text-stone-600' }}">{{ $u->pivot->role }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Categories --}}
            <div class="glass rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <span class="material-icons-round text-primary text-lg">category</span>
                        Catégories
                    </h3>
                    @if(auth()->id() === $colocation->owner_id)
                        <a href="{{ route('categories.index', $colocation) }}" class="text-xs text-primary hover:underline">Gérer</a>
                    @endif
                </div>

                @if($colocation->categories->isEmpty())
                    <p class="text-stone-600 text-xs text-center py-3">Aucune catégorie créée.</p>
                @else
                    <div class="flex flex-wrap gap-2">
                        @foreach($colocation->categories->sortBy('name') as $cat)
                            <span class="bg-white/5 border border-white/10 text-stone-400 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $cat->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ══ Modal Règlement ══ --}}
    <div x-show="settleOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-black/80 backdrop-blur-sm"
         @click.self="settleOpen = false"
         @keydown.escape.window="settleOpen = false"
         style="display: none;">

        <div x-show="settleOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="w-full max-w-md rounded-2xl shadow-2xl overflow-hidden"
             style="background: rgba(36,35,33,0.97); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.07);">

            {{-- Gold top bar --}}
            <div class="h-1.5 bg-gradient-to-r from-primary/80 to-primary/20"></div>

            <div class="p-8">
                {{-- Icon --}}
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-primary/20">
                    <span class="material-icons-round text-primary text-3xl">payments</span>
                </div>

                <h3 class="text-xl font-serif font-bold text-white text-center mb-2">
                    Confirmer le règlement
                </h3>
                <p class="text-stone-500 text-sm text-center leading-relaxed mb-2">
                    Vous êtes sur le point de marquer votre dette comme <span class="text-primary font-semibold">soldée</span>.
                </p>
                @php $myBal = round(abs($myBalance), 2); @endphp
                <div class="flex items-center justify-center gap-2 my-5 p-3 rounded-xl bg-primary/5 border border-primary/15">
                    <span class="material-icons-round text-primary text-base">euro</span>
                    <span class="text-white font-bold text-lg">{{ number_format($myBal, 2) }} €</span>
                    <span class="text-stone-500 text-sm">à régler</span>
                </div>
                <p class="text-stone-600 text-xs text-center mb-7">
                    Cette action confirmera que vous avez effectué le virement à vos colocataires.
                </p>

                <div class="flex flex-col gap-3">
                    <form method="POST" action="{{ route('payments.settle', $colocation) }}">
                        @csrf
                        <button type="submit"
                                class="w-full bg-primary text-black font-bold text-sm tracking-widest uppercase h-14 rounded-full hover:bg-yellow-400 transition-all shadow-lg shadow-primary/20">
                            Confirmer le paiement
                        </button>
                    </form>
                    <button @click="settleOpen = false"
                            class="w-full text-stone-500 hover:text-white font-bold text-sm tracking-widest uppercase h-12 rounded-full border border-white/10 hover:border-white/20 transition-all">
                        Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
</x-app-layout>