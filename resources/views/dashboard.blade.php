<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="min-h-screen bg-[#020617] text-white font-sans">

        {{-- HERO --}}
        <section class="relative pt-14 pb-10 overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(37,99,235,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(37,99,235,0.04)_1px,transparent_1px)] bg-[size:60px_60px] pointer-events-none"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-10">

                    <div>
                        <p class="text-blue-500 font-black uppercase tracking-[0.4em] text-xs mb-3">
                            Bem-vindo de volta, Comandante
                        </p>
                        <h1 class="text-5xl md:text-7xl font-[1000] italic uppercase tracking-tighter leading-[0.85]">
                            PRONTO <br><span class="text-blue-500">PARA O COMBATE?</span>
                        </h1>
                        <p class="text-slate-400 font-bold uppercase italic text-sm mt-5 opacity-70">
                            Escolha sua missão abaixo e domine o oceano.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-4 shrink-0">
                        <div class="bg-slate-900/80 border border-white/5 rounded-2xl px-6 py-5 text-center hover:border-blue-500/30 transition-colors">
                            <p class="text-3xl font-[1000] italic text-blue-500">{{ $stats['vitorias'] ?? 0 }}</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mt-1">Vitórias</p>
                        </div>
                        <div class="bg-slate-900/80 border border-white/5 rounded-2xl px-6 py-5 text-center hover:border-blue-500/30 transition-colors">
                            <p class="text-3xl font-[1000] italic text-white">{{ $stats['partidas'] ?? 0 }}</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mt-1">Partidas</p>
                        </div>
                        <div class="bg-slate-900/80 border border-white/5 rounded-2xl px-6 py-5 text-center hover:border-blue-500/30 transition-colors">
                            <p class="text-3xl font-[1000] italic text-yellow-500">{{ $stats['medalhas'] ?? 0 }}</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mt-1">Medalhas</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- AÇÕES PRINCIPAIS --}}
        <section class="max-w-7xl mx-auto px-6 pb-10">
            <div class="grid md:grid-cols-3 gap-5">

                <a href="{{ route('partida.modos') }}"
                   class="group relative flex flex-col justify-between p-8 bg-blue-600 rounded-3xl shadow-[0_8px_0_rgb(30,58,138)] hover:translate-y-[2px] hover:shadow-[0_6px_0_rgb(30,58,138)] active:translate-y-[4px] active:shadow-none transition-all overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-125 transition-transform duration-500"></div>
                    <span class="material-symbols-outlined text-5xl text-white/80 font-bold mb-8">rocket_launch</span>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-200 mb-1">Ação Principal</p>
                        <h3 class="text-2xl font-[1000] italic uppercase tracking-tighter">Novo Combate</h3>
                        <p class="text-blue-100/70 text-xs font-bold uppercase mt-2">Inicie uma nova batalha agora.</p>
                    </div>
                </a>

                <button class="group relative flex flex-col justify-between p-8 bg-slate-900 border border-white/5 rounded-3xl hover:border-blue-500/40 transition-all overflow-hidden text-left">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-blue-600/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-125 transition-transform duration-500"></div>
                    <span class="material-symbols-outlined text-5xl text-slate-500 font-bold mb-8 group-hover:text-blue-400 transition-colors">save</span>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-600 mb-1 group-hover:text-blue-500 transition-colors">Continuar</p>
                        <h3 class="text-2xl font-[1000] italic uppercase tracking-tighter">Carregar Jogo</h3>
                        <p class="text-slate-500 text-xs font-bold uppercase mt-2">Retome uma partida salva.</p>
                    </div>
                </button>

                <a href="{{ route('ranking.index') }}"
                   class="group relative flex flex-col justify-between p-8 bg-slate-900 border border-white/5 rounded-3xl hover:border-yellow-500/30 transition-all overflow-hidden">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-yellow-500/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-125 transition-transform duration-500"></div>
                    <span class="material-symbols-outlined text-5xl text-slate-500 font-bold mb-8 group-hover:text-yellow-500 transition-colors">emoji_events</span>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-slate-600 mb-1 group-hover:text-yellow-500 transition-colors">Competição</p>
                        <h3 class="text-2xl font-[1000] italic uppercase tracking-tighter">Ranking Global</h3>
                        <p class="text-slate-500 text-xs font-bold uppercase mt-2">Veja quem domina os mares.</p>
                    </div>
                </a>
            </div>
        </section>

        {{-- MODOS DE JOGO + LINKS SECUNDÁRIOS --}}
        <section class="max-w-7xl mx-auto px-6 pb-10">
            <div class="grid lg:grid-cols-3 gap-5">

                {{-- Modos de Campanha --}}
                <div class="lg:col-span-2 p-8 bg-slate-950/80 border border-white/5 rounded-3xl">
                    <div class="mb-6">
                        <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Modo <span class="text-blue-500">Campanha</span></h2>
                        <div class="h-0.5 w-12 bg-blue-600 mt-2"></div>
                    </div>
                    <div class="grid sm:grid-cols-3 gap-4">

                        <a href="{{ route('partida.create', ['modo' => 'pve', 'dificuldade' => 'facil']) }}"
                           class="p-5 bg-slate-900/50 rounded-2xl border border-white/5 hover:border-blue-500/30 hover:bg-slate-900 transition-all">
                            <p class="text-blue-500 font-black italic text-[10px] uppercase mb-2 tracking-widest">Marinheiro</p>
                            <h3 class="text-sm font-black italic uppercase mb-1">Tiros Aleatórios</h3>
                            <p class="text-slate-500 text-[10px] font-bold uppercase leading-relaxed">IA básica sem estratégia.</p>
                        </a>

                        <a href="{{ route('partida.create', ['modo' => 'pve', 'dificuldade' => 'medio']) }}"
                           class="p-5 bg-slate-900 border-2 border-blue-600/50 rounded-2xl shadow-xl hover:border-blue-400 transition-all">
                            <p class="text-blue-400 font-black italic text-[10px] uppercase mb-2 tracking-widest">Capitão</p>
                            <h3 class="text-sm font-black italic uppercase mb-1">Busca Inteligente</h3>
                            <p class="text-slate-400 text-[10px] font-bold uppercase leading-relaxed">IA tática com rastreamento.</p>
                        </a>

                        <a href="{{ route('partida.create', ['modo' => 'pve', 'dificuldade' => 'dificil']) }}"
                           class="p-5 bg-slate-900/50 rounded-2xl border border-white/5 hover:border-indigo-500/30 hover:bg-slate-900 transition-all">
                            <p class="text-indigo-500 font-black italic text-[10px] uppercase mb-2 tracking-widest">Almirante</p>
                            <h3 class="text-sm font-black italic uppercase mb-1">Probabilidade</h3>
                            <p class="text-slate-500 text-[10px] font-bold uppercase leading-relaxed">IA com mapa de calor.</p>
                        </a>

                    </div>
                </div>

                {{-- Links Secundários --}}
                <div class="flex flex-col gap-4">
                    <a href="{{ route('regras') }}"
                       class="group flex items-center gap-5 p-6 bg-slate-900 border border-white/5 rounded-2xl hover:border-blue-500/30 transition-all">
                        <span class="material-symbols-outlined text-3xl text-slate-500 group-hover:text-blue-400 transition-colors font-bold">menu_book</span>
                        <div>
                            <h4 class="text-sm font-black italic uppercase">Regras do Jogo</h4>
                            <p class="text-slate-600 text-[10px] font-bold uppercase">Como vencer a batalha.</p>
                        </div>
                        <span class="material-symbols-outlined text-slate-700 ml-auto group-hover:text-slate-400 transition-colors">arrow_forward</span>
                    </a>

                    <a href="{{ route('configuracoes') }}"
                       class="group flex items-center gap-5 p-6 bg-slate-900 border border-white/5 rounded-2xl hover:border-blue-500/30 transition-all">
                        <span class="material-symbols-outlined text-3xl text-slate-500 group-hover:text-blue-400 transition-colors font-bold">settings</span>
                        <div>
                            <h4 class="text-sm font-black italic uppercase">Configurações</h4>
                            <p class="text-slate-600 text-[10px] font-bold uppercase">Personalize sua experiência.</p>
                        </div>
                        <span class="material-symbols-outlined text-slate-700 ml-auto group-hover:text-slate-400 transition-colors">arrow_forward</span>
                    </a>

                    <div class="flex items-center gap-5 p-6 bg-slate-900/50 border border-white/5 rounded-2xl">
                        <span class="material-symbols-outlined text-3xl text-yellow-500 font-bold">military_tech</span>
                        <div>
                            <h4 class="text-sm font-black italic uppercase">Suas Medalhas</h4>
                            <div class="flex gap-1 mt-1">
                                @for($i = 0; $i < min($stats['medalhas'] ?? 0, 5); $i++)
                                    <span class="material-symbols-outlined text-yellow-500 text-sm font-bold">military_tech</span>
                                @endfor
                                @if(($stats['medalhas'] ?? 0) == 0)
                                    <p class="text-slate-600 text-[10px] font-bold uppercase">Nenhuma ainda.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- PARTIDAS RECENTES --}}
        <section class="max-w-7xl mx-auto px-6 pb-16">
            <div class="p-8 bg-slate-950/80 border border-white/5 rounded-3xl">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Últimas <span class="text-blue-500">Batalhas</span></h2>
                        <div class="h-0.5 w-12 bg-blue-600 mt-2"></div>
                    </div>
                    <a href="{{ route('ranking.index') }}" class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-500 hover:text-white transition-colors">
                        Ver Tudo →
                    </a>
                </div>

                @if(isset($partidas) && count($partidas))
                    <div class="space-y-3">
                        @foreach($partidas as $partida)
                        <div class="flex items-center justify-between p-4 bg-slate-900/50 rounded-xl border border-white/5 hover:border-blue-500/20 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-lg {{ $partida->resultado === 'vitoria' ? 'bg-blue-600/20 border border-blue-500/30' : 'bg-red-900/20 border border-red-500/20' }} flex items-center justify-center">
                                    <span class="material-symbols-outlined text-sm {{ $partida->resultado === 'vitoria' ? 'text-blue-400' : 'text-red-400' }} font-bold">
                                        {{ $partida->resultado === 'vitoria' ? 'check' : 'close' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs font-black italic uppercase">{{ $partida->modo ?? 'Modo Clássico' }}</p>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase">{{ $partida->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-black italic uppercase {{ $partida->resultado === 'vitoria' ? 'text-blue-400' : 'text-red-400' }}">
                                {{ $partida->resultado === 'vitoria' ? 'Vitória' : 'Derrota' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <span class="material-symbols-outlined text-6xl text-slate-800 font-bold mb-4">anchor</span>
                        <p class="text-slate-600 font-black italic uppercase text-sm">Nenhuma batalha ainda.</p>
                        <p class="text-slate-700 font-bold uppercase text-[10px] mt-1">Inicie seu primeiro combate!</p>
                    </div>
                @endif
            </div>
        </section>

    </div>
</x-app-layout>
