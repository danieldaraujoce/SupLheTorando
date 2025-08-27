
<?php $__env->startSection('content'); ?>


<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Produtos</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="<?php echo e(route('admin.dashboard')); ?>">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Produtos</li>
        </ol>
    </nav>
</div>


<div class="rounded-2xl border border-gray-200 bg-white p-6">
    
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Catálogo de Produtos</h4>
            <p class="text-sm text-gray-500 mt-1">Gerencie todos os produtos da sua loja.</p>
        </div>
        <div class="flex items-center gap-4 mt-3 sm:mt-0">
            <a href="<?php echo e(route('admin.produtos.cadastro-rapido')); ?>"
                class="flex items-center justify-center whitespace-nowrap rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                Cadastro Rápido
            </a>
            <a href="<?php echo e(route('admin.produtos.create')); ?>"
                class="flex items-center justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                + Novo Produto
            </a>
        </div>
    </div>

    
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
        <?php $__empty_1 = true; $__currentLoopData = $produtos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm flex flex-col">
            <img src="<?php echo e($produto->imagem_url ? Storage::url($produto->imagem_url) : 'https://via.placeholder.com/300x200'); ?>"
                alt="<?php echo e($produto->nome); ?>" class="w-full h-40 object-cover rounded-t-2xl">
            <div class="p-4 flex flex-col flex-grow">
                <span class="text-xs text-primary font-medium"><?php echo e($produto->categoria->nome ?? 'Sem Categoria'); ?></span>
                <h3 class="font-bold text-lg text-black mt-1"><?php echo e($produto->nome); ?></h3>
                <p class="text-sm text-gray-500 mt-auto pt-2"><?php echo e($produto->codigo_barras ?? 'Sem código'); ?></p>
            </div>
            <div class="border-t border-gray-200 p-4 flex justify-between items-center">
                <p class="text-xl font-bold text-black">R$ <?php echo e(number_format($produto->preco, 2, ',', '.')); ?></p>
                <div class="flex items-center space-x-3">
                    <a href="<?php echo e(route('admin.produtos.edit', $produto->id)); ?>" class="text-gray-500 hover:text-primary"
                        title="Editar"><i class="fas fa-pencil-alt"></i></a>
                    <form action="<?php echo e(route('admin.produtos.destroy', $produto->id)); ?>" method="POST"
                        onsubmit="return confirm('Tem certeza?');">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="text-gray-500 hover:text-red-500" title="Excluir">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="col-span-full text-center text-gray-500 py-10">Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>
    <div class="p-6 border-t border-gray-200"><?php echo e($produtos->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Laravel\supermercado-gamifica\resources\views/admin/produtos/index.blade.php ENDPATH**/ ?>