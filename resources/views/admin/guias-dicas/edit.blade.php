@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Editar Guia ou Dica</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.guias-dicas.index') }}">Guias e Dicas /</a></li>
            <li class="text-sm font-medium text-primary">Editar</li>
        </ol>
    </nav>
</div>

{{-- Card Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.guias-dicas.update', $guia->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h4 class="mb-6 text-lg font-semibold text-gray-800">Informações da Guia/Dica</h4>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-3 block text-sm font-medium text-black">Título</label>
                <input type="text" name="titulo" value="{{ old('titulo', $guia->titulo) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Nova Imagem de Capa (Opcional)</label>
                <input type="file" name="imagem_capa"
                    class="h-11 w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 shadow-theme-xs file:mr-4 file:h-full file:border-0 file:border-r file:border-solid file:border-gray-300 file:bg-gray-100 file:px-4">
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Novo Arquivo Principal (Opcional)</label>
                <input type="file" name="arquivo_url"
                    class="h-11 w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 shadow-theme-xs file:mr-4 file:h-full file:border-0 file:border-r file:border-solid file:border-gray-300 file:bg-gray-100 file:px-4">
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                <input type="date" name="data_inicio"
                    value="{{ old('data_inicio', $guia->data_inicio->format('Y-m-d')) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                <input type="date" name="data_fim" value="{{ old('data_fim', $guia->data_fim->format('Y-m-d')) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>
        </div>

        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.guias-dicas.index') }}"
                class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Atualizar</button>
        </div>
    </form>
</div>
@endsection