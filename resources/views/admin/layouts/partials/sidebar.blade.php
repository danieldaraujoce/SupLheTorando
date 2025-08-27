<aside
    class="fixed left-0 top-0 z-40 h-screen w-64 -translate-x-full transition-transform sm:translate-x-0 bg-gray-50 border-r border-gray-200"
    :class="{ 'translate-x-0': sidebarToggle }"
    aria-label="Sidebar">

    {{-- Logo e Título --}}
    <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5 border-b border-gray-200">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
            @php $logoPath = \App\Helpers\SettingsHelper::get('logo_marca'); @endphp
            @if($logoPath)
                <img src="{{ Storage::url($logoPath) }}" alt="Logo" class="h-8">
            @else
                <i class="fas fa-rocket text-primary text-2xl"></i>
            @endif
            <span class="text-xl font-bold text-black">GAMIFICA</span>
        </a>

        {{-- Botão de fechar a sidebar no mobile --}}
        <button @click.stop="sidebarToggle = !sidebarToggle" class="block sm:hidden">
            <i class="fas fa-times text-gray-500"></i>
        </button>
    </div>

    {{-- Lista de Links da Navegação --}}
    <div class="h-full overflow-y-auto px-3 py-4">
        <ul class="space-y-2 font-medium">
            {{-- Item: Dashboard --}}
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'text-gray-900 hover:bg-gray-100' }}">
                    <i class="fas fa-tachometer-alt w-5 h-5 transition duration-75 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            {{-- Item: Clientes --}}
            <li>
                <a href="{{ route('admin.clientes.index') }}"
                    class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.clientes.*') ? 'bg-primary text-white' : 'text-gray-900 hover:bg-gray-100' }}">
                    <i class="fas fa-users w-5 h-5 transition duration-75 {{ request()->routeIs('admin.clientes.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ms-3">Clientes</span>
                </a>
            </li>

            {{-- Item: Destaques Home --}}
            <li>
                <a href="{{ route('admin.destaques-home.index') }}"
                    class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.destaques-home.*') ? 'bg-primary text-white' : 'text-gray-900 hover:bg-gray-100' }}">
                    <i class="fas fa-star w-5 h-5 transition duration-75 {{ request()->routeIs('admin.destaques-home.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ms-3">Destaques Home</span>
                </a>
            </li>
            
            {{-- Item: Configurações --}}
            <li>
                <a href="{{ route('admin.configuracoes.index') }}"
                    class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.configuracoes.*') ? 'bg-primary text-white' : 'text-gray-900 hover:bg-gray-100' }}">
                    <i class="fas fa-cog w-5 h-5 transition duration-75 {{ request()->routeIs('admin.configuracoes.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ms-3">Configurações</span>
                </a>
            </li>

            {{-- Divisor (Exemplo) --}}
            <li class="px-2 pt-4 pb-2">
                <span class="text-xs font-semibold text-gray-500 uppercase">Gerenciar Conteúdo</span>
            </li>

            {{-- Adicione aqui os outros links do seu menu, seguindo o mesmo padrão --}}
            {{-- Exemplo para Cupons: --}}
            <li>
                <a href="{{ route('admin.cupons.index') }}"
                    class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.cupons.*') ? 'bg-primary text-white' : 'text-gray-900 hover:bg-gray-100' }}">
                    <i class="fas fa-ticket-alt w-5 h-5 transition duration-75 {{ request()->routeIs('admin.cupons.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ms-3">Cupons</span>
                </a>
            </li>
             <li>
                <a href="{{ route('admin.cashbacks.index') }}"
                    class="flex items-center p-2 rounded-lg {{ request()->routeIs('admin.cashbacks.*') ? 'bg-primary text-white' : 'text-gray-900 hover:bg-gray-100' }}">
                    <i class="fas fa-coins w-5 h-5 transition duration-75 {{ request()->routeIs('admin.cashbacks.*') ? 'text-white' : 'text-gray-500' }}"></i>
                    <span class="ms-3">Cashbacks</span>
                </a>
            </li>
        </ul>
    </div>
</aside>