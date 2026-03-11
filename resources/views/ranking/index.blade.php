<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="min-h-screen bg-[#020617] text-white font-sans">

        {{-- HERO --}}
        <section class="relative pt-14 pb-10 overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(37,99,235,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(37,99,235,0.04)_1px,transparent_1px)] bg-[size:60px_60px] pointer-events-none"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="flex flex-col lg:flex-row items-start lg:items-end justify-between gap-8">
                    <div>
                        <p class="text-blue-500 font-black uppercase tracking-[0.4em] text-xs mb-3">Competição Global</p>
                        <h1 class="text-5xl md:text-7xl font-[1000] italic uppercase tracking-tighter leading-[0.85]">
                            HALL DOS <br><span class="text-blue-500">ALMIRANTES</span>
                        </h1>
                        <div class="h-1 w-20 bg-blue-600 mt-4"></div>
                    </div>

                    {{-- Stats do jogador logado --}}
                    @if($minhaPosicao)
                    <div class="bg-slate-900/80 border border-blue-500/30 rounded-2xl px-8 py-5 flex items-center gap-6 shrink-0">
                        <div class="text-center">
                            <p class="text-4xl font-[1000] italic text-blue-500">#{{ $minhaPosicao }}</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mt-1">Sua Posição</p>
                        </div>
                        <div class="w-px h-12 bg-white/10"></div>
                        <div class="text-center">
                            <p class="text-4xl font-[1000] italic text-white">{{ $minhasPrecisao ?? 0 }}%</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mt-1">Sua Precisão</p>
                        </div>
                        <div class="w-px h-12 bg-white/10"></div>
                        <div class="text-center">
                            <p class="text-4xl font-[1000] italic text-yellow-500">{{ $minhasMedalhas }}</p>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mt-1">Medalhas</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- PÓDIO TOP 3 --}}
        @if($ranking->count() >= 3)
        <section class="max-w-3xl mx-auto px-6 pb-10">
            <div class="grid grid-cols-3 gap-4 items-end">

                {{-- 2º lugar --}}
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-800 border-2 border-slate-600 flex items-center justify-center font-black text-slate-300 text-2xl uppercase mb-3">
                        {{ substr($ranking[1]->user->name, 0, 1) }}
                    </div>
                    <p class="font-black italic uppercase text-sm tracking-tight mb-0.5 text-center truncate max-w-[100px]">{{ $ranking[1]->user->name }}</p>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-3">{{ number_format($ranking[1]->pontuacao_total) }} pts</p>
                    <div class="w-full bg-slate-800 border border-slate-600 rounded-t-2xl py-6 text-center">
                        <p class="text-5xl font-[1000] italic text-slate-400">#2</p>
                    </div>
                </div>

                {{-- 1º lugar --}}
                <div class="flex flex-col items-center -mt-8">
                    <div class="relative mb-3">
                        <span class="material-symbols-outlined text-yellow-500 text-3xl absolute -top-4 left-1/2 -translate-x-1/2 font-bold">emoji_events</span>
                        <div class="w-[72px] h-[72px] rounded-2xl bg-yellow-500/10 border-2 border-yellow-500/50 flex items-center justify-center font-black text-yellow-400 text-3xl uppercase mt-4">
                            {{ substr($ranking[0]->user->name, 0, 1) }}
                        </div>
                    </div>
                    <p class="font-black italic uppercase tracking-tight mb-0.5 text-center truncate max-w-[110px]">{{ $ranking[0]->user->name }}</p>
                    <p class="text-[10px] font-black uppercase tracking-widest text-yellow-500/70 mb-3">{{ number_format($ranking[0]->pontuacao_total) }} pts</p>
                    <div class="w-full bg-yellow-500/10 border border-yellow-500/30 rounded-t-2xl py-10 text-center shadow-[0_0_40px_rgba(234,179,8,0.1)]">
                        <p class="text-6xl font-[1000] italic text-yellow-500">#1</p>
                    </div>
                </div>

                {{-- 3º lugar --}}
                <div class="flex flex-col items-center">
                    <div class="w-14 h-14 rounded-2xl bg-slate-800 border-2 border-orange-700/40 flex items-center justify-center font-black text-orange-400/70 text-2xl uppercase mb-3">
                        {{ substr($ranking[2]->user->name, 0, 1) }}
                    </div>
                    <p class="font-black italic uppercase text-sm tracking-tight mb-0.5 text-center truncate max-w-[100px]">{{ $ranking[2]->user->name }}</p>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-3">{{ number_format($ranking[2]->pontuacao_total) }} pts</p>
                    <div class="w-full bg-slate-800/60 border border-orange-700/20 rounded-t-2xl py-4 text-center">
                        <p class="text-4xl font-[1000] italic text-orange-600/70">#3</p>
                    </div>
                </div>

            </div>
        </section>
        @endif

        {{-- TABELA COMPLETA --}}
        <section class="max-w-7xl mx-auto px-6 pb-16">

            {{-- Filtros --}}
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Ranking <span class="text-blue-500">Completo</span></h2>
                    <div class="h-0.5 w-12 bg-blue-600 mt-2"></div>
                </div>
                <div class="flex gap-2">
                    @foreach(['geral' => 'Geral', 'mensal' => 'Mensal', 'semanal' => 'Semanal'] as $key => $label)
                    <a href="{{ route('ranking.index', ['filtro' => $key]) }}"
                       class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all
                              {{ $filtro === $key ? 'bg-blue-600 text-white shadow-[0_4px_0_rgb(30,58,138)]' : 'bg-slate-900 border border-white/5 text-slate-400 hover:text-white' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="bg-slate-950/50 border border-white/5 rounded-[2rem] overflow-hidden">

                @if($ranking->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <span class="material-symbols-outlined text-6xl text-slate-800 font-bold mb-4">anchor</span>
                    <p class="text-slate-600 font-black italic uppercase text-sm">Nenhuma batalha registrada.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[640px]">
                        <thead>
                            <tr class="bg-slate-950 border-b border-white/10 uppercase font-black italic text-[10px] tracking-widest text-slate-500">
                                <th class="px-8 py-5">Posição</th>
                                <th class="px-8 py-5">Comandante</th>
                                <th class="px-8 py-5 text-center">Pontuação</th>
                                <th class="px-8 py-5 text-center">V / D</th>
                                <th class="px-8 py-5 text-center">Medalhas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($ranking as $i => $player)
                            <tr class="group hover:bg-blue-600/5 transition-colors {{ Auth::id() === $player->user_id ? 'bg-blue-600/10 border-l-2 border-blue-500' : '' }}">

                                {{-- Posição --}}
                                <td class="px-8 py-5">
                                    @if($i === 0)
                                        <span class="text-2xl font-[1000] italic text-yellow-500">#1</span>
                                    @elseif($i === 1)
                                        <span class="text-2xl font-[1000] italic text-slate-400">#2</span>
                                    @elseif($i === 2)
                                        <span class="text-2xl font-[1000] italic text-orange-600">#3</span>
                                    @else
                                        <span class="text-lg font-[1000] italic text-slate-600">#{{ $i + 1 }}</span>
                                    @endif
                                </td>

                                {{-- Nome --}}
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-800 border border-white/10 flex items-center justify-center font-black text-blue-500 uppercase text-sm shrink-0">
                                            {{ substr($player->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black italic uppercase tracking-tight text-white group-hover:text-blue-400 transition-colors text-sm">
                                                {{ $player->user->name }}
                                                @if(Auth::id() === $player->user_id)
                                                    <span class="ml-2 text-[9px] bg-blue-600/30 text-blue-400 px-2 py-0.5 rounded-full uppercase tracking-widest font-black">Você</span>
                                                @endif
                                            </p>
                                            <p class="text-[10px] text-slate-600 font-bold uppercase">{{ $player->total_partidas }} partidas</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Pontuação --}}
                                <td class="px-8 py-5 text-center">
                                    <span class="font-[1000] italic text-lg text-blue-400">{{ number_format($player->pontuacao_total) }}</span>
                                </td>

                                {{-- V/D --}}
                                <td class="px-8 py-5 text-center">
                                    <span class="font-black italic text-sm">
                                        <span class="text-white">{{ $player->vitorias }}</span>
                                        <span class="text-slate-600 mx-1">/</span>
                                        <span class="text-slate-500">{{ $player->derrotas }}</span>
                                    </span>
                                </td>

                                {{-- Medalhas --}}
                                <td class="px-8 py-5 text-center">
                                    <div class="flex justify-center gap-1 items-center">
                                        @forelse($player->tipos_medalhas ?? [] as $tipo)
                                            @php
                                                $labels = [
                                                    'almirante'          => 'Almirante',
                                                    'capitao_mar_guerra' => 'Capitão de Mar e Guerra',
                                                    'capitao'            => 'Capitão',
                                                    'marinheiro'         => 'Marinheiro',
                                                ];
                                            @endphp
                                            <div class="relative group/medal">
                                                <span class="material-symbols-outlined text-yellow-500 text-sm font-bold cursor-default">military_tech</span>
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 bg-slate-800 border border-white/10 rounded-xl
                                                            text-[10px] font-black uppercase tracking-widest text-white whitespace-nowrap
                                                            opacity-0 group-hover/medal:opacity-100 pointer-events-none transition-opacity z-50">
                                                    {{ $labels[$tipo] ?? $tipo }}
                                                </div>
                                            </div>
                                        @empty
                                            <span class="text-slate-700 text-[10px] font-black">—</span>
                                        @endforelse
                                        @if(($player->total_medalhas ?? 0) > count($player->tipos_medalhas ?? []))
                                            <span class="text-[10px] font-black text-slate-500">+{{ $player->total_medalhas - count($player->tipos_medalhas) }}</span>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

            </div>
        </section>

    </div>
</x-app-layout>
