<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $titulo_pagina ?? 'Painel Admin' }} - Supermercado Gamifica</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ sidebarToggle: false }" class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        {{-- INÍCIO DA SIDEBAR --}}
        <aside :class="sidebarToggle ? 'translate-x-0' : '-translate-x-full'"
            class="fixed left-0 top-0 z-50 flex h-screen w-72 flex-col overflow-y-auto bg-white border-r border-gray-200 duration-300 ease-linear lg:static lg:translate-x-0"
            @click.outside="sidebarToggle = false">

            <div class="flex items-center justify-between gap-2 pt-4 px-6 py-5.5 lg:py-6.5">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-black flex items-center gap-2">
                    @php $logoPath = \App\Helpers\SettingsHelper::get('logo_marca'); @endphp
                    @if($logoPath)
                    <img src="{{ Storage::url($logoPath) }}" alt="Logo" class="h-8">
                    @else
                    <i class="fas fa-rocket text-primary"></i> GAMIFICA
                    @endif
                </a>
                <button class="block lg:hidden" @click.stop="sidebarToggle = !sidebarToggle">
                    <i class="fas fa-times text-2xl text-black"></i>
                </button>
            </div>
            
            {{-- =============================================== --}}
            {{-- =========== CORREÇÃO APLICADA AQUI ============ --}}
            {{-- =============================================== --}}
            {{-- Adicionada a classe pb-10 para garantir o espaçamento inferior --}}
            <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
                <nav class="mt-5 py-4 px-4 lg:mt-9 lg:px-6">
                    <div>
                        <h3 class="mb-4 ml-4 text-sm font-semibold text-gray-400">MENU</h3>
                        <ul class="mb-6 flex flex-col gap-1.5">
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-700 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.dashboard') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt fa-fw"></i>
                                    Dashboard</a></li>
                            <li x-data="{ open: {{ request()->routeIs('admin.clientes.*', 'admin.niveis.*') ? 'true' : 'false' }} }">
                            <a href="#" @click.prevent="open = !open"
                                class="group relative flex items-center justify-between gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.clientes.*', 'admin.niveis.*') ? 'bg-primary/10 text-primary' : '' }}">
                                <span><i class="fas fa-users fa-fw"></i> Clientes</span>
                                <i class="fas fa-chevron-down transition-transform" :class="{'rotate-180': open}"></i>
                            </a>
                            <div x-show="open" x-collapse class="mt-2 space-y-2 pl-8">
                                <a href="{{ route('admin.clientes.index') }}"
                                    class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.clientes.*') ? 'text-primary' : '' }}">
                                    Lista de Clientes
                                </a>
                                <a href="{{ route('admin.niveis.index') }}"
                                    class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.niveis.*') ? 'text-primary' : '' }}">
                                    Níveis de Fidelidade
                                </a>
                            </div>
                        </li>
                        <li>
                        <a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.calculadora.index') ? 'bg-primary/10 text-primary' : '' }}"
                        href="{{ route('admin.calculadora.index') }}">
                        <i class="fas fa-calculator fa-fw"></i> Calc. de Moedas
                        </a>
                        </li>
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.cashbacks.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.cashbacks.index') }}"><i class="fas fa-undo-alt fa-fw"></i>
                                    Cashback</a></li>
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.cupons.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.cupons.index') }}"><i class="fas fa-tags fa-fw"></i>
                                    Cupons</a></li>
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.encartes.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.encartes.index') }}"><i class="fas fa-file-invoice fa-fw"></i>
                                    Encartes</a></li>
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.guias-dicas.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.guias-dicas.index') }}"><i class="fas fa-book-open fa-fw"></i>
                                    Guias e Dicas</a></li>
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.jogos.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.jogos.index') }}"><i class="fas fa-gamepad fa-fw"></i> Jogue e
                                    Ganhe</a></li>
                            {{-- TROQUE o <li> de Missões existente por este bloco --}}
                            <li x-data="{ open: {{ request()->routeIs('admin.missoes.*') ? 'true' : 'false' }} }">
                                <a href="#" @click.prevent="open = !open"
                                    class="group relative flex items-center justify-between gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.missoes.*') ? 'bg-primary/10 text-primary' : '' }}">
                                    <span><i class="fas fa-bullseye fa-fw"></i> Missões</span>
                                    <i class="fas fa-chevron-down transition-transform" :class="{'rotate-180': open}"></i>
                                </a>
                                {{-- Sub-menu --}}
                                <div x-show="open" x-collapse class="mt-2 space-y-2 pl-8">
                                    <a href="{{ route('admin.missoes.index') }}"
                                        class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.missoes.index', 'admin.missoes.create', 'admin.missoes.edit') ? 'text-primary' : '' }}">
                                        Todas as Missões
                                    </a>
                                    <a href="{{ route('admin.missoes.pendentes') }}"
                                        class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.missoes.pendentes') ? 'text-primary' : '' }}">
                                        Pendentes de Validação
                                    </a>
                                </div>
                            </li>
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.ofertas-mes.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.ofertas-mes.index') }}"><i
                                        class="fas fa-calendar-alt fa-fw"></i> Ofertas do Mês</a></li>
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.ofertas-vip.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.ofertas-vip.index') }}"><i class="fas fa-star fa-fw"></i>
                                    Ofertas Vip</a></li>
                            <li
                                x-data="{ open: {{ request()->routeIs('admin.produtos.*', 'admin.categorias-produtos.*') ? 'true' : 'false' }} }">
                                <a href="#" @click.prevent="open = !open"
                                    class="group relative flex items-center justify-between gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.produtos.*', 'admin.categorias-produtos.*') ? 'bg-primary/10 text-primary' : '' }}">
                                    <span><i class="fas fa-box-open fa-fw"></i> Produtos</span>
                                    <i class="fas fa-chevron-down transition-transform"
                                        :class="{'rotate-180': open}"></i>
                                </a>
                                <div x-show="open" x-collapse class="mt-2 space-y-2 pl-8">
                                    <a href="{{ route('admin.produtos.index') }}"
                                        class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.produtos.*') ? 'text-primary' : '' }}">Todos
                                        os Produtos</a>
                                    <a href="{{ route('admin.categorias-produtos.index') }}"
                                        class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.categorias-produtos.*') ? 'text-primary' : '' }}">Categorias</a>
                                    <a href="{{ route('admin.produtos.cadastro-rapido') }}"
                                        class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.produtos.cadastro-rapido') ? 'text-primary' : '' }}">Cadastro
                                        Rápido</a>
                                </div>
                            </li>
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.promocoes.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.promocoes.index') }}"><i class="fas fa-bullhorn fa-fw"></i>
                                    Promoções</a></li>
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.quizzes.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.quizzes.index') }}"><i
                                        class="fas fa-question-circle fa-fw"></i> Quizzes</a></li>

                            <li x-data="{ open: {{ request()->routeIs('admin.recompensas.*') ? 'true' : 'false' }} }">
                                <a href="#" @click.prevent="open = !open" class="group relative flex items-center justify-between gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.recompensas.*') ? 'bg-primary/10 text-primary' : '' }}">
                                    <span><i class="fas fa-gift fa-fw"></i> Recompensas</span>
                                    <i class="fas fa-chevron-down transition-transform" :class="{'rotate-180': open}"></i>
                                </a>
                                <div x-show="open" x-collapse class="mt-2 space-y-2 pl-8">
                                    <a href="{{ route('admin.recompensas.index') }}" class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.recompensas.index', 'admin.recompensas.create', 'admin.recompensas.edit') ? 'text-primary' : '' }}">Catálogo</a>
                                    <a href="{{ route('admin.recompensas.pendentes') }}" class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.recompensas.pendentes') ? 'text-primary' : '' }}">Pendentes de Validação</a>
                                </div>
                            </li>

                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.roletas.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.roletas.index') }}"><i class="fas fa-dharmachakra fa-fw"></i>
                                    Roleta</a></li>
                            <li><a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.caixas-surpresa.*') ? 'bg-primary/10 text-primary' : '' }}"
                                    href="{{ route('admin.caixas-surpresa.index') }}"><i
                                        class="fas fa-box-open fa-fw"></i> Caixas Surpresa</a></li>

                            <li>
                                <a class="group relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.relatorios.*') ? 'bg-primary/10 text-primary' : '' }}" href="{{ route('admin.relatorios.index') }}">
                                    <i class="fas fa-chart-bar fa-fw"></i>
                                    Relatórios
                                </a>
                            </li>

                            
                            <li
                                x-data="{ open: {{ request()->routeIs('admin.configuracoes.*', 'admin.destaques-home.*') ? 'true' : 'false' }} }">
                                <a href="#" @click.prevent="open = !open"
                                    class="group relative flex items-center justify-between gap-2.5 rounded-md py-2 px-4 font-medium text-gray-500 duration-300 ease-in-out hover:bg-gray-100 {{ request()->routeIs('admin.configuracoes.*', 'admin.destaques-home.*') ? 'bg-primary/10 text-primary' : '' }}">
                                    <span><i class="fas fa-cog fa-fw"></i> Configurações</span>
                                    <i class="fas fa-chevron-down transition-transform"
                                        :class="{'rotate-180': open}"></i>
                                </a>
                                <div x-show="open" x-collapse class="mt-2 space-y-2 pl-8">
                                    <a href="{{ route('admin.configuracoes.index') }}"
                                        class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.configuracoes.*') ? 'text-primary' : '' }}">Gerais</a>
                                    <a href="{{ route('admin.destaques-home.index') }}"
                                        class="block rounded-md py-2 px-3 text-sm text-gray-500 hover:bg-gray-100 {{ request()->routeIs('admin.destaques-home.*') ? 'text-primary' : '' }}">Front-End
                                        (Home)</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </aside>
        {{-- FIM DA SIDEBAR --}}

        {{-- Container da Área de Conteúdo (SEU CÓDIGO ORIGINAL MANTIDO) --}}
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            {{-- INÍCIO DO HEADER (SEU CÓDIGO ORIGINAL MANTIDO) --}}
            <header class="sticky top-0 z-40 flex w-full bg-white shadow-sm">
                <div class="flex flex-grow items-center justify-between py-4 px-4 md:px-6">
                    <div class="flex items-center gap-4">
                        <button class="z-50 rounded-lg border border-gray-200 bg-white p-2 px-4 lg:hidden"
                            @click.stop="sidebarToggle = !sidebarToggle">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                       
                    </div>

                    <div class="flex items-center gap-3 sm:gap-7">
                        <ul class="flex items-center gap-2 sm:gap-4">
                            <li><button
                                    class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:text-primary hover:bg-gray-100"><i
                                        class="fas fa-moon text-xl"></i></button></li>
                            <li><button
                                    class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:text-primary hover:bg-gray-100"><i
                                        class="fas fa-bell text-xl"></i></button></li>
                        </ul>

                        <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                            <a class="flex items-center gap-4" href="#" @click.prevent="dropdownOpen = ! dropdownOpen">
                                <span class="hidden text-right lg:block"><span
                                        class="block text-sm font-medium text-black">Olá,
                                        {{ auth()->user()->nome }}</span></span>
                                <span class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center"><i
                                        class="fas fa-user"></i></span>
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <div x-show="dropdownOpen"
                                class="absolute right-0 mt-4 flex w-64 flex-col rounded-lg border border-gray-200 bg-white shadow-lg"
                                style="display: none;">
                                <div class="px-6 py-4 border-b border-gray-200">
                                    <h4 class="text-sm font-semibold text-black">{{ auth()->user()->nome }}</h4>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <ul class="flex flex-col gap-1 p-3">
                                    <li><a href="#"
                                            class="flex items-center gap-3 rounded-md py-2 px-3 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary"><i
                                                class="fas fa-user-edit w-5"></i> Editar Perfil</a></li>
                                    <li><a href="#"
                                            class="flex items-center gap-3 rounded-md py-2 px-3 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary"><i
                                                class="fas fa-cog w-5"></i> Configurações</a></li>
                                    <li><a href="#"
                                            class="flex items-center gap-3 rounded-md py-2 px-3 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary"><i
                                                class="fas fa-question-circle w-5"></i> Suporte</a></li>
                                </ul>
                                <div class="p-3 border-t border-gray-200">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center gap-3.5 rounded-md py-2 px-3 text-sm font-medium duration-300 ease-in-out text-gray-600 hover:bg-gray-100 hover:text-primary"><i
                                                class="fas fa-sign-out-alt w-5"></i> Sair</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            {{-- FIM DO HEADER --}}

            {{-- Área de Conteúdo Principal --}}
            <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
                @if (session('success'))
                <div class="mb-4 rounded-md border border-green-400 bg-green-100 p-4 text-sm text-green-700"
                    role="alert">{{ session('success') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>