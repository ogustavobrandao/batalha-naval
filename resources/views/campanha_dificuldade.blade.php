<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="min-h-screen bg-[#020617] text-white font-sans">

        {{-- HERO --}}
        <section class="relative pt-14 pb-10 overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(37,99,235,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(37,99,235,0.04)_1px,transparent_1px)] bg-[size:60px_60px] pointer-events-none"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-4xl mx-auto px-6 relative z-10">
                <p class="text-blue-500 font-black uppercase tracking-[0.4em] text-xs mb-3">Modo Campanha</p>
                <h1 class="text-5xl md:text-7xl font-[1000] italic uppercase tracking-tighter leading-[0.85]">
                    ESCOLHA O <br><span class="text-blue-500">INIMIGO.</span>
                </h1>
                <div class="h-1 w-20 bg-blue-600 mt-4"></div>
                <p class="text-slate-400 font-bold uppercase italic text-sm mt-5 opacity-70">
                    Selecione o nível de dificuldade da IA para iniciar a batalha.
                </p>
            </div>
        </section>

        {{-- FORM --}}
        <form id="campanha_store" action="{{ route('partida.store') }}" method="post">
            @csrf
            <input type="hidden" name="modo" value="{{ $modo }}">

            <section class="max-w-4xl mx-auto px-6 pb-10">
                <div class="grid sm:grid-cols-3 gap-5" id="dificuldade-cards">

                    {{-- Fácil --}}
                    <div class="dificuldade-card group relative flex flex-col justify-between p-8 bg-slate-900 border-2 rounded-3xl cursor-pointer transition-all duration-300
                                {{ old('dificuldade', $dificuldade) === 'facil' ? 'border-green-500/60 bg-green-500/5 shadow-[0_0_30px_rgba(34,197,94,0.1)]' : 'border-white/5 hover:border-green-500/30' }}">
                        <input type="radio" name="dificuldade" value="facil" class="sr-only"
                               {{ old('dificuldade', $dificuldade) === 'facil' ? 'checked' : '' }}>

                        <div class="selected-check absolute top-4 right-4 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center transition-opacity
                                    {{ old('dificuldade', $dificuldade) === 'facil' ? 'opacity-100' : 'opacity-0' }}">
                            <span class="material-symbols-outlined text-white text-sm font-bold">check</span>
                        </div>

                        <span class="material-symbols-outlined text-5xl font-bold mb-6
                                     {{ old('dificuldade', $dificuldade) === 'facil' ? 'text-green-400' : 'text-slate-600 group-hover:text-green-500' }} transition-colors">
                            gps_not_fixed
                        </span>

                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.4em] mb-2
                                      {{ old('dificuldade', $dificuldade) === 'facil' ? 'text-green-400' : 'text-slate-600' }}">
                                Nível: Marinheiro
                            </p>
                            <h3 class="text-xl font-[1000] italic uppercase tracking-tighter mb-3">Recruta</h3>
                            <p class="text-slate-500 text-[10px] font-bold uppercase leading-relaxed">
                                IA dispara aleatoriamente sem memória de acertos. Ideal para iniciantes.
                            </p>
                            <p class="text-green-400 font-[1000] italic text-lg mt-4">100 pts base</p>
                        </div>
                    </div>

                    {{-- Médio --}}
                    <div class="dificuldade-card group relative flex flex-col justify-between p-8 bg-slate-900 border-2 rounded-3xl cursor-pointer transition-all duration-300
                                {{ old('dificuldade', $dificuldade) === 'medio' ? 'border-blue-500/60 bg-blue-500/5 shadow-[0_0_30px_rgba(59,130,246,0.1)]' : 'border-white/5 hover:border-blue-500/30' }}">
                        <input type="radio" name="dificuldade" value="medio" class="sr-only"
                               {{ old('dificuldade', $dificuldade) === 'medio' ? 'checked' : '' }}>

                        <div class="selected-check absolute top-4 right-4 w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center transition-opacity
                                    {{ old('dificuldade', $dificuldade) === 'medio' ? 'opacity-100' : 'opacity-0' }}">
                            <span class="material-symbols-outlined text-white text-sm font-bold">check</span>
                        </div>

                        <span class="material-symbols-outlined text-5xl font-bold mb-6
                                     {{ old('dificuldade', $dificuldade) === 'medio' ? 'text-blue-400' : 'text-slate-600 group-hover:text-blue-500' }} transition-colors">
                            radar
                        </span>

                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.4em] mb-2
                                      {{ old('dificuldade', $dificuldade) === 'medio' ? 'text-blue-400' : 'text-slate-600' }}">
                                Nível: Capitão
                            </p>
                            <h3 class="text-xl font-[1000] italic uppercase tracking-tighter mb-3">Experiente</h3>
                            <p class="text-slate-500 text-[10px] font-bold uppercase leading-relaxed">
                                Após um acerto, a IA persegue o navio sistematicamente até afundá-lo.
                            </p>
                            <p class="text-blue-400 font-[1000] italic text-lg mt-4">250 pts base</p>
                        </div>
                    </div>

                    {{-- Difícil --}}
                    <div class="dificuldade-card group relative flex flex-col justify-between p-8 bg-slate-900 border-2 rounded-3xl cursor-pointer transition-all duration-300
                                {{ old('dificuldade', $dificuldade) === 'dificil' ? 'border-red-500/60 bg-red-500/5 shadow-[0_0_30px_rgba(239,68,68,0.1)]' : 'border-white/5 hover:border-red-500/30' }}">
                        <input type="radio" name="dificuldade" value="dificil" class="sr-only"
                               {{ old('dificuldade', $dificuldade) === 'dificil' ? 'checked' : '' }}>

                        <div class="selected-check absolute top-4 right-4 w-6 h-6 rounded-full bg-red-500 flex items-center justify-center transition-opacity
                                    {{ old('dificuldade', $dificuldade) === 'dificil' ? 'opacity-100' : 'opacity-0' }}">
                            <span class="material-symbols-outlined text-white text-sm font-bold">check</span>
                        </div>

                        <span class="material-symbols-outlined text-5xl font-bold mb-6
                                     {{ old('dificuldade', $dificuldade) === 'dificil' ? 'text-red-400' : 'text-slate-600 group-hover:text-red-500' }} transition-colors">
                            psychology
                        </span>

                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.4em] mb-2
                                      {{ old('dificuldade', $dificuldade) === 'dificil' ? 'text-red-400' : 'text-slate-600' }}">
                                Nível: Almirante
                            </p>
                            <h3 class="text-xl font-[1000] italic uppercase tracking-tighter mb-3">Estrategista</h3>
                            <p class="text-slate-500 text-[10px] font-bold uppercase leading-relaxed">
                                Usa mapa de calor e análise probabilística para prever sua frota.
                            </p>
                            <p class="text-red-400 font-[1000] italic text-lg mt-4">500 pts base</p>
                        </div>
                    </div>

                </div>
            </section>

            {{-- BOTÕES --}}
            <section class="max-w-4xl mx-auto px-6 pb-16">
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('dashboard') }}"
                       class="flex-1 flex items-center justify-center gap-3 py-5 bg-slate-900 border border-white/5 rounded-2xl font-black uppercase italic tracking-tighter text-slate-400 hover:text-white hover:border-white/10 transition-all">
                        <span class="material-symbols-outlined font-bold">arrow_back</span>
                        Voltar ao Menu
                    </a>
                    <button type="submit" form="campanha_store"
                            class="flex-1 flex items-center justify-center gap-3 py-5 bg-blue-600 text-white font-black uppercase italic tracking-tighter rounded-2xl shadow-[0_6px_0_rgb(30,58,138)] hover:translate-y-[2px] hover:shadow-[0_4px_0_rgb(30,58,138)] active:translate-y-[4px] active:shadow-none transition-all">
                        <span class="material-symbols-outlined font-bold">rocket_launch</span>
                        Iniciar Combate
                    </button>
                </div>
            </section>

        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.dificuldade-card');

            const colors = {
                'facil':   { border: 'border-green-500/60', bg: 'bg-green-500/5',  shadow: 'shadow-[0_0_30px_rgba(34,197,94,0.1)]',   icon: 'text-green-400', label: 'text-green-400', check: 'bg-green-500'  },
                'medio':   { border: 'border-blue-500/60',  bg: 'bg-blue-500/5',   shadow: 'shadow-[0_0_30px_rgba(59,130,246,0.1)]',   icon: 'text-blue-400',  label: 'text-blue-400',  check: 'bg-blue-500'   },
                'dificil': { border: 'border-red-500/60',   bg: 'bg-red-500/5',    shadow: 'shadow-[0_0_30px_rgba(239,68,68,0.1)]',    icon: 'text-red-400',   label: 'text-red-400',   check: 'bg-red-500'    },
            };

            const defaults = {
                'facil':   { border: 'border-white/5', hover: 'hover:border-green-500/30', icon: 'text-slate-600', label: 'text-slate-600' },
                'medio':   { border: 'border-white/5', hover: 'hover:border-blue-500/30',  icon: 'text-slate-600', label: 'text-slate-600' },
                'dificil': { border: 'border-white/5', hover: 'hover:border-red-500/30',   icon: 'text-slate-600', label: 'text-slate-600' },
            };

            function selectCard(card) {
                const value = card.querySelector('input[type="radio"]').value;

                cards.forEach(c => {
                    const v = c.querySelector('input[type="radio"]').value;
                    const col = colors[v];
                    const def = defaults[v];
                    const radio = c.querySelector('input[type="radio"]');
                    const check = c.querySelector('.selected-check');
                    const icon  = c.querySelector('.material-symbols-outlined:not(.selected-check span)');
                    const label = c.querySelector('p[class*="tracking-"]');

                    // Reset
                    c.classList.remove(col.border, col.bg, col.shadow);
                    c.classList.add(def.border, def.hover);
                    if (check) check.classList.add('opacity-0');
                    radio.checked = false;
                });

                // Ativa selecionado
                const col = colors[value];
                const def = defaults[value];
                card.classList.remove(def.border, def.hover);
                card.classList.add(col.border, col.bg, col.shadow);
                card.querySelector('input[type="radio"]').checked = true;
                const check = card.querySelector('.selected-check');
                if (check) check.classList.remove('opacity-0');
            }

            cards.forEach(card => {
                card.addEventListener('click', () => selectCard(card));
            });
        });
    </script>
    @endpush
</x-app-layout>
