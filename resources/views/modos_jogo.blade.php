<x-app-layout>
    <div class="relative flex min-h-screen w-full flex-col items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-4xl">
            <h1 class="mb-8 text-center text-4xl font-bold tracking-tight text-white sm:text-5xl">Seleção de Modo de Jogo</h1>
            <div class="grid w-full grid-cols-1 gap-6 md:grid-cols-2 lg:gap-8">
                <!-- Card 1: Modo Campanha (Selected) -->
                <a href="{{ route('partida.create') }}">
                    <div
                        class="group relative cursor-pointer overflow-hidden rounded-xl border-2 border-primary bg-[#192633] shadow-[0_0_15px_rgba(19,127,236,0.5)] transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_25px_rgba(19,127,236,0.6)]">
                        <div class="flex h-full flex-col items-start p-6">
                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-lg bg-primary/10">
                                <span class="material-symbols-outlined text-4xl text-primary">stadia_controller</span>
                            </div>
                            <h2 class="text-2xl font-bold leading-tight tracking-[-0.015em] text-white">Modo Campanha</h2>
                            <p class="mt-2 text-base font-normal leading-relaxed text-[#92adc9]">
                                Jogue o clássico Batalha Naval por turnos contra um oponente de IA. Posicione sua frota estrategicamente
                                e afunde os navios inimigos para vencer.
                            </p>
                        </div>
                    </div>
                </a>
                <!-- Card 2: Modo Dinâmico -->
                <a href="{{ route('partida.create', ['modo' => 'dinamico']) }}">
                    <div class="group relative cursor-pointer overflow-hidden rounded-xl border-2 border-gray-700 bg-[#192633]/60 transition-all duration-300 hover:scale-[1.02] hover:border-primary/50 hover:bg-[#192633]">
                        <div class="flex h-full flex-col items-start p-6">
                            <div
                                class="mb-4 flex h-16 w-16 items-center justify-center rounded-lg bg-gray-700/50 transition-colors group-hover:bg-primary/10">
                                <span
                                    class="material-symbols-outlined text-4xl text-gray-400 transition-colors group-hover:text-primary">moving</span>
                            </div>
                            <h2 class="text-2xl font-bold leading-tight tracking-[-0.015em] text-white">Modo Dinâmico</h2>
                            <p class="mt-2 text-base font-normal leading-relaxed text-[#92adc9]">
                                Uma variante única onde os navios podem se mover um espaço antes de cada ataque, adicionando uma nova
                                camada de estratégia e imprevisibilidade ao combate.
                            </p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('lobby.online') }}">
                    <div class="group relative cursor-pointer overflow-hidden rounded-xl border-2 border-indigo-500 bg-indigo-950/20 shadow-[0_0_15px_rgba(99,102,241,0.4)] transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_25px_rgba(99,102,241,0.6)]">
                        <div class="flex h-full flex-col items-start p-6">
                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-lg bg-indigo-500/10">
                                <span class="material-symbols-outlined text-4xl text-indigo-400">public</span>
                            </div>
                            <div class="absolute top-4 right-4 bg-indigo-500 text-[10px] font-black px-3 py-1 rounded-full uppercase italic">PvP Real-Time</div>
                            <h2 class="text-2xl font-bold leading-tight tracking-[-0.015em] text-white italic uppercase">Batalha Online</h2>
                            <p class="mt-2 text-base font-normal leading-relaxed text-[#92adc9]">
                                Desafie comandantes reais em tempo real. Teste suas táticas contra mentes humanas no servidor global.
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
