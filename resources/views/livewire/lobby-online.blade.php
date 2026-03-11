<div class="py-12 bg-[#020617] min-h-screen">
    <div class="max-w-4xl mx-auto px-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
            <div>
                <h2 class="text-4xl font-[1000] italic uppercase tracking-tighter text-white">Centro de <span class="text-blue-500">Operações</span></h2>
                <p class="text-slate-500 font-bold uppercase text-xs tracking-widest mt-2">Batalhas em Tempo Real</p>
            </div>
            <button wire:click="criarDesafio" class="bg-blue-600 hover:bg-blue-500 px-8 py-4 rounded-2xl font-black uppercase italic tracking-tighter text-white shadow-[0_6px_0_rgb(30,58,138)] hover:translate-y-[2px] transition-all active:shadow-none active:translate-y-[4px]">
                Criar Novo Desafio
            </button>
        </div>

        <div class="space-y-4">
            @forelse($desafios as $desafio)
                <div class="bg-slate-900/50 border border-white/5 p-6 rounded-[2rem] flex items-center justify-between group hover:border-blue-500/30 transition-all">
                    <div class="flex items-center gap-6">
                        <div class="w-14 h-14 rounded-2xl bg-slate-800 border border-white/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-blue-500 text-3xl">person</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-black uppercase italic text-white">{{ $desafio->jogador1->name }}</h4>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">Aguardando Combate...</span>
                        </div>
                    </div>
                    
                    <button wire:click="aceitarDesafio({{ $desafio->id }})" class="bg-white text-black px-6 py-2 rounded-xl font-black uppercase italic text-xs hover:bg-blue-400 hover:text-white transition-all">
                        Aceitar Desafio
                    </button>
                </div>
            @empty
                <div class="text-center py-20 border-2 border-dashed border-white/5 rounded-[3rem]">
                    <span class="material-symbols-outlined text-6xl text-slate-800 mb-4">radar</span>
                    <p class="text-slate-600 font-black uppercase italic tracking-widest">Nenhum sinal detectado no radar...</p>
                    <p class="text-slate-700 text-xs mt-2 uppercase font-bold">Seja o primeiro a criar um desafio!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>