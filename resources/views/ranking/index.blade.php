<x-app-layout>
<div class="min-h-screen bg-[#0a0f14] text-white py-12 px-4">
    <div class="max-w-5xl mx-auto flex flex-col gap-12">

        {{-- Header --}}
        <div class="text-center">
            <span class="material-symbols-outlined text-6xl text-yellow-400">military_tech</span>
            <h1 class="text-4xl font-black uppercase italic tracking-tighter mt-2">Ranking Naval</h1>
            <p class="text-white/40 text-sm mt-1">Os melhores almirantes dos sete mares</p>
        </div>

        {{-- Ranking Global --}}
        <section>
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-[#137fec] mb-4">🌍 Ranking Global</h2>
            <div class="rounded-3xl border border-white/10 bg-white/5 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-white/40 text-xs uppercase tracking-widest">
                            <th class="text-left p-4">#</th>
                            <th class="text-left p-4">Jogador</th>
                            <th class="text-right p-4">Melhor Pontuação</th>
                            <th class="text-right p-4">Partidas</th>
                            <th class="text-right p-4">Vitórias</th>
                            <th class="text-right p-4">Precisão Média</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($global as $i => $entry)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors
                                   {{ $entry->user_id === auth()->id() ? 'bg-[#137fec]/10' : '' }}">
                            <td class="p-4 font-black text-lg
                                {{ $i === 0 ? 'text-yellow-400' : ($i === 1 ? 'text-slate-300' : ($i === 2 ? 'text-amber-600' : 'text-white/30')) }}">
                                {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : '#'.($i+1))) }}
                            </td>
                            <td class="p-4 font-bold">
                                {{ $entry->user->name }}
                                @if($entry->user_id === auth()->id())
                                    <span class="ml-2 text-[10px] bg-[#137fec]/20 text-[#137fec] px-2 py-0.5 rounded-full uppercase tracking-widest">Você</span>
                                @endif
                            </td>
                            <td class="p-4 text-right font-black text-yellow-400">{{ number_format($entry->melhor_pontuacao) }}</td>
                            <td class="p-4 text-right text-white/60">{{ $entry->total_partidas }}</td>
                            <td class="p-4 text-right text-emerald-400">{{ $entry->vitorias }}</td>
                            <td class="p-4 text-right text-[#137fec]">{{ $entry->precisao_media }}%</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="p-8 text-center text-white/30">Nenhuma partida registrada ainda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Ranking Pessoal --}}
        <section>
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-[#137fec] mb-4">🎯 Minhas Partidas</h2>
            <div class="rounded-3xl border border-white/10 bg-white/5 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-white/10 text-white/40 text-xs uppercase tracking-widest">
                            <th class="text-left p-4">Resultado</th>
                            <th class="text-left p-4">Dificuldade</th>
                            <th class="text-right p-4">Pontuação</th>
                            <th class="text-right p-4">Precisão</th>
                            <th class="text-right p-4">Tiros</th>
                            <th class="text-right p-4">Tempo</th>
                            <th class="text-right p-4">Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pessoal as $entry)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="p-4">
                                @if($entry->venceu)
                                    <span class="text-emerald-400 font-bold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">check_circle</span> Vitória
                                    </span>
                                @else
                                    <span class="text-red-500 font-bold flex items-center gap-1">
                                        <span class="material-symbols-outlined text-base">cancel</span> Derrota
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold uppercase
                                    {{ $entry->dificuldade === 'facil' ? 'bg-emerald-500/20 text-emerald-400' :
                                       ($entry->dificuldade === 'medio' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                                    {{ ucfirst($entry->dificuldade) }}
                                </span>
                            </td>
                            <td class="p-4 text-right font-black text-yellow-400">{{ number_format($entry->pontuacao) }}</td>
                            <td class="p-4 text-right text-[#137fec]">{{ $entry->precisao }}%</td>
                            <td class="p-4 text-right text-white/60">{{ $entry->tiros_dados }}</td>
                            <td class="p-4 text-right text-white/60">{{ gmdate('i:s', $entry->tempo_segundos) }}</td>
                            <td class="p-4 text-right text-white/40 text-xs">{{ $entry->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="p-8 text-center text-white/30">Você ainda não jogou nenhuma partida.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div class="text-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-white/40 hover:text-white transition-colors text-sm">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Voltar ao menu
            </a>
        </div>
    </div>
</div>
</x-app-layout>
