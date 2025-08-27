@extends('layouts.app')

@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6">
    <h1 class="text-3xl font-bold text-black">🛒 Nossos Produtos</h1>
    <p class="text-gray-600 mt-1">Veja nossa seleção completa de itens disponíveis.</p>
</div>

{{-- MODAL DE QUANTIDADE --}}
<div id="purchaseModal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl p-6 shadow-xl w-full max-w-sm mx-auto">
        <h3 class="text-xl font-bold text-black" id="modalProductName">Nome do Produto</h3>
        <p class="text-gray-600 mt-4 mb-6">Selecione a quantidade desejada:</p>
        
        <div class="flex items-center justify-center gap-4">
            <button onclick="changeQuantity(-1)" class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-200 text-lg font-bold text-gray-700 hover:bg-gray-300">-</button>
            <input type="text" id="quantityInput" readonly class="w-20 h-12 text-center text-2xl font-bold border-2 border-gray-200 rounded-lg">
            <button onclick="changeQuantity(1)" class="w-12 h-12 flex items-center justify-center rounded-full bg-gray-200 text-lg font-bold text-gray-700 hover:bg-gray-300">+</button>
        </div>
        
        <div class="mt-8">
            <button id="addToCartButton" class="w-full rounded-lg bg-primary py-3 px-5 font-bold text-white text-lg hover:bg-opacity-90">
                Adicionar ao Carrinho
            </button>
            <button onclick="closePurchaseModal()" class="mt-2 w-full text-sm text-gray-500 hover:text-black">
                Cancelar
            </button>
        </div>
    </div>
</div>

{{-- Grid de Produtos --}}
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
    @forelse ($produtos as $produto)
        <div class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col group">
            <div class="h-32 bg-gray-200 flex items-center justify-center overflow-hidden">
                @if($produto->imagem_url)
                    <img src="{{ Storage::url($produto->imagem_url) }}" alt="{{ $produto->nome }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-110">
                @else
                    <i class="fas fa-store text-4xl text-gray-400"></i>
                @endif
            </div>
            <div class="p-4 flex flex-col flex-grow">
                <h3 class="font-bold text-black text-sm leading-tight flex-grow">{{ Str::limit($produto->nome, 45) }}</h3>
                
                {{-- =============================================== --}}
                {{-- ============ CORREÇÃO APLICADA AQUI ============ --}}
                {{-- =============================================== --}}
                @if($produto->coins_recompensa > 0)
                    <div class="mt-2 flex items-center gap-1"> {{-- Adicionado flex e items-center para alinhar o texto com a badge --}}
                        <span class="text-xs text-gray-700 font-semibold">Ganhe:</span> {{-- Texto "Ganhe:" --}}
                        <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 py-1 px-3 text-sm font-bold text-yellow-700"> {{-- Classes ajustadas para tamanho e negrito --}}
                            <i class="fas fa-coins text-base"></i> {{-- Ícone maior --}}
                            +{{ $produto->coins_recompensa }}
                        </span>
                    </div>
                @endif
                
                <div class="mt-4 flex justify-between items-center">
                    <span class="font-bold text-lg text-primary">R$ {{ number_format($produto->preco, 2, ',', '.') }}</span>
                    
                    <button onclick='openPurchaseModal(@json($produto))' class="w-9 h-9 flex items-center justify-center rounded-full bg-primary/10 text-primary hover:bg-primary hover:text-white transition-colors">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <p class="col-span-full text-center text-gray-500 py-10">Nenhum produto encontrado no momento.</p>
    @endforelse
</div>

{{-- Paginação --}}
<div class="mt-8">
    {{ $produtos->links() }}
</div>

@endsection

@push('scripts')
<script>
    // Colocamos o script dentro de um listener para garantir que o HTML esteja pronto
    document.addEventListener('DOMContentLoaded', function () {
        let currentProduct = null;
        let quantity = 1;
        
        const modal = document.getElementById('purchaseModal');
        const modalProductName = document.getElementById('modalProductName');
        const quantityInput = document.getElementById('quantityInput');
        const addToCartButton = document.getElementById('addToCartButton');

        // Tornamos as funções globais para serem acessíveis pelo onclick
        window.openPurchaseModal = function(product) {
            currentProduct = product;
            quantity = 1;
            
            modalProductName.textContent = product.nome;
            updateModal();
            modal.classList.remove('hidden');
        }

        window.closePurchaseModal = function() {
            modal.classList.add('hidden');
            currentProduct = null;
        }

        window.changeQuantity = function(amount) {
            if (!currentProduct) return;
            
            const newQuantity = quantity + amount;
            const maxQuantity = currentProduct.estoque > 0 ? currentProduct.estoque : 1;
            const minQuantity = 1;

            if (newQuantity >= minQuantity && newQuantity <= maxQuantity) {
                quantity = newQuantity;
                updateModal();
            }
        }

        function updateModal() {
            quantityInput.value = quantity;
        }

        addToCartButton.addEventListener('click', function() {
            if (!currentProduct) return;
            
            fetch("{{ route('cliente.carrinho.adicionar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    produto_id: currentProduct.id,
                    quantidade: quantity
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Produto adicionado ao carrinho!'); 
                } else {
                    alert('Erro: ' + (data.message || 'Não foi possível adicionar o produto.'));
                }
                closePurchaseModal();
            })
            .catch(() => alert('Ocorreu um erro de comunicação.'));
        });
    });
</script>
@endpush