@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">
        Cadastro Rápido por Leitor
    </h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.produtos.index') }}">Produtos /</a></li>
            <li class="text-sm font-medium text-primary">Cadastro Rápido</li>
        </ol>
    </nav>
</div>

{{-- Componente Alpine.js que gerencia a página --}}
<div x-data="cadastroRapido()">
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <label class="mb-3 block text-sm font-medium text-black">Escanear Código de Barras</label>
        <div class="relative">
            <input type="text" x-model="codigoBarras" @keydown.enter.prevent="buscarProduto"
                placeholder="Aguardando leitura do código de barras..."
                class="h-12 w-full rounded-lg border border-gray-300 bg-transparent px-5 text-lg font-mono text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                autofocus>

            {{-- Ícone de Loading --}}
            <div x-show="loading" class="absolute right-4 top-1/2 -translate-y-1/2">
                <i class="fas fa-spinner animate-spin text-primary"></i>
            </div>
        </div>
        <p class="mt-2 text-sm text-gray-500">Posicione o cursor no campo e utilize o leitor de código de barras USB.</p>
    </div>

    {{-- Formulário que aparece após a busca --}}
    <template x-if="produtoEncontrado">
        <form action="{{ route('admin.produtos.store') }}" method="POST" enctype="multipart/form-data" class="mt-9">
            @csrf
            <input type="hidden" name="codigo_barras" :value="codigoBarras">
            <input type="hidden" name="imagem_url" :value="produto.imagem_url">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-5 border-b border-gray-200 pb-6 mb-6">
                    <img :src="produto.imagem_url" alt="Imagem do Produto"
                        class="h-24 w-24 object-contain rounded-md border p-1 bg-white">
                    <div>
                        <h3 class="text-xl font-semibold text-black" x-text="produto.nome"></h3>
                        <p class="text-sm text-gray-500" x-text="produto.categorias"></p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Nome do Produto</label>
                        <input type="text" name="nome" :value="produto.nome"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10" required>
                    </div>
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Preço de Venda (R$)</label>
                        <input type="text" name="preco" placeholder="19.99"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10" required>
                    </div>
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Estoque Inicial</label>
                        <input type="number" name="estoque" value="0"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                    </div>
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Categoria</label>
                        <select name="categoria_id" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                            <option value="">Selecione...</option>
                            @foreach($categorias as $categoria)
                            <option value="{{$categoria->id}}">{{$categoria->nome}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.produtos.cadastro-rapido') }}" class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Limpar</a>
                <button type="submit" class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Salvar Produto</button>
            </div>
        </form>
    </template>
</div>
@endsection
@push('scripts')
<script>
function cadastroRapido() {
    return {
        codigoBarras: '',
        produto: {},
        produtoEncontrado: false,
        loading: false,
        buscarProduto() {
            if (this.codigoBarras.length < 8) {
                this.produtoEncontrado = false;
                return;
            }
            this.loading = true;
            this.produtoEncontrado = false;

            fetch(`/api/produto/buscar/${this.codigoBarras}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.produto = data.data;
                        this.produtoEncontrado = true;
                    } else {
                        alert('Produto não encontrado na base de dados externa.');
                    }
                })
                .catch(err => console.error(err))
                .finally(() => this.loading = false);
        }
    }
}
</script>
@endpush