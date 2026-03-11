<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="min-h-screen bg-[#020617] text-white font-sans">

        {{-- HERO --}}
        <section class="relative pt-14 pb-10 overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(37,99,235,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(37,99,235,0.04)_1px,transparent_1px)] bg-[size:60px_60px] pointer-events-none"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-4xl mx-auto px-6 relative z-10">
                <p class="text-blue-500 font-black uppercase tracking-[0.4em] text-xs mb-3">Manual do Comandante</p>
                <h1 class="text-5xl md:text-7xl font-[1000] italic uppercase tracking-tighter leading-[0.85]">
                    REGRAS <br><span class="text-blue-500">DO COMBATE</span>
                </h1>
                <div class="h-1 w-20 bg-blue-600 mt-4"></div>
                <p class="text-slate-400 font-bold uppercase italic text-sm mt-5 opacity-70 max-w-lg">
                    Aprenda a posicionar sua frota, atacar o inimigo e conquistar a vitória nos mares.
                </p>
            </div>
        </section>

        <div class="max-w-4xl mx-auto px-6 pb-16 flex flex-col gap-5">

            {{-- OBJETIVO --}}
            <div class="p-8 bg-slate-900/80 border border-white/5 rounded-3xl hover:border-blue-500/20 transition-colors">
                <div class="flex items-center gap-4 mb-5">
                    <span class="material-symbols-outlined text-4xl text-blue-500 font-bold">target</span>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-500">Missão</p>
                        <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Objetivo</h2>
                    </div>
                </div>
                <p class="text-slate-400 font-bold uppercase text-sm leading-relaxed">
                    Afunde <span class="text-white">todos os navios</span> da frota inimiga antes que o inimigo afunde os seus.
                    Vence quem destruir completamente a frota adversária primeiro.
                </p>
            </div>

            {{-- FROTA --}}
            <div class="p-8 bg-slate-900/80 border border-white/5 rounded-3xl">
                <div class="flex items-center gap-4 mb-6">
                    <span class="material-symbols-outlined text-4xl text-blue-500 font-bold">directions_boat</span>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-500">Esquadra</p>
                        <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Unidades de Combate <span class="text-blue-500">(10×10)</span></h2>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach([
                        ['cor' => '#6366f1', 'nome' => 'Porta-aviões', 'blocos' => 6, 'qtd' => 2],
                        ['cor' => '#0ea5e9', 'nome' => 'Navio de Guerra', 'blocos' => 4, 'qtd' => 2],
                        ['cor' => '#f59e0b', 'nome' => 'Encouraçado', 'blocos' => 3, 'qtd' => 1],
                        ['cor' => '#10b981', 'nome' => 'Submarino', 'blocos' => 1, 'qtd' => 1],
                    ] as $navio)
                    <div class="flex items-center justify-between p-5 bg-slate-950/60 rounded-2xl border border-white/5 hover:border-white/10 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 rounded-full shrink-0" style="background: {{ $navio['cor'] }}; box-shadow: 0 0 8px {{ $navio['cor'] }}"></div>
                            <span class="font-black italic uppercase text-sm">{{ $navio['nome'] }}</span>
                        </div>
                        <div class="flex items-center gap-4 text-[10px] font-black uppercase">
                            <span class="text-slate-500">{{ $navio['blocos'] }} casas</span>
                            <span class="text-blue-500">×{{ $navio['qtd'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- COMO JOGAR --}}
            <div class="p-8 bg-slate-900/80 border border-white/5 rounded-3xl">
                <div class="flex items-center gap-4 mb-6">
                    <span class="material-symbols-outlined text-4xl text-blue-500 font-bold">menu_book</span>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-500">Passo a Passo</p>
                        <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Como Jogar</h2>
                    </div>
                </div>
                <div class="flex flex-col gap-6">
                    @foreach([
                        ['icon' => 'my_location',    'titulo' => 'Posicione sua frota',    'desc' => 'Selecione um navio na barra lateral, escolha a direção com o botão girar e clique no tabuleiro para posicioná-lo. Clique em um navio já posicionado para removê-lo.'],
                        ['icon' => 'rocket_launch',  'titulo' => 'Inicie a batalha',        'desc' => 'Após posicionar todos os navios, clique em "Confirmar Implantação". A IA posicionará sua frota automaticamente.'],
                        ['icon' => 'explosion',      'titulo' => 'Ataque o inimigo',        'desc' => 'Clique em qualquer célula do radar inimigo para disparar. Acertou? Você atira novamente. Errou? A vez passa para o inimigo.'],
                        ['icon' => 'anchor',         'titulo' => 'Afunde todos os navios',  'desc' => 'Um navio é afundado quando todos os seus blocos são atingidos. O jogo termina quando uma das frotas é completamente destruída.'],
                    ] as $i => $passo)
                    <div class="flex gap-5 items-start">
                        <div class="shrink-0 w-10 h-10 rounded-xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-500 text-lg font-bold">{{ $passo['icon'] }}</span>
                        </div>
                        <div class="pt-1">
                            <p class="font-black italic uppercase tracking-tight text-white">{{ sprintf('%02d', $i + 1) }}. {{ $passo['titulo'] }}</p>
                            <p class="text-slate-500 text-xs font-bold uppercase mt-1 leading-relaxed">{{ $passo['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- DIFICULDADES + PONTUAÇÃO (lado a lado) --}}
            <div class="grid md:grid-cols-2 gap-5">

                {{-- Dificuldades --}}
                <div class="p-8 bg-slate-900/80 border border-white/5 rounded-3xl">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="material-symbols-outlined text-4xl text-blue-500 font-bold">bolt</span>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-500">IA Inimiga</p>
                            <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Dificuldades</h2>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="p-5 bg-slate-950/60 rounded-2xl border border-green-500/20 hover:border-green-500/40 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-green-400 font-black italic uppercase text-sm">Fácil — Marinheiro</span>
                                <span class="text-green-400 font-[1000] italic">100 pts</span>
                            </div>
                            <p class="text-slate-500 text-[10px] font-bold uppercase leading-relaxed">IA atira em coordenadas aleatórias sem estratégia.</p>
                        </div>
                        <div class="p-5 bg-slate-950/60 rounded-2xl border border-yellow-500/20 hover:border-yellow-500/40 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-yellow-400 font-black italic uppercase text-sm">Médio — Capitão</span>
                                <span class="text-yellow-400 font-[1000] italic">250 pts</span>
                            </div>
                            <p class="text-slate-500 text-[10px] font-bold uppercase leading-relaxed">IA persegue navios atingidos até afundá-los.</p>
                        </div>
                        <div class="p-5 bg-slate-950/60 rounded-2xl border border-red-500/20 hover:border-red-500/40 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-red-400 font-black italic uppercase text-sm">Difícil — Almirante</span>
                                <span class="text-red-400 font-[1000] italic">500 pts</span>
                            </div>
                            <p class="text-slate-500 text-[10px] font-bold uppercase leading-relaxed">IA usa mapa de calor e probabilidades para localizar sua frota.</p>
                        </div>
                    </div>
                </div>

                {{-- Pontuação --}}
                <div class="p-8 bg-slate-900/80 border border-white/5 rounded-3xl">
                    <div class="flex items-center gap-4 mb-6">
                        <span class="material-symbols-outlined text-4xl text-yellow-500 font-bold">emoji_events</span>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.4em] text-yellow-500">Sistema</p>
                            <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Pontuação</h2>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center justify-between p-4 bg-slate-950/60 rounded-2xl border border-white/5">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Bônus Precisão</p>
                                <p class="text-[10px] text-slate-600 font-bold uppercase mt-0.5">Baseado na % de acertos</p>
                            </div>
                            <span class="font-[1000] italic text-white">+200</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-slate-950/60 rounded-2xl border border-white/5">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Bônus Tempo</p>
                                <p class="text-[10px] text-slate-600 font-bold uppercase mt-0.5">-1 pt a cada 10 segundos</p>
                            </div>
                            <span class="font-[1000] italic text-white">+300</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-slate-950/60 rounded-2xl border border-white/5">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Bônus Vitória</p>
                                <p class="text-[10px] text-slate-600 font-bold uppercase mt-0.5">Apenas se vencer a batalha</p>
                            </div>
                            <span class="font-[1000] italic text-white">+300</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-blue-600/10 rounded-2xl border border-blue-500/30">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-blue-400">Pontuação Máxima</p>
                                <p class="text-[10px] text-slate-600 font-bold uppercase mt-0.5">Difícil + perfeito + rápido</p>
                            </div>
                            <span class="font-[1000] italic text-blue-400 text-lg">1.300</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MEDALHAS --}}
            <div class="p-8 bg-slate-900/80 border border-white/5 rounded-3xl">
                <div class="flex items-center gap-4 mb-6">
                    <span class="material-symbols-outlined text-4xl text-yellow-500 font-bold">military_tech</span>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-yellow-500">Conquistas</p>
                        <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Medalhas</h2>
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach([
                        ['cor' => 'text-yellow-400 border-yellow-500/30 bg-yellow-500/5',  'nome' => 'Almirante',              'desc' => 'Vença a batalha sem perder nenhum navio da sua frota.'],
                        ['cor' => 'text-blue-400   border-blue-500/30   bg-blue-500/5',    'nome' => 'Capitão de Mar e Guerra','desc' => 'Acerte 8 tiros consecutivos no tabuleiro inimigo.'],
                        ['cor' => 'text-slate-300  border-slate-500/30  bg-slate-500/5',   'nome' => 'Capitão',                'desc' => 'Acerte 7 tiros consecutivos no tabuleiro inimigo.'],
                        ['cor' => 'text-green-400  border-green-500/30  bg-green-500/5',   'nome' => 'Marinheiro',             'desc' => 'Vença a batalha em menos de 3 minutos.'],
                    ] as $medalha)
                    <div class="flex items-start gap-4 p-5 rounded-2xl border {{ $medalha['cor'] }} transition-colors">
                        <span class="material-symbols-outlined text-2xl font-bold shrink-0 mt-0.5">military_tech</span>
                        <div>
                            <p class="font-black italic uppercase text-sm">{{ $medalha['nome'] }}</p>
                            <p class="text-slate-500 text-[10px] font-bold uppercase mt-1 leading-relaxed">{{ $medalha['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- BOTÃO VOLTAR --}}
            <div class="flex justify-center pt-2 pb-4">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-3 px-10 py-4 bg-blue-600 text-white font-black uppercase italic tracking-tighter rounded-2xl shadow-[0_6px_0_rgb(30,58,138)] hover:translate-y-[2px] hover:shadow-[0_4px_0_rgb(30,58,138)] active:translate-y-[4px] active:shadow-none transition-all">
                    <span class="material-symbols-outlined font-bold">arrow_back</span>
                    Voltar ao Menu
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
