@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Editar Categoria</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.produtos.index') }}">Produtos /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.categorias-produtos.index') }}">Categorias /</a>
            </li>
            <li class="text-sm font-medium text-primary">Editar</li>
        </ol>
    </nav>
</div>

{{-- Card Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.categorias-produtos.update', $categoria->id) }}" method="POST">
        @csrf
        @method('PUT')
        <h4 class="mb-6 text-lg font-semibold text-gray-800">Informações da Categoria</h4>

        <div class="flex flex-col gap-6">
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Nome da Categoria</label>
                <input type="text" name="nome" value="{{ old('nome', $categoria->nome) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.categorias-produtos.index') }}"
                class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                Atualizar Categoria
            </button>
        </div>
    </form>
</div>
@endsection