<section x-data="{ open: false }">

    <button type="button" @click="open = true"
            class="inline-flex items-center gap-2 bg-red-500/10 text-red-400 border border-red-500/20
                   px-6 h-12 rounded-full text-xs font-bold uppercase tracking-widest
                   hover:bg-red-500/20 transition-colors">
        <span class="material-icons-round text-base">delete_forever</span>
        Supprimer mon compte
    </button>

    {{-- Modal --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-6 bg-black/80 backdrop-blur-sm"
         @click.self="open = false"
         style="display: none;">

        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="w-full max-w-md rounded-2xl p-8 shadow-2xl"
             style="background: rgba(36,35,33,0.97); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.07);">

            {{-- Icon --}}
            <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-6 border border-red-500/20">
                <span class="material-icons-round text-red-500 text-3xl">warning_amber</span>
            </div>

            <h3 class="text-xl font-serif font-bold text-white text-center mb-2">
                Supprimer le compte ?
            </h3>
            <p class="text-stone-500 text-sm text-center leading-relaxed mb-8">
                Cette action est <span class="text-red-400 font-semibold">irréversible</span>.
                Toutes vos données seront définitivement supprimées.
                Confirmez avec votre mot de passe.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-5">
                @csrf
                @method('delete')

                <div class="flex flex-col gap-2">
                    <label for="delete_password"
                           class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/70">
                        Mot de passe
                    </label>
                    <input id="delete_password" name="password" type="password"
                           placeholder="Confirmez votre mot de passe"
                           class="w-full h-14 px-5 rounded-xl text-sm text-stone-200 placeholder-stone-600 outline-none transition-all
                                  bg-[#2A2826] border border-white/10
                                  focus:border-red-500 focus:ring-2 focus:ring-red-500/20
                                  @if($errors->userDeletion->get('password')) border-red-500/50 bg-red-950/30 @endif">
                    @foreach ($errors->userDeletion->get('password') as $message)
                        <p class="text-red-400 text-xs">{{ $message }}</p>
                    @endforeach
                </div>

                <div class="flex flex-col gap-3 pt-2">
                    <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white font-bold text-xs tracking-widest uppercase h-14 rounded-full transition-all shadow-lg shadow-red-600/20">
                        Confirmer la suppression
                    </button>
                    <button type="button" @click="open = false"
                            class="w-full bg-transparent text-stone-500 hover:text-white font-bold text-xs tracking-widest uppercase h-12 rounded-full border border-white/10 hover:border-white/20 transition-all">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

</section>