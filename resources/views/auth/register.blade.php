<x-guest-layout>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-950 to-black"></div>
                <div class="absolute -top-28 left-1/2 h-72 w-[46rem] -translate-x-1/2 rounded-full bg-blue-500/10 blur-3xl"></div>
                <div class="absolute -bottom-36 right-[-8rem] h-80 w-[42rem] rounded-full bg-indigo-500/10 blur-3xl"></div>
            </div>

            <div class="relative mx-auto flex min-h-screen max-w-6xl items-center px-6 py-14">
                <div class="grid w-full items-center gap-10 lg:grid-cols-2">

                    <section class="text-center lg:text-left">
                        <p class="inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-1 text-xs text-slate-300 ring-1 ring-white/10">
                            <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                            Crie sua conta
                        </p>

                        <h1 class="mt-5 text-4xl font-extrabold tracking-tight text-white sm:text-5xl">
                            Junte-se ao Batalha Naval
                        </h1>

                        <p class="mt-4 max-w-xl text-base text-slate-300 sm:text-lg">
                            Crie sua conta para salvar partidas, acompanhar progresso e personalizar suas configurações.
                        </p>

                        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row lg:justify-start">
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center rounded-xl bg-white/5 px-5 py-3 text-sm font-semibold text-white ring-1 ring-white/10 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20">
                                Já tenho conta
                            </a>
                        </div>
                    </section>

                    <aside class="mx-auto w-full max-w-md">
                        <div class="rounded-2xl bg-white/5 p-6 ring-1 ring-white/10 backdrop-blur">
                            <div class="mb-6">
                                <h2 class="text-xl font-bold text-white">Registrar</h2>
                                <p class="mt-1 text-sm text-slate-300">Preencha os dados para criar sua conta.</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                                @csrf

                                <div>
                                    <x-input-label for="name" value="Nome" class="text-slate-200" />
                                    <x-text-input id="name"
                                                  class="mt-1 block w-full rounded-xl border-white/10 bg-slate-950/40 text-slate-100 focus:border-blue-400/60 focus:ring-blue-300/30"
                                                  type="text"
                                                  name="name"
                                                  :value="old('name')"
                                                  required
                                                  autofocus
                                                  autocomplete="name" />
                                </div>

                                <div>
                                    <x-input-label for="email" value="Email" class="text-slate-200" />
                                    <x-text-input id="email"
                                                  class="mt-1 block w-full rounded-xl border-white/10 bg-slate-950/40 text-slate-100 focus:border-blue-400/60 focus:ring-blue-300/30"
                                                  type="email"
                                                  name="email"
                                                  :value="old('email')"
                                                  required
                                                  autocomplete="username" />
                                </div>

                                <div>
                                    <x-input-label for="password" value="Senha" class="text-slate-200" />
                                    <x-text-input id="password"
                                                  class="mt-1 block w-full rounded-xl border-white/10 bg-slate-950/40 text-slate-100 focus:border-blue-400/60 focus:ring-blue-300/30"
                                                  type="password"
                                                  name="password"
                                                  required
                                                  autocomplete="new-password" />
                                    <p class="mt-2 text-xs text-slate-400">
                                        Use pelo menos 8 caracteres (recomendado: letras + números).
                                    </p>
                                </div>

                                <div>
                                    <x-input-label for="password_confirmation" value="Confirmar senha" class="text-slate-200" />
                                    <x-text-input id="password_confirmation"
                                                  class="mt-1 block w-full rounded-xl border-white/10 bg-slate-950/40 text-slate-100 focus:border-blue-400/60 focus:ring-blue-300/30"
                                                  type="password"
                                                  name="password_confirmation"
                                                  required
                                                  autocomplete="new-password" />
                                </div>

                                <button type="submit"
                                        class="mt-2 w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-300/60">
                                    Criar conta
                                </button>

                                <p class="pt-2 text-center text-sm text-slate-300">
                                    Já tem conta?
                                    <a class="font-semibold text-blue-300 hover:text-blue-200"
                                       href="{{ route('login') }}">
                                        Entrar
                                    </a>
                                </p>

                                <p class="text-center text-xs text-slate-500">
                                    Ao criar uma conta, você concorda com os termos do sistema.
                                </p>
                            </form>
                        </div>

                        <p class="mt-4 text-center text-xs text-slate-500">
                            © {{ date('Y') }} Batalha Naval
                        </p>
                    </aside>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
