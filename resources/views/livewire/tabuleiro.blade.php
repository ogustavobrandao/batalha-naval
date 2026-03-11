<div x-data x-on:ia-turno.window="setTimeout(() => $wire.turnoIA(), 1000)" class="relative flex h-screen w-full flex-col bg-[#0a0f14] font-display text-white overflow-hidden text-sm md:text-base">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <style>
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        .isometric-board {
            transform: perspective(1200px) rotateX(60deg) rotateZ({{ $anguloRadar }}deg);
            transform-style: preserve-3d;
            transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* overflow visible para os navios 3D não serem cortados */
        .grid-cell {
            border: 1px solid rgba(19, 127, 236, 0.1);
            transform-style: flat;
            position: relative !important;
            aspect-ratio: 1/1;
            overflow: visible !important;
        }

        /* BLOCOS 3D */
        .ship-block {
            background: var(--ship-color) !important;
            transform: translateZ(20px);
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        .ship-block::before {
            content: ''; position: absolute; top: 0; left: 100%; width: 20px; height: 100%;
            background: var(--ship-color); filter: brightness(0.7);
            transform-origin: left; transform: rotateY(90deg);
        }
        .ship-block::after {
            content: ''; position: absolute; top: 100%; left: 0; width: 100%; height: 20px;
            background: var(--ship-color); filter: brightness(0.5);
            transform-origin: top; transform: rotateX(-90deg);
        }

        .ship-block > div {
            position: absolute;
            inset: 0;
            z-index: 999;
        }

        /* ESTADOS DE COMBATE */
        .cell-hit  { background: rgba(239, 68, 68, 0.55) !important; box-shadow: 0 0 15px #ef4444; }
        .cell-miss { background: rgba(255, 255, 255, 0.1) !important; }
        .cell-sunk { background: rgba(15, 23, 42, 0.85) !important; opacity: 0.6; }

        /* SETA DIRECIONAL */
        .direction-arrow {
            position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
            transform: rotateZ(calc(-1 * {{ $anguloRadar }}deg)) translateZ(30px);
            pointer-events: none;
        }
        .arrow-icon {
            font-size: 40px !important; color: #137fec;
            filter: drop-shadow(0 0 10px #137fec);
            transform: rotate({{ $direcaoNavio * 90 }}deg);
            transition: transform 0.3s ease;
        }
    </style>

    @if($fase === 'posicionamento')
        <header class="flex items-center justify-between border-b border-white/5 px-10 py-5 bg-[#0a0f14]/95 backdrop-blur-xl z-50">
            <button wire:click="girarRadar" class="flex items-center gap-4 px-8 py-2 bg-[#137fec]/10 border border-[#137fec]/30 rounded-full hover:bg-[#137fec]/20 transition-all">
                <span class="material-symbols-outlined text-xl text-[#137fec]">sync</span>
                <span class="text-xs font-black uppercase tracking-[0.3em]">Visão Orbital</span>
            </button>
        </header>
    @endif

    @php
        $souJogador1 = Auth::id() === $partida->criado_por;
        $euEstouPronto = $souJogador1 ? $partida->jogador1_pronto : $partida->jogador2_pronto;
        $oponentePronto = $souJogador1 ? $partida->jogador2_pronto : $partida->jogador1_pronto;
    @endphp

    {{-- Overlay de Espera: Só aparece se EU confirmei e o OUTRO não --}}
    @if($partida->eMultiplayer() && $fase === 'posicionamento' && $euEstouPronto && !$oponentePronto)
        <div class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/80 backdrop-blur-md">
            <div class="text-center p-12 border border-blue-500/30 rounded-[3rem] bg-slate-900 shadow-2xl">
                <div class="w-20 h-20 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto mb-6"></div>
                <h3 class="text-2xl font-black italic uppercase text-white tracking-tighter">Implantação Confirmada</h3>
                <p class="text-slate-500 font-bold uppercase text-xs mt-2 tracking-widest">Aguardando o oponente posicionar a esquadra...</p>
            </div>
        </div>
    @endif

    {{-- Overlay de Turno do Inimigo: Durante a Batalha --}}
    @if($fase === 'batalha' && $bloquearAcoes)
        <div class="fixed inset-x-0 top-32 z-40 pointer-events-none flex justify-center">
            <div class="bg-red-500/10 border border-red-500/20 px-8 py-3 rounded-full backdrop-blur-md shadow-[0_0_30px_rgba(239,68,68,0.2)]">
                <span class="text-red-500 font-black uppercase text-xs tracking-[0.4em] animate-pulse italic">
                    Atenção: Turno do Inimigo
                </span>
            </div>
        </div>
    @endif

    <main class="flex-1 flex overflow-hidden">
        <div class="flex-1 relative flex items-center justify-center p-20 bg-[radial-gradient(circle_at_center,_#137fec10_0%,_transparent_75%)]">
            @if($fase === 'finalizada')
        <div class="absolute inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="flex flex-col items-center gap-8 rounded-3xl border border-white/10 bg-[#0a0f14] p-16 text-center shadow-2xl max-w-lg w-full mx-4">

                @php $ranking = \App\Models\Ranking::where('partida_id', $partida->id)->where('user_id', auth()->id())->latest()->first(); @endphp
                @if($ranking && $ranking->venceu)
                    <span class="material-symbols-outlined text-8xl text-yellow-400 animate-bounce">military_tech</span>
                    <h2 class="text-5xl font-black uppercase italic tracking-tighter text-yellow-400">Vitória!</h2>
                    <p class="text-white/50 text-sm">A frota inimiga foi completamente destruída.</p>
                @else
                    <span class="material-symbols-outlined text-8xl text-red-500">skull</span>
                    <h2 class="text-5xl font-black uppercase italic tracking-tighter text-red-500">Derrota</h2>
                    <p class="text-white/50 text-sm">Sua frota foi afundada pelo inimigo.</p>
                @endif

                @if($ranking)
                <div class="grid grid-cols-2 gap-4 w-full mt-2">
                    <div class="rounded-2xl bg-white/5 p-4 border border-white/10">
                        <p class="text-white/40 text-xs uppercase tracking-widest">Pontuação</p>
                        <p class="text-3xl font-black text-white mt-1">{{ number_format($ranking->pontuacao) }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-4 border border-white/10">
                        <p class="text-white/40 text-xs uppercase tracking-widest">Precisão</p>
                        <p class="text-3xl font-black text-white mt-1">{{ $ranking->precisao }}%</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-4 border border-white/10">
                        <p class="text-white/40 text-xs uppercase tracking-widest">Tiros</p>
                        <p class="text-3xl font-black text-white mt-1">{{ $ranking->tiros_dados }}</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-4 border border-white/10">
                        <p class="text-white/40 text-xs uppercase tracking-widest">Tempo</p>
                        <p class="text-3xl font-black text-white mt-1">{{ gmdate('i:s', $ranking->tempo_segundos) }}</p>
                    </div>
                </div>
                @endif

                <div class="flex gap-4 w-full mt-2">
                    <a href="{{ route('ranking.index') }}"
                       class="flex-1 py-4 rounded-2xl font-black uppercase tracking-widest text-sm bg-white/5 border border-white/10 text-white hover:bg-white/10 transition-all text-center">
                        Ver Ranking
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="flex-1 py-4 rounded-2xl font-black uppercase tracking-widest text-sm bg-[#137fec] text-white hover:bg-[#137fec]/80 transition-all shadow-[0_0_30px_#137fec50] text-center">
                        Menu Principal
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if($fase === 'posicionamento')
        <div class="relative w-[550px] h-[550px] isometric-board grid grid-cols-10 grid-rows-10 bg-[#121a21]/95 p-3 border border-white/10 shadow-2xl">
                    @foreach($meuTabuleiro as $r => $linha)
    @foreach($linha as $c => $celula)
        <div wire:key="pos-{{ $r }}-{{ $c }}"
             wire:click="clicarCelula({{ $r }}, {{ $c }})"
             style="--ship-color: {{ $celula['cor'] ?? 'transparent' }}; color: {{ $celula['cor'] ?? 'transparent' }}; z-index: {{ $r + $c }}; position: relative;"
            class="grid-cell {{ isset($celula['ship_id']) ? 'ship-block cursor-alias' : 'hover:bg-[#137fec]/10 cursor-pointer' }} group">

            @if(isset($celula['ship_id']))
                @include('partials.navio-segmento', [
                    'tipo'    => $celula['navio'],
                    'parte'   => $celula['parte'] ?? 0,
                    'tamanho' => $celula['tamanho'] ?? 1,
                    'direcao' => $celula['direcao'] ?? 0,
                    'status'  => $celula['status'],
                ])
            @endif

            @if($navioSelecionado && !isset($celula['navio']))
                <div class="direction-arrow opacity-0 group-hover:opacity-100">
                    <span class="material-symbols-outlined arrow-icon">arrow_upward</span>
                </div>
            @endif
        </div>
        @endforeach
    @endforeach
                </div>
            @else
                {{-- FASE DE BATALHA --}}
                <div class="flex gap-16 items-center scale-90">
                    {{-- Tabuleiro do Jogador (Radar Amigo) --}}
                    <div class="flex flex-col items-center gap-6">
                        <h3 class="text-blue-400 font-bold uppercase tracking-widest text-xs">Radar Amigo</h3>
                        <div class="grid grid-cols-10 w-[420px] h-[420px] bg-black/40 p-1 border border-blue-500/20">
                            @foreach($meuTabuleiro as $r => $linha)
                        @foreach($linha as $c => $celula)
                            <div wire:key="meu-{{ $r }}-{{ $c }}"
                                wire:click="{{ ($fase === 'batalha' && ($partida->modo ?? '') === 'dinamico' && !$jaMoveuNesteTurno) ? "selecionarNavioParaMover('".($celula['ship_id'] ?? '')."')" : '' }}"
                                class="grid-cell relative
                                {{ ($navioParaMover === ($celula['ship_id'] ?? null)) ? 'ring-2 ring-yellow-400 z-30 shadow-[0_0_15px_#facc15]' : '' }}
                                {{ $celula['status'] === 'acerto'  ? 'cell-hit'  : '' }}
                                {{ $celula['status'] === 'erro'     ? 'cell-miss' : '' }}
                                {{ $celula['status'] === 'afundado' ? 'cell-sunk' : '' }}">

                                @if(isset($celula['ship_id']) && in_array($celula['status'], ['posicionado','acerto','afundado']))
                                    @include('partials.navio-segmento', [
                                        'tipo'    => $celula['navio'],
                                        'parte'   => $celula['parte'] ?? 0,
                                        'tamanho' => $celula['tamanho'] ?? 1,
                                        'direcao' => $celula['direcao'] ?? 0,
                                        'status'  => $celula['status'],
                                    ])
                                @endif

                                @if($celula['status'] === 'acerto')
                                    <span class="absolute inset-0 flex items-center justify-center z-20">
                                        <span class="material-symbols-outlined text-white text-base drop-shadow-lg">local_fire_department</span>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                        </div>
                    </div>

                    {{-- Tabuleiro do Inimigo (Alvo) --}}
                    <div class="flex flex-col items-center gap-6">
                        <h3 class="text-red-500 font-bold uppercase tracking-widest text-xs">Fogo no Inimigo</h3>
                        <div class="grid grid-cols-10 w-[420px] h-[420px] bg-black/40 p-1 border border-red-500/20 shadow-xl">
                            @foreach($tabuleiroOponente as $r => $linha)
                                @foreach($linha as $c => $celula)
                                    <div wire:key="op-{{ $r }}-{{ $c }}"
                                        wire:click="atirar({{ $r }}, {{ $c }})"
                                        style="color: {{ $celula['cor'] ?? 'transparent' }};"
                                        class="grid-cell cursor-crosshair hover:bg-red-500/10
                                        {{ $celula['status'] === 'acerto'  ? 'cell-hit'  : '' }}
                                        {{ $celula['status'] === 'erro'     ? 'cell-miss' : '' }}
                                        {{ $celula['status'] === 'afundado' ? 'cell-sunk' : '' }}">

                                        @if(isset($celula['ship_id']) && in_array($celula['status'], ['acerto','afundado']))
                                            @include('partials.navio-segmento', [
                                                'tipo'    => $celula['navio'],
                                                'parte'   => $celula['parte'] ?? 0,
                                                'tamanho' => $celula['tamanho'] ?? 1,
                                                'direcao' => $celula['direcao'] ?? 0,
                                                'status'  => $celula['status'],
                                            ])
                                        @endif

                                        @if($celula['status'] === 'acerto')
                                            <span class="absolute inset-0 flex items-center justify-center z-20">
                                                <span class="material-symbols-outlined text-white text-base drop-shadow-lg">local_fire_department</span>
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- BARRA LATERAL --}}
        <aside class="w-[450px] bg-[#0a0f14] border-l border-white/5 p-10 flex flex-col gap-10 shadow-2xl z-50">
            @if($fase === 'posicionamento')
                <div class="flex flex-col gap-4">
                    @foreach($naviosDisponiveis as $chave => $dadosNavio)
                    <div wire:click="selecionarNavio('{{ $chave }}')"
                         class="group relative flex items-center gap-6 p-6 rounded-3xl border transition-all cursor-pointer
                         {{ $navioSelecionado === $chave ? 'border-[#137fec] bg-[#137fec]/10' : ($statusFrota[$chave] == 0 ? 'opacity-20 grayscale' : 'bg-white/5 border-transparent hover:border-white/10') }}">

                        <div class="w-14 h-14 flex items-center justify-center rounded-2xl" style="background: {{ $dadosNavio['cor'] }}">
                            <span class="material-symbols-outlined text-white text-3xl font-bold">directions_boat</span>
                        </div>

                        <div class="flex-1">
                            <p class="font-bold text-xl uppercase tracking-tighter">{{ $dadosNavio['nome'] }}</p>
                            <p class="text-white/40 text-[10px] font-black uppercase mt-1">Disponíveis: {{ $statusFrota[$chave] }}</p>
                        </div>

                        @if($navioSelecionado === $chave)
                            <button wire:click.stop="girarNavio" class="text-[#137fec] p-2 bg-white/5 rounded-full hover:scale-125 transition-all">
                                <span class="material-symbols-outlined text-3xl">rotate_right</span>
                            </button>
                        @endif
                    </div>
                    @endforeach
                </div>

                <button wire:click="iniciarBatalha" {{ array_sum($statusFrota) > 0 ? 'disabled' : '' }}
                        class="w-full py-6 rounded-3xl font-black uppercase tracking-[0.3em] transition-all
                        {{ array_sum($statusFrota) === 0 ? 'bg-[#137fec] text-white shadow-[0_0_50px_#137fec60] scale-105' : 'bg-white/5 text-white/20 cursor-not-allowed' }}">
                    Confirmar Implantação
                </button>
            @else
                <div class="flex-1 flex flex-col justify-center items-center text-center gap-6">
                    <span class="material-symbols-outlined text-6xl text-red-500 animate-pulse">target</span>
                    <h4 class="text-2xl font-black uppercase italic">Combate Ativo</h4>
                    <p class="text-white/40 text-sm mt-2 max-w-[250px]">Selecione as coordenadas no radar inimigo para disparar.</p>

                    @if(($partida->modo ?? '') === 'dinamico' && !$jaMoveuNesteTurno)
                        <div class="mt-8 p-6 bg-white/5 rounded-3xl border border-[#137fec]/20">
                            <h5 class="text-[#137fec] font-black text-xs uppercase tracking-widest text-center mb-6">Manobra Tática</h5>

                            @if($navioParaMover)
                                <div class="grid grid-cols-3 gap-3 max-w-[180px] mx-auto">
                                    <button wire:click="moverNavio('norte')" class="col-start-2 p-3 bg-[#137fec]/10 hover:bg-[#137fec] rounded-2xl transition-all">
                                        <span class="material-symbols-outlined">keyboard_arrow_up</span>
                                    </button>
                                    <button wire:click="moverNavio('oeste')" class="col-start-1 p-3 bg-[#137fec]/10 hover:bg-[#137fec] rounded-2xl transition-all">
                                        <span class="material-symbols-outlined">keyboard_arrow_left</span>
                                    </button>
                                    <button wire:click="moverNavio('sul')" class="col-start-2 p-3 bg-[#137fec]/10 hover:bg-[#137fec] rounded-2xl transition-all">
                                        <span class="material-symbols-outlined">keyboard_arrow_down</span>
                                    </button>
                                    <button wire:click="moverNavio('leste')" class="col-start-3 p-3 bg-[#137fec]/10 hover:bg-[#137fec] rounded-2xl transition-all">
                                        <span class="material-symbols-outlined">keyboard_arrow_right</span>
                                    </button>
                                </div>
                                <p class="text-[10px] text-white/40 mt-6 text-center italic">Movimente o navio selecionado em 1 casa</p>
                            @else
                                <div class="flex flex-col items-center gap-4 py-4">
                                    <span class="material-symbols-outlined text-white/20 text-4xl">ads_click</span>
                                    <p class="text-[10px] text-white/50 text-center">Selecione um navio no <br><strong class="text-blue-400">Radar Amigo</strong> para mover</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
        </aside>
    </main>
</div>
