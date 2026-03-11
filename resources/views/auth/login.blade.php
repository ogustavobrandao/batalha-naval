<x-guest-layout>
    <div class="min-h-screen bg-[#020617] flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                
                <h2 class="text-xl font-black italic uppercase text-slate-400 tracking-widest">Acesso ao Centro de Comando</h2>
            </div>

            <div class="bg-slate-900/50 border-2 border-white/5 rounded-[2rem] p-8 backdrop-blur-xl shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-blue-500 to-transparent"></div>
                
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-black uppercase tracking-[0.2em] text-blue-400 mb-2 italic">Identificação (Email)</label>
                        <x-text-input id="email" class="block w-full bg-slate-950 border-white/10 text-white rounded-xl focus:border-blue-500 focus:ring-blue-500/20" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-black uppercase tracking-[0.2em] text-blue-400 italic">Código de Acesso</label>
                            @if (Route::has('password.request'))
                                <a class="text-[10px] font-bold uppercase text-slate-500 hover:text-white transition-colors" href="{{ route('password.request') }}">Esqueceu?</a>
                            @endif
                        </div>
                        <x-text-input id="password" class="block w-full bg-slate-950 border-white/10 text-white rounded-xl focus:border-blue-500 focus:ring-blue-500/20" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-white/10 bg-slate-950 text-blue-600 focus:ring-blue-500" name="remember">
                        <span class="ms-2 text-xs font-black uppercase tracking-widest text-slate-500 italic">Manter Conectado</span>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 py-4 rounded-xl font-black uppercase italic tracking-tighter text-white shadow-[0_6px_0_rgb(30,58,138)] hover:translate-y-[2px] hover:shadow-[0_4px_0_rgb(30,58,138)] active:translate-y-[4px] active:shadow-none transition-all">
                        Autenticar Comandante
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-white/5 text-center">
                    <p class="text-xs font-bold text-slate-500 uppercase italic">Não possui registro?</p>
                    <a href="{{ route('register') }}" class="inline-block mt-2 text-sm font-black uppercase text-blue-400 hover:text-white transition-colors">Solicitar Patente →</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>