<div class="my-6">
    <h3 class="text-lg text-center font-semibold text-black mb-2">Adicionar Produto ao Carrinho</h3>
    
    <div class="flex items-center gap-2 mb-4">
        <input type="text" id="barcode-input-dashboard" placeholder="Digite o código de barras"
               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
        <button type="button" onclick="adicionarAoCarrinhoPeloInput()"
                class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <a href="{{ route('cliente.scanner.index') }}"
            class="w-full px-4 py-3 bg-green-500 text-white font-semibold rounded-md flex items-center justify-center gap-2 hover:bg-green-600 transition-colors">
        <i class="fas fa-barcode"></i>
        Escanear com a Câmara
    </a>
</div>