<nav class="bg-[#020617] border-b border-white/5 sticky top-0 z-50 backdrop-blur-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">

            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group">
                        <img src="{{ asset('logo.png') }}" class="w-14 h-14 object-contain drop-shadow-[0_0_10px_rgba(59,130,246,0.3)] group-hover:scale-110 transition-transform" alt="Logo">
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="text-xs font-black uppercase italic tracking-[0.2em]">
                        {{ __('Dashboard') }}
                    </x-nav-link>


                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 relative">
                <div class="relative">
                    <button id="user-menu-button" type="button"
                            class="inline-flex items-center px-4 py-2 border-2 border-white/5 rounded-xl text-xs font-black uppercase italic tracking-widest text-slate-400 bg-slate-900/50 hover:bg-blue-600 hover:text-white hover:border-blue-500 transition-all duration-300 shadow-lg">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm">person</span>
                            {{ Auth::user()->name }}
                        </div>
                        <svg class="ms-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div id="user-menu-dropdown"
                         class="hidden absolute right-0 mt-2 w-48 bg-[#020617] border border-white/10 rounded-xl overflow-hidden shadow-2xl z-50">

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-[10px] font-black uppercase italic tracking-widest text-slate-400 hover:bg-blue-600 hover:text-white transition-colors border-b border-white/5">
                            Configurações
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-[10px] font-black uppercase italic tracking-widest text-red-500 hover:bg-red-600 hover:text-white transition-colors">
                                Sair do Jogo
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button id="mobile-menu-button" type="button" class="p-2 rounded-lg text-slate-500 hover:text-white hover:bg-white/5 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path id="mobile-icon-open" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden sm:hidden bg-slate-950 border-t border-white/5">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-xs font-black uppercase italic">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('ranking.index')" :active="request()->routeIs('ranking.index')" class="text-xs font-black uppercase italic">
                Ranking
            </x-responsive-nav-link>
        </div>
        <div class="pt-4 pb-1 border-t border-white/5">
            <div class="px-4 py-2">
                <div class="font-black italic uppercase text-sm text-blue-500">{{ Auth::user()->name }}</div>
            </div>
            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-[10px] font-black uppercase italic">Configurações</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-[10px] font-black uppercase italic text-red-500">
                        Sair
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userBtn = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-menu-dropdown');
            const mobileBtn = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            // Toggle Dropdown Usuário
            if(userBtn && userDropdown) {
                userBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });
            }

            // Toggle Menu Mobile
            if(mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Fechar ao clicar fora
            document.addEventListener('click', function() {
                if (userDropdown) userDropdown.classList.add('hidden');
                if (mobileMenu) mobileMenu.classList.add('hidden');
            });
        });
    </script>
</nav>
