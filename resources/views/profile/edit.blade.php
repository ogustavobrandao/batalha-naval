<x-app-layout>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <div class="min-h-screen bg-[#020617] text-white font-sans">

        {{-- HERO --}}
        <section class="relative pt-14 pb-10 overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(37,99,235,0.04)_1px,transparent_1px),linear-gradient(90deg,rgba(37,99,235,0.04)_1px,transparent_1px)] bg-[size:60px_60px] pointer-events-none"></div>
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-3xl mx-auto px-6 relative z-10">
                <p class="text-blue-500 font-black uppercase tracking-[0.4em] text-xs mb-3">Conta</p>
                <h1 class="text-5xl md:text-7xl font-[1000] italic uppercase tracking-tighter leading-[0.85]">
                    CONFIGU<br><span class="text-blue-500">RAÇÕES</span>
                </h1>
                <div class="h-1 w-20 bg-blue-600 mt-4"></div>
                <p class="text-slate-400 font-bold uppercase italic text-sm mt-5 opacity-70">
                    Gerencie suas informações pessoais e preferências de conta.
                </p>
            </div>
        </section>

        <div class="max-w-3xl mx-auto px-6 pb-16 flex flex-col gap-5">

            {{-- Alertas de sucesso --}}
            @if(session('status') === 'profile-updated')
                <div class="flex items-center gap-4 p-5 bg-green-500/10 border border-green-500/30 rounded-2xl">
                    <span class="material-symbols-outlined text-green-400 font-bold">check_circle</span>
                    <p class="text-green-400 font-black uppercase text-sm">Perfil atualizado com sucesso!</p>
                </div>
            @endif
            @if(session('status') === 'password-updated')
                <div class="flex items-center gap-4 p-5 bg-green-500/10 border border-green-500/30 rounded-2xl">
                    <span class="material-symbols-outlined text-green-400 font-bold">check_circle</span>
                    <p class="text-green-400 font-black uppercase text-sm">Senha alterada com sucesso!</p>
                </div>
            @endif

            {{-- DADOS PESSOAIS --}}
            <div class="p-8 bg-slate-900/80 border border-white/5 rounded-3xl">
                <div class="flex items-center gap-4 mb-6">
                    <span class="material-symbols-outlined text-4xl text-blue-500 font-bold">person</span>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-500">Identificação</p>
                        <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Dados Pessoais</h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="flex flex-col gap-4">
                    @csrf
                    @method('PATCH')

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Nome de Comandante</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                            class="w-full rounded-2xl bg-slate-950 border border-white/5 px-5 py-4 text-white font-bold placeholder-slate-600 focus:outline-none focus:border-blue-500/50 transition-colors"
                            required />
                        @error('name')
                            <span class="text-red-400 text-[10px] font-black uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                            class="w-full rounded-2xl bg-slate-950 border border-white/5 px-5 py-4 text-white font-bold placeholder-slate-600 focus:outline-none focus:border-blue-500/50 transition-colors"
                            required />
                        @error('email')
                            <span class="text-red-400 text-[10px] font-black uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-10 py-4 bg-blue-600 text-white font-black uppercase italic tracking-tighter rounded-2xl shadow-[0_6px_0_rgb(30,58,138)] hover:translate-y-[2px] hover:shadow-[0_4px_0_rgb(30,58,138)] active:translate-y-[4px] active:shadow-none transition-all">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>

            {{-- ALTERAR SENHA --}}
            <div class="p-8 bg-slate-900/80 border border-white/5 rounded-3xl">
                <div class="flex items-center gap-4 mb-6">
                    <span class="material-symbols-outlined text-4xl text-blue-500 font-bold">lock</span>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-blue-500">Segurança</p>
                        <h2 class="text-xl font-[1000] italic uppercase tracking-tighter">Alterar Senha</h2>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-4">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Senha Atual</label>
                        <input type="password" name="current_password" placeholder="••••••••"
                            class="w-full rounded-2xl bg-slate-950 border border-white/5 px-5 py-4 text-white font-bold placeholder-slate-600 focus:outline-none focus:border-blue-500/50 transition-colors" />
                        @error('current_password', 'updatePassword')
                            <span class="text-red-400 text-[10px] font-black uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Nova Senha</label>
                            <input type="password" name="password" placeholder="••••••••"
                                class="w-full rounded-2xl bg-slate-950 border border-white/5 px-5 py-4 text-white font-bold placeholder-slate-600 focus:outline-none focus:border-blue-500/50 transition-colors" />
                            @error('password', 'updatePassword')
                                <span class="text-red-400 text-[10px] font-black uppercase">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Confirmar Nova Senha</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••"
                                class="w-full rounded-2xl bg-slate-950 border border-white/5 px-5 py-4 text-white font-bold placeholder-slate-600 focus:outline-none focus:border-blue-500/50 transition-colors" />
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-10 py-4 bg-blue-600 text-white font-black uppercase italic tracking-tighter rounded-2xl shadow-[0_6px_0_rgb(30,58,138)] hover:translate-y-[2px] hover:shadow-[0_4px_0_rgb(30,58,138)] active:translate-y-[4px] active:shadow-none transition-all">
                            Alterar Senha
                        </button>
                    </div>
                </form>
            </div>

            {{-- ZONA DE PERIGO --}}
            <div class="p-8 bg-red-950/20 border border-red-500/20 rounded-3xl">
                <div class="flex items-center gap-4 mb-4">
                    <span class="material-symbols-outlined text-4xl text-red-500 font-bold">warning</span>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] text-red-500">Irreversível</p>
                        <h2 class="text-xl font-[1000] italic uppercase tracking-tighter text-red-400">Zona de Perigo</h2>
                    </div>
                </div>
                <p class="text-slate-500 text-[10px] font-bold uppercase leading-relaxed mb-6">
                    Ao excluir sua conta, todos os seus dados, histórico de partidas, medalhas e posição no ranking serão permanentemente removidos. Esta ação não pode ser desfeita.
                </p>

                <form method="POST" action="{{ route('profile.destroy') }}" class="flex flex-col gap-4"
                      onsubmit="return confirm('Tem certeza? Esta ação é irreversível e apagará todos os seus dados.')">
                    @csrf
                    @method('DELETE')

                    <div class="flex flex-col gap-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-500">Confirme sua senha para excluir</label>
                        <input type="password" name="password" placeholder="••••••••"
                            class="w-full rounded-2xl bg-slate-950 border border-red-500/20 px-5 py-4 text-white font-bold placeholder-slate-600 focus:outline-none focus:border-red-500/50 transition-colors" />
                        @error('password', 'userDeletion')
                            <span class="text-red-400 text-[10px] font-black uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="px-10 py-4 bg-red-500/10 border border-red-500/30 text-red-400 font-black uppercase italic tracking-tighter rounded-2xl hover:bg-red-500/20 hover:border-red-500/50 transition-all">
                            Excluir Minha Conta
                        </button>
                    </div>
                </form>
            </div>

            {{-- BOTÃO VOLTAR --}}
            <div class="flex justify-center pt-2">
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-3 px-10 py-4 bg-blue-600 text-white font-black uppercase italic tracking-tighter rounded-2xl shadow-[0_6px_0_rgb(30,58,138)] hover:translate-y-[2px] hover:shadow-[0_4px_0_rgb(30,58,138)] active:translate-y-[4px] active:shadow-none transition-all">
                    <span class="material-symbols-outlined font-bold">arrow_back</span>
                    Voltar ao Menu
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
