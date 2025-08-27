@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Categorias de Produtos</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.produtos.index') }}">Produtos /</a></li>
            <li class="text-sm font-medium text-primary">Categorias</li>
        </ol>
    </nav>
</div>

{{-- Card Principal --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    {{-- Cabeçalho Interno do Card --}}
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Lista de Categorias</h4>
            <p class="text-sm text-gray-500 mt-1">Organize os produtos da sua loja.</p>
        </div>
        <a href="{{ route('admin.categorias-produtos.create') }}"
            class="mt-3 sm:mt-0 flex items-center justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            + Nova Categoria
        </a>
    </div>

    {{-- Tabela de Categorias --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Nome da Categoria</th>
                    <th class="py-4 px-6 font-medium text-black">Slug (URL)</th>
                    <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categorias as $categoria)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <h5 class="font-semibold text-black">{{ $categoria->nome }}</h5>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-mono text-sm text-gray-500">{{ $categoria->slug }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.categorias-produtos.edit', $categoria->id) }}"
                                class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Editar</a>
                            <form action="{{ route('admin.categorias-produtos.destroy', $categoria->id) }}"
                                method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                                @csrf @method('DELETE')
                                <button
                                    class="rounded-lg bg-red-600 py-2 px-4 text-sm font-medium text-white hover:bg-red-700">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-10 px-4 text-center text-gray-500">Nenhuma categoria encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($categorias->hasPages())
    <div class="p-6 border-t border-gray-200">{{ $categorias->links() }}</div>
    @endif
</div>
@endsection