@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Criar Novo Quiz</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.quizzes.index') }}">Quizzes /</a></li>
            <li class="text-sm font-medium text-primary">Adicionar</li>
        </ol>
    </nav>
</div>

{{-- Card Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.quizzes.store') }}" method="POST">
        @csrf
        <h4 class="mb-6 text-lg font-semibold text-gray-800">Informações do Quiz</h4>

        <div class="flex flex-col gap-6">
            <div class="mb-6">
                <label class="mb-3 block text-sm font-medium text-black">Título do Quiz</label>
                <input type="text" name="titulo" placeholder="Ex: Quiz do Mestre Churrasqueiro"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800"
                    required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                    <input type="date" name="data_inicio" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4" required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                    <input type="date" name="data_fim" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4" required>
                </div>
            </div>

            <div>
                <label class="mb-3 block text-sm font-medium text-black">Descrição (Opcional)</label>
                <textarea name="descricao" rows="4" placeholder="Uma breve explicação sobre o tema do quiz"
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800"></textarea>
            </div>
        </div>

        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.quizzes.index') }}"
                class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                Salvar e Adicionar Perguntas
            </button>
        </div>
    </form>
</div>
@endsection