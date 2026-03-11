<x-guest-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="min-h-screen bg-[#020617] text-white font-sans selection:bg-blue-500">
        
        <nav class="flex items-center justify-between px-6 py-4 border-b border-white/5 bg-slate-950/50 backdrop-blur-xl sticky top-0 z-50">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-10 h-10">
                <span class="text-2xl font-[1000] italic tracking-tighter uppercase">Batalha<span class="text-blue-500">Naval</span></span>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-white transition-colors">Entrar</a>
                <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-500 px-6 py-2.5 rounded-lg font-black uppercase italic tracking-tighter transition-all active:scale-95 shadow-[0_4px_0_rgb(30,58,138)] hover:translate-y-[2px] hover:shadow-[0_2px_0_rgb(30,58,138)]">
                    Jogar Agora
                </a>
            </div>
        </nav>

        <section class="relative pt-16 pb-24 overflow-hidden">
            <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center relative z-10">
                <div class="text-center lg:text-left">
                    <h1 class="text-6xl md:text-[5.5rem] font-[1000] italic uppercase tracking-tighter leading-[0.85] mb-8">
                        ESTRATÉGIA <br><span class="text-blue-500">OU NAUFRÁGIO.</span>
                    </h1>
                    <p class="text-slate-400 text-lg md:text-xl max-w-xl mb-10 leading-relaxed font-bold uppercase italic opacity-80">
                        Domine o oceano em batalhas 10x10. Destrua a frota inimiga antes que detectem a sua.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-6 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-12 py-5 font-[1000] uppercase italic text-xl tracking-tighter text-white bg-blue-600 rounded-2xl transition-all shadow-[0_8px_0_rgb(30,58,138)] hover:translate-y-[2px] hover:shadow-[0_6px_0_rgb(30,58,138)] active:translate-y-[4px] active:shadow-none">
                            Iniciar Combate
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-10 py-5 font-black uppercase italic text-lg tracking-tighter text-slate-400 border-2 border-white/5 rounded-2xl hover:text-white hover:border-white/10 transition-all">
                            Já sou Comandante
                        </a>
                    </div>
                </div>

                <div class="relative flex justify-center">
                    <div class="relative p-8 bg-slate-900 border-4 border-slate-800 rounded-[2.5rem] shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-500 group">
                        <div class="absolute -top-6 -right-6 bg-yellow-500 text-black font-[1000] px-6 py-2 rounded-xl uppercase italic text-sm shadow-xl z-20 animate-bounce">
                            Batalha MultiJogador
                        </div>
                        
                        <div class="bg-white p-4 rounded-2xl shadow-inner group-hover:scale-105 transition-transform">
                            <div class="w-64 h-64 bg-white grid grid-cols-7 grid-rows-7 gap-1">
                                <div class="bg-black rounded-sm col-span-2 row-span-2"></div><div class="bg-white"></div><div class="bg-black"></div><div class="bg-white"></div><div class="bg-black rounded-sm col-span-2 row-span-2"></div>
                                <div class="bg-white"></div><div class="bg-black"></div><div class="bg-white"></div>
                                <div class="bg-black"></div><div class="bg-white"></div><div class="bg-black"></div><div class="bg-white"></div><div class="bg-black"></div><div class="bg-white"></div><div class="bg-black"></div>
                                <div class="bg-white"></div><div class="bg-black"></div><div class="bg-white"></div><div class="bg-black"></div><div class="bg-white"></div><div class="bg-black"></div><div class="bg-white"></div>
                                <div class="bg-black rounded-sm col-span-2 row-span-2"></div><div class="bg-white"></div><div class="bg-black"></div><div class="bg-white"></div><div class="bg-black"></div><div class="bg-white"></div>
                                <div class="bg-white"></div><div class="bg-black"></div><div class="bg-white"></div><div class="bg-black"></div>
                            </div>
                        </div>
                        <p class="mt-6 text-center text-slate-500 font-black uppercase text-[10px] tracking-[0.3em]">Escaneie para Modo Online</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-slate-950/80 border-y border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="mb-12">
                    <h2 class="text-3xl font-[1000] italic uppercase tracking-tighter">Modo <span class="text-blue-500">Campanha</span></h2>
                    <div class="h-1 w-20 bg-blue-600 mt-2"></div>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    <div class="p-8 bg-slate-900/50 rounded-3xl border border-white/5 hover:border-blue-500/30 transition-colors">
                        <div class="text-blue-500 font-black italic text-xs uppercase mb-4 tracking-widest">Nível: Marinheiro</div>
                        <h3 class="text-xl font-black italic uppercase mb-3">Tiros Aleatórios</h3>
                        <p class="text-slate-400 text-sm font-bold uppercase leading-relaxed opacity-70">
                            IA básica. Dispara projéteis em coordenadas aleatórias sem analisar o campo de batalha.
                        </p>
                    </div>
                    <div class="p-8 bg-slate-900 border-2 border-blue-600/50 rounded-3xl shadow-2xl scale-105">
                        <div class="text-blue-400 font-black italic text-xs uppercase mb-4 tracking-widest">Nível: Capitão</div>
                        <h3 class="text-xl font-black italic uppercase mb-3">Busca Inteligente</h3>
                        <p class="text-slate-300 text-sm font-bold uppercase leading-relaxed">
                            IA tática. Ao registrar um acerto, inicia uma busca sistemática nas células adjacentes até afundar o navio.
                        </p>
                    </div>
                    <div class="p-8 bg-slate-900/50 rounded-3xl border border-white/5">
                        <div class="text-indigo-500 font-black italic text-xs uppercase mb-4 tracking-widest">Nível: Almirante</div>
                        <h3 class="text-xl font-black italic uppercase mb-3">Probabilidade</h3>
                        <p class="text-slate-400 text-sm font-bold uppercase leading-relaxed opacity-70">
                            IA especialista. Utiliza mapas de calor e heurísticas de probabilidade por célula para prever sua frota.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-20 items-center">
                <div class="order-2 md:order-1">
                    <div class="space-y-12">
                        <div class="flex gap-6 group">
                            <span class="material-symbols-outlined text-5xl text-blue-500 group-hover:scale-110 transition-transform font-bold">rebase_edit</span>
                            <div>
                                <h4 class="text-2xl font-black italic uppercase">Modo Dinâmico</h4>
                                <p class="text-slate-500 font-bold uppercase text-xs mt-2 leading-relaxed">Cada jogador pode mover um navio (uma casa) para qualquer direção antes de realizar o disparo.</p>
                            </div>
                        </div>
                        <div class="flex gap-6 group">
                            <span class="material-symbols-outlined text-5xl text-yellow-500 group-hover:scale-110 transition-transform font-bold">military_tech</span>
                            <div>
                                <h4 class="text-2xl font-black italic uppercase">Medalha Almirante</h4>
                                <p class="text-slate-500 font-bold uppercase text-xs mt-2 leading-relaxed">Recompensa lendária para comandantes que vencerem a batalha sem perder nenhum navio da esquadra.</p>
                            </div>
                        </div>
                        <div class="flex gap-6 group">
                            <span class="material-symbols-outlined text-5xl text-slate-400 group-hover:scale-110 transition-transform font-bold">target</span>
                            <div>
                                <h4 class="text-2xl font-black italic uppercase">Medalha Capitão</h4>
                                <p class="text-slate-500 font-bold uppercase text-xs mt-2 leading-relaxed">Concedida ao atingir a marca crítica de 8 acertos consecutivos no tabuleiro inimigo.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="order-1 md:order-2 bg-gradient-to-br from-blue-900/20 to-slate-900 p-1 rounded-[2.5rem]">
                    <div class="bg-slate-950 rounded-[2.3rem] p-10 border border-white/5">
                        <h3 class="text-xl font-black italic uppercase mb-8 border-b border-white/10 pb-4">Unidades de Combate (10x10)</h3>
                        <ul class="space-y-4">
                            <li class="flex justify-between items-center text-sm font-black uppercase">
                                <span class="text-slate-500 italic">Porta-Aviões (x2)</span>
                                <span class="text-blue-500">6 Casas</span>
                            </li>
                            <li class="flex justify-between items-center text-sm font-black uppercase">
                                <span class="text-slate-500 italic">Navio de Guerra (x2)</span>
                                <span class="text-blue-500">4 Casas</span>
                            </li>
                            <li class="flex justify-between items-center text-sm font-black uppercase">
                                <span class="text-slate-500 italic">Encouraçado (x1)</span>
                                <span class="text-blue-500">3 Casas</span>
                            </li>
                            <li class="flex justify-between items-center text-sm font-black uppercase">
                                <span class="text-slate-500 italic">Submarino (x1)</span>
                                <span class="text-blue-500">1 Casa</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24 bg-slate-900/30">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col items-center mb-16">
                    <span class="text-blue-500 font-black uppercase tracking-[0.4em] text-xs mb-4">Estatísticas de Elite</span>
                    <h2 class="text-5xl font-[1000] italic uppercase tracking-tighter text-center">Hall dos <span class="text-blue-500">Almirantes</span></h2>
                    <div class="h-1.5 w-32 bg-blue-600 mt-6 rounded-full shadow-[0_0_15px_rgba(37,99,235,0.6)]"></div>
                </div>

                <div class="bg-slate-950/50 border-x border-t border-white/5 rounded-t-[2rem] overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[600px]">
                        <thead>
                            <tr class="bg-slate-950 border-b border-white/10 uppercase font-black italic text-xs tracking-widest text-slate-500">
                                <th class="px-8 py-6">Posição</th>
                                <th class="px-8 py-6">Comandante</th>
                                <th class="px-8 py-6 text-center">Vitórias</th>
                                <th class="px-8 py-6 text-center">Medalhas</th>
                                <th class="px-8 py-6 text-right">Precisão</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @php 
                                // Mock de dados para visualização, você integrará com seu RankingController
                                $topPlayers = [
                                    ['name' => 'Almirante_Nelson', 'wins' => 142, 'medals' => 12, 'acc' => '94%'],
                                    ['name' => 'DarkFleet_01', 'wins' => 128, 'medals' => 8, 'acc' => '89%'],
                                    ['name' => 'OceanHunter', 'wins' => 115, 'medals' => 5, 'acc' => '82%'],
                                ];
                            @endphp
                            @foreach($topPlayers as $i => $player)
                            <tr class="group hover:bg-blue-600/5 transition-colors">
                                <td class="px-8 py-6">
                                    <span class="text-2xl font-[1000] italic {{ $i == 0 ? 'text-yellow-500' : 'text-slate-600' }}">#{{ $i + 1 }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-slate-800 border border-white/10 flex items-center justify-center font-black text-blue-500 uppercase">
                                            {{ substr($player['name'], 0, 1) }}
                                        </div>
                                        <span class="font-black italic uppercase tracking-tight text-white group-hover:text-blue-400 transition-colors">{{ $player['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center font-black italic text-xl">{{ $player['wins'] }}</td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex justify-center gap-2">
                                        @for($j=0; $j < min($player['medals'], 3); $j++)
                                            <span class="material-symbols-outlined text-yellow-500 text-sm font-bold">military_tech</span>
                                        @endfor
                                        @if($player['medals'] > 3)
                                            <span class="text-[10px] font-black text-slate-500">+{{ $player['medals'] - 3 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right font-black italic text-blue-500">{{ $player['acc'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bg-slate-950 p-6 rounded-b-[2rem] border-x border-b border-white/5 text-center">
                    <a href="{{ route('ranking.index') }}" class="text-xs font-black uppercase tracking-[0.4em] text-slate-500 hover:text-white transition-colors">Ver Ranking Completo</a>
                </div>
            </div>
        </section>

        <footer class="py-12 border-t border-white/5 text-center bg-slate-950/50">
            <p class="text-[10px] font-black uppercase tracking-[0.8em] text-slate-700 italic">
                ESTRATÉGIA MARÍTIMA • 2026 • UFAPE
            </p>
        </footer>
    </div>
</x-guest-layout>