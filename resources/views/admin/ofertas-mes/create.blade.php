@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Nova Oferta do Mês</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.ofertas-mes.index') }}">Ofertas do Mês /</a></li>
            <li class="text-sm font-medium text-primary">Adicionar</li>
        </ol>
    </nav>
</div>

{{-- Card Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.ofertas-mes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h4 class="mb-6 text-lg font-semibold text-gray-800">Informações da Oferta</h4>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-3 block text-sm font-medium text-black">Título</label>
                <input type="text" name="titulo" placeholder="Ex: Ofertas Especiais de Aniversário"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Imagem de Capa</label>
                <input type="file" name="imagem_capa"
                    class="h-11 w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 shadow-theme-xs file:mr-4 file:h-full file:border-0 file:border-r file:border-solid file:border-gray-300 file:bg-gray-100 file:px-4"
                    required>
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Arquivo Principal (PDF/Imagem)</label>
                <input type="file" name="arquivo_url"
                    class="h-11 w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 shadow-theme-xs file:mr-4 file:h-full file:border-0 file:border-r file:border-solid file:border-gray-300 file:bg-gray-100 file:px-4"
                    required>
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                <input type="date" name="data_inicio"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                <input type="date" name="data_fim"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.ofertas-mes.index') }}"
                class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                Salvar Oferta
            </button>
        </div>
    </form>
</div>
@endsection