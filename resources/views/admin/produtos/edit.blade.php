@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Editar Produto</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.produtos.index') }}">Produtos /</a></li>
            <li class="text-sm font-medium text-primary">Editar</li>
        </ol>
    </nav>
</div>

@if ($errors->any())
<div class="mb-4 rounded-md border border-red-400 bg-red-100 p-4 text-sm text-red-700">
    <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

{{-- Card Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.produtos.update', $produto->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h4 class="mb-6 text-lg font-semibold text-gray-800">Informações do Produto</h4>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Nome do Produto</label>
                <input type="text" name="nome" value="{{ old('nome', $produto->nome) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Código de Barras (EAN)</label>
                <input type="text" name="codigo_barras" value="{{ old('codigo_barras', $produto->codigo_barras) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Categoria</label>
                <select name="categoria_id"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                    <option value="">Selecione...</option>
                    @foreach($categorias as $categoria)
                    <option value="{{$categoria->id}}"
                        {{ old('categoria_id', $produto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                        {{$categoria->nome}}</option>
                    @endforeach
                </select>
            </div>
            
            {{-- =============================================== --}}
            {{-- =========== CAMPO DE PREÇO CORRIGIDO ============ --}}
            {{-- =============================================== --}}
            <div x-data="{ 
                value: '{{ old('preco', number_format($produto->preco, 2, ',', '.')) }}',
                formatPrice() {
                    let cleanValue = this.value.toString().replace(/\D/g, '');
                    if(cleanValue === '') { this.value = ''; return; }
                    let numberValue = parseInt(cleanValue, 10) / 100;
                    this.value = numberValue.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }" x-init="formatPrice()">
                <label class="mb-3 block text-sm font-medium text-black">Preço (R$)</label>
                <input type="text" name="preco" x-model="value" @input="formatPrice"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>

            {{-- =============================================== --}}
            {{-- ========= CAMPO DE ESTOQUE ADICIONADO =========== --}}
            {{-- =============================================== --}}
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Quantidade em Estoque</label>
                <input type="number" name="estoque" value="{{ old('estoque', $produto->estoque) }}" min="0"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>

            {{-- Campo para Recompensa em Moedas --}}
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Recompensa em Moedas</label>
                <input type="number" name="coins_recompensa" placeholder="Ex: 50"
                    value="{{ old('coins_recompensa', $produto->coins_recompensa ?? 0) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
            </div>

            <div class="md:col-span-2">
                <label class="mb-3 block text-sm font-medium text-black">Nova Imagem do Produto (Opcional)</label>
                <input type="file" name="imagem_url"
                    class="h-11 w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 shadow-theme-xs file:mr-4 file:h-full file:border-0 file:border-r file:border-solid file:border-gray-300 file:bg-gray-100 file:px-4">
            </div>
            <div class="md:col-span-2">
                <label class="mb-3 block text-sm font-medium text-black">Descrição</label>
                <textarea name="descricao" rows="4"
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">{{ old('descricao', $produto->descricao) }}</textarea>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.produtos.index') }}"
                class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                Atualizar Produto
            </button>
        </div>
    </form>
</div>
@endsection