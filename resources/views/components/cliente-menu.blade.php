{{-- MENU DE NAVEGAÇÃO FIXO NA BASE --}}
<div class="fixed bottom-0 left-0 z-40 w-full h-16 bg-white border-t border-gray-200">
    <div class="grid h-full max-w-lg grid-cols-5 mx-auto font-medium">
        {{-- Item 1: Início --}}
        <a href="{{ route('dashboard') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-gray-500' }}">
            <i class="fas fa-home w-6 h-6 mb-1 text-xl"></i>
            <span class="text-xs">Início</span>
        </a>
        
        {{-- Item 2: Missões --}}
        <a href="{{ route('cliente.missoes.index') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 {{ request()->routeIs('cliente.missoes.index') ? 'text-primary' : 'text-gray-500' }}">
            <i class="fas fa-rocket w-6 h-6 mb-1 text-xl"></i>
            <span class="text-xs">Missões</span>
        </a>
        
        {{-- Item 3: Escanear (Botão Central Destacado) --}}
        <div class="flex items-center justify-center">
            <a href="{{ route('cliente.scanner.index') }}" class="inline-flex items-center justify-center w-14 h-14 font-medium bg-primary text-white rounded-full shadow-lg transform -translate-y-4 hover:bg-primary-dark transition-transform hover:scale-110">
                <i class="fas fa-barcode text-2xl"></i>
            </a>
        </div>
        
        {{-- Item 4: Carrinho (com Indicador) --}}
        <a href="{{ route('cliente.carrinho.index') }}" class="relative inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 {{ request()->routeIs('cliente.carrinho.index') ? 'text-primary' : 'text-gray-500' }}">
            {{-- Lógica para buscar o total de itens no carrinho --}}
            @php
                $totalItensCarrinho = auth()->user()->carrinho ? auth()->user()->carrinho->itens->sum('quantidade') : 0;
            @endphp
            @if($totalItensCarrinho > 0)
                <div class="absolute top-1 right-3.5 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full">
                    {{ $totalItensCarrinho }}
                </div>
            @endif
            <i class="fas fa-shopping-cart w-6 h-6 mb-1 text-xl"></i>
            <span class="text-xs">Carrinho</span>
        </a>
        
        {{-- Item 5: Recompensas --}}
        <a href="{{ route('cliente.recompensas.index') }}" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 {{ request()->routeIs('cliente.recompensas.index') ? 'text-primary' : 'text-gray-500' }}">
            <i class="fas fa-gift w-6 h-6 mb-1 text-xl"></i>
            <span class="text-xs">Brindes</span>
        </a>
    </div>
</div>