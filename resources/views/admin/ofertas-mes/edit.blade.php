@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Editar Oferta do Mês</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.ofertas-mes.index') }}">Ofertas do Mês /</a></li>
            <li class="text-sm font-medium text-primary">Editar</li>
        </ol>
    </nav>
</div>

{{-- Card Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">

    {{-- LINHA CORRIGIDA PARA O MÉTODO MAIS ROBUSTO --}}
    <form action="{{ route('admin.ofertas-mes.update', $oferta) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            {{-- Título --}}
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Título</label>
                <input type="text" name="titulo" value="{{ old('titulo', $oferta->titulo) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4" required>
            </div>

            {{-- Datas --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                    <input type="date" name="data_inicio" value="{{ old('data_inicio', $oferta->data_inicio->format('Y-m-d')) }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4" required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                    <input type="date" name="data_fim" value="{{ old('data_fim', $oferta->data_fim->format('Y-m-d')) }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4" required>
                </div>
            </div>

            {{-- Uploads de Arquivo --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Imagem da Capa (Opcional)</label>
                    <input type="file" name="imagem_capa" class="w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm file:mr-4 file:py-2.5 file:px-4 file:border-0 file:bg-gray-100 file:font-medium">
                    <p class="text-xs text-gray-500 mt-2">Atual: <a href="{{ Storage::url($oferta->imagem_capa) }}" target="_blank" class="text-primary hover:underline">Ver Imagem</a></p>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Arquivo PDF/Imagem (Opcional)</label>
                    <input type="file" name="arquivo_url" class="w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm file:mr-4 file:py-2.5 file:px-4 file:border-0 file:bg-gray-100 file:font-medium">
                    <p class="text-xs text-gray-500 mt-2">Atual: <a href="{{ Storage::url($oferta->arquivo_url) }}" target="_blank" class="text-primary hover:underline">Ver Arquivo</a></p>
                </div>
            </div>

            {{-- Botões de Ação --}}
            <div class="mt-6 flex justify-end gap-4 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.ofertas-mes.index') }}" class="flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="flex items-center justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Salvar Alterações</button>
            </div>
        </div>
    </form>
</div>
@endsection