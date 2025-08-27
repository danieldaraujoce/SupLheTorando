<header class="sticky top-0 z-40 flex w-full bg-white shadow-sm">
    <div class="flex flex-grow items-center justify-between py-4 px-4 md:px-6">
        <div class="flex items-center gap-4">
            {{-- Botão para abrir a sidebar no mobile --}}
            <button class="z-50 rounded-lg border border-gray-200 bg-white p-2 px-4 lg:hidden"
                @click.stop="sidebarToggle = !sidebarToggle">
                <i class="fas fa-bars text-xl"></i>
            </button>
            {{-- Campo de busca --}}
            <div class="hidden sm:block">
                <div class="relative">
                    <span class="absolute top-1/2 left-4 -translate-y-1/2"><i
                            class="fas fa-search text-gray-400"></i></span>
                    <input type="text" placeholder="Search..."
                        class="w-full bg-gray-100 rounded-lg pl-10 pr-4 py-2 focus:outline-none">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 sm:gap-7">
            {{-- Ícones de Ação --}}
            <ul class="flex items-center gap-2 sm:gap-4">
                <li><button
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:text-primary hover:bg-gray-100"><i
                            class="fas fa-moon text-xl"></i></button></li>
                <li><button
                        class="flex h-10 w-10 items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:text-primary hover:bg-gray-100"><i
                            class="fas fa-bell text-xl"></i></button></li>
            </ul>

            {{-- Dropdown do Perfil --}}
            <div class="relative" x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false">
                <a class="flex items-center gap-4" href="#" @click.prevent="dropdownOpen = ! dropdownOpen">
                    <span class="hidden text-right lg:block"><span
                            class="block text-sm font-medium text-black">Olá,
                            {{ auth()->user()->nome }}</span></span>
                    
                    {{-- Avatar do Admin --}}
                    <span class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                         @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </span>
                    <i class="fas fa-chevron-down text-sm"></i>
                </a>
                
                {{-- Conteúdo do Dropdown --}}
                <div x-show="dropdownOpen"
                    class="absolute right-0 mt-4 flex w-64 flex-col rounded-lg border border-gray-200 bg-white shadow-lg"
                    style="display: none;">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h4 class="text-sm font-semibold text-black">{{ auth()->user()->nome }}</h4>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <ul class="flex flex-col gap-1 p-3">
                        {{-- =============================================== --}}
                        {{-- =========== CORREÇÕES APLICADAS AQUI ============ --}}
                        {{-- =============================================== --}}
                        <li><a href="{{ route('admin.profile.edit') }}"
                                class="flex items-center gap-3 rounded-md py-2 px-3 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary"><i
                                    class="fas fa-user-edit w-5"></i> Editar Perfil</a></li>
                        <li><a href="{{ route('admin.configuracoes.index') }}"
                                class="flex items-center gap-3 rounded-md py-2 px-3 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary"><i
                                    class="fas fa-cog w-5"></i> Configurações</a></li>
                        {{-- Link de Suporte mantido sem rota, conforme pedido --}}
                        <li><a href="https://wa.me/5588997215517"
                                class="flex items-center gap-3 rounded-md py-2 px-3 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary"><i
                                    class="fas fa-question-circle w-5" target="_black"></i> Suporte</a></li>
                    </ul>
                    <div class="p-3 border-t border-gray-200">
                        {{-- Formulário de Logout corrigido --}}
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