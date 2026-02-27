<x-app-layout>
<div class="space-y-8">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-primary text-xs font-bold tracking-[0.25em] uppercase mb-2">Administration</p>
            <h1 class="text-4xl md:text-5xl font-serif font-bold text-white">Gestion des <br><span class="text-stone-500">Utilisateurs</span></h1>
        </div>
        <div class="relative group">
            <span class="material-icons-round absolute left-4 top-1/2 -translate-y-1/2 text-stone-500 group-focus-within:text-primary transition-colors text-xl">search</span>
            <input type="text" placeholder="Rechercher un membre..."
                   class="w-full md:w-72 bg-card-dark border border-white/10 rounded-full py-3 pl-12 pr-6 text-stone-200 text-sm placeholder-stone-600 focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all">
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('status'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
            <span class="material-icons-round">check_circle</span>
            <span class="text-sm font-medium">{{ session('status') }}</span>
        </div>
    @endif

    @if($errors->has('ban'))
        <div class="flex items-center gap-3 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400">
            <span class="material-icons-round">error</span>
            <span class="text-sm font-medium">{{ $errors->first('ban') }}</span>
        </div>
    @endif

    {{-- Table --}}
    <div class="glass rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-white/5" style="background: rgba(255,255,255,0.02);">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-stone-500">Utilisateur</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-stone-500">Rôle</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-stone-500 hidden md:table-cell">ID</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-stone-500">Statut</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-stone-500 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/[0.04]">
                    @foreach($users as $u)
                    <tr class="group hover:bg-white/[0.02] transition-colors {{ $u->is_banned ? 'opacity-60' : '' }}">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 {{ $u->is_banned ? 'border-red-500/30 bg-red-500/10 text-red-400' : 'border-primary/20 bg-primary/10 text-primary' }}">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-stone-200 {{ $u->is_banned ? 'line-through text-stone-500' : '' }}">{{ $u->name }}</p>
                                    <p class="text-xs text-stone-500">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                {{ $u->role === 'admin'
                                    ? 'bg-primary/10 text-primary border border-primary/20'
                                    : 'bg-white/5 text-stone-400 border border-white/10' }}">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-6 py-5 hidden md:table-cell">
                            <span class="text-stone-600 text-sm font-mono">#{{ $u->id }}</span>
                        </td>
                        <td class="px-6 py-5">
                            @if($u->is_banned)
                                <span class="flex items-center gap-2 text-red-400 text-sm">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Banni
                                </span>
                            @else
                                <span class="flex items-center gap-2 text-emerald-400 text-sm">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Actif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            @if($u->is_banned)
                                <form method="POST" action="{{ route('admin.users.unban', $u) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="inline-flex items-center gap-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider hover:bg-emerald-500/20 transition-colors">
                                        <span class="material-icons-round text-sm">undo</span> Débannir
                                    </button>
                                </form>
                            @else
                                <button onclick="openBanModal({{ $u->id }}, '{{ addslashes($u->name) }}')"
                                        class="inline-flex items-center gap-2 bg-red-500/10 text-red-400 border border-red-500/20 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider hover:bg-red-500/20 transition-colors">
                                    <span class="material-icons-round text-sm">block</span> Bannir
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-white/5 flex items-center justify-between" style="background: rgba(255,255,255,0.01);">
            <p class="text-xs text-stone-500">
                Affichage de {{ $users->firstItem() }}–{{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
            </p>
            <div class="flex items-center gap-1">
                {{-- Previous --}}
                @if($users->onFirstPage())
                    <span class="p-2 rounded-lg text-stone-700 cursor-not-allowed"><span class="material-icons-round text-lg">chevron_left</span></span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="p-2 rounded-lg text-stone-400 hover:bg-white/10 transition-colors"><span class="material-icons-round text-lg">chevron_left</span></a>
                @endif

                {{-- Page numbers --}}
                @foreach($users->getUrlRange(max(1, $users->currentPage()-2), min($users->lastPage(), $users->currentPage()+2)) as $page => $url)
                    @if($page == $users->currentPage())
                        <span class="w-8 h-8 rounded-lg bg-primary text-black font-bold text-sm flex items-center justify-center">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 rounded-lg text-stone-400 hover:bg-white/10 text-sm flex items-center justify-center transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="p-2 rounded-lg text-stone-400 hover:bg-white/10 transition-colors"><span class="material-icons-round text-lg">chevron_right</span></a>
                @else
                    <span class="p-2 rounded-lg text-stone-700 cursor-not-allowed"><span class="material-icons-round text-lg">chevron_right</span></span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Ban Modal --}}
<div id="banModal" class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-black/80 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
    <div id="banModalBox" class="w-full max-w-md rounded-2xl p-8 shadow-2xl scale-95 transition-transform duration-300"
         style="background: rgba(36,35,33,0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.07);">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-5">
                <span class="material-icons-round text-red-500 text-4xl">warning_amber</span>
            </div>
            <h3 class="text-2xl font-serif font-bold text-white mb-2">Bannir l'utilisateur ?</h3>
            <p class="text-stone-400 text-sm leading-relaxed" id="banModalDesc">
                Cette action révoquera l'accès de <strong class="text-white" id="banModalName"></strong> à la plateforme.
            </p>
        </div>

        <form id="banForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="flex flex-col gap-3">
                <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-full transition-all shadow-lg shadow-red-600/20 tracking-wider text-sm uppercase">
                    Confirmer le bannissement
                </button>
                <button type="button" onclick="closeBanModal()"
                        class="w-full bg-transparent hover:bg-white/5 text-stone-400 font-bold py-4 rounded-full transition-all tracking-wider text-sm uppercase border border-white/10">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openBanModal(userId, userName) {
        document.getElementById('banModalName').textContent = userName;
        document.getElementById('banForm').action = '/admin/users/' + userId + '/ban';
        const modal = document.getElementById('banModal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        document.getElementById('banModalBox').classList.remove('scale-95');
        document.getElementById('banModalBox').classList.add('scale-100');
    }
    function closeBanModal() {
        const modal = document.getElementById('banModal');
        modal.classList.add('opacity-0', 'pointer-events-none');
        document.getElementById('banModalBox').classList.add('scale-95');
        document.getElementById('banModalBox').classList.remove('scale-100');
    }
    document.getElementById('banModal').addEventListener('click', function(e) {
        if (e.target === this) closeBanModal();
    });
    window.addEventListener('keydown', e => { if (e.key === 'Escape') closeBanModal(); });
</script>
</x-app-layout>