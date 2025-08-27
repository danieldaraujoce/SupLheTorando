

<?php $__env->startSection('content'); ?>


<div class="mb-6">
    <h2 class="text-2xl font-bold text-black">Meu Carrinho</h2>
    <p class="text-gray-600">Confira os itens que você está comprando.</p>
</div>


<?php if($carrinho && $carrinho->itens->isNotEmpty()): ?>
    <div class="bg-white rounded-2xl p-6 shadow-lg" x-data="{
        carrinhoItens: <?php echo e(json_encode($carrinho->itens)); ?>,
        csrf: '<?php echo e(csrf_token()); ?>',
        total: <?php echo e($carrinho->itens->sum(fn($item) => $item->quantidade * $item->preco_unitario)); ?>,

        updateItem(itemId, change) {
            let item = this.carrinhoItens.find(i => i.id == itemId);
            if (!item) return;

            // Previne que a quantidade vá abaixo de 1 (será removido se chegar a 0)
            if (item.quantidade + change <= 0) {
                this.removeItem(itemId);
                return;
            }

            fetch(`/carrinho/item/${itemId}/update`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                body: JSON.stringify({ quantidade: change })
            })
            .then(response => {
                if (!response.ok) return response.json().then(error => { throw new Error(error.message); });
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    item.quantidade = data.quantidade;
                    this.total = data.total_carrinho;
                }
            })
            .catch(error => alert(error.message));
        },

        removeItem(itemId) {
            if (confirm('Tem certeza que deseja remover este item do carrinho?')) {
                fetch(`/carrinho/item/${itemId}/remove`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': this.csrf }
                })
                .then(response => {
                    if (!response.ok) return response.json().then(error => { throw new Error(error.message); });
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        this.carrinhoItens = this.carrinhoItens.filter(i => i.id != itemId);
                        this.total = data.total_carrinho;
                    }
                })
                .catch(error => alert(error.message));
            }
        },
        
        finalizarCompra() {
             fetch('/carrinho/finalizar', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': this.csrf, 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => {
                if (!response.ok) return response.json().then(error => { throw new Error(error.message); });
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('qr-code-image').src = data.qr_code_image;
                    document.getElementById('qrCodeModal').classList.remove('hidden');
                } else {
                    alert(data.message);
                }
            })
            .catch(error => alert(error.message));
        }
    }">
        
        <div class="space-y-4">
            <template x-for="item in carrinhoItens" :key="item.id">
                <div class.bind="item.quantidade > 0 ? 'flex items-start gap-4 border-b border-gray-200 pb-4 last:border-b-0 last:pb-0' : 'hidden'">
                    <img :src="item.produto.imagem_url ? `<?php echo e(asset('storage')); ?>/${item.produto.imagem_url.replace('public/', '')}` : 'https://via.placeholder.com/64x64'"
                         :alt="item.produto.nome" class="h-16 w-16 object-cover rounded-lg flex-shrink-0">
                    
                    <div class="flex-grow flex flex-col justify-between h-full">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-lg text-black" x-text="item.produto.nome"></h3>
                            <button type="button" class="w-8 h-8 rounded-full bg-red-100 text-red-500 hover:bg-red-200 transition-colors flex-shrink-0 flex items-center justify-center" x-on:click="removeItem(item.id)">
                                <i class="fas fa-trash-alt text-xs"></i>
                            </button>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <button type="button" class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 transition-colors" x-on:click="updateItem(item.id, -1)">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="text-lg font-bold text-primary w-8 text-center" x-text="item.quantidade"></span>
                                <button type="button" class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 transition-colors" x-on:click="updateItem(item.id, 1)">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                            <p class="text-sm font-bold text-gray-600" x-text="'R$ ' + (item.preco_unitario * item.quantidade).toFixed(2).replace('.', ',')"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <template x-if="carrinhoItens.length === 0">
            <div class="text-center text-gray-500 py-16">
                <i class="fas fa-shopping-cart fa-4x mb-3 text-gray-300"></i>
                <h3 class="text-xl font-medium">Seu carrinho está vazio.</h3>
                <p class="text-sm">Adicione produtos para continuar.</p>
            </div>
        </template>
        
        <div x-show="carrinhoItens.length > 0">
            <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between items-center">
                <h4 class="text-xl font-bold text-black">Total:</h4>
                <span class="text-3xl font-bold text-primary" x-text="'R$ ' + total.toFixed(2).replace('.', ',')"></span>
            </div>
            
            <div class="mt-8">
                

            <form action="<?php echo e(route('cliente.carrinho.finalize')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg">
                    Finalizar Compra e Gerar QR Code
                </button>
            </form>
            
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="text-center text-gray-500 py-16 bg-white rounded-2xl shadow-lg">
        <i class="fas fa-shopping-cart fa-4x mb-3 text-gray-300"></i>
        <h3 class="text-xl font-medium">Seu carrinho está vazio.</h3>
        <p class="text-sm">Adicione produtos para começar.</p>
    </div>
<?php endif; ?>


<div id="qrCodeModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm m-4 text-center relative">
        <h2 class="text-2xl font-bold text-black mb-4">Apresente no Caixa</h2>
        <p class="text-gray-600 mb-6">Escaneie este QR Code para finalizar sua compra no PDV.</p>
        <img id="qr-code-image" src="" alt="QR Code" class="mx-auto w-56 h-56 rounded-lg mb-6">
        
        
        
        
        <button type="button" id="close-modal-btn" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
            onclick="document.getElementById('qrCodeModal').classList.add('hidden'); window.location.href = '<?php echo e(route('dashboard')); ?>';">
            <i class="fas fa-times fa-lg"></i>
        </button>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Laravel\supermercado-gamifica\resources\views/cliente/carrinho/index.blade.php ENDPATH**/ ?>