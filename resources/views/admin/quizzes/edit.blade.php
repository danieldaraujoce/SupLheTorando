@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Gerenciar Quiz</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.quizzes.index') }}">Quizzes /</a></li>
            <li class="text-sm font-medium text-primary">Gerenciar</li>
        </ol>
    </nav>
</div>

{{-- Card para Editar os Dados do Quiz --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6 mb-6">
    <h4 class="text-xl font-semibold text-black border-b border-gray-200 pb-4 mb-6">Editar Detalhes do Quiz</h4>
    <form action="{{ route('admin.quizzes.update', $quiz->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="titulo" class="mb-3 block text-sm font-medium text-black">Título do Quiz</label>
                <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $quiz->titulo) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800" required>
            </div>

            <div>
                <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                <input type="date" name="data_inicio" value="{{ old('data_inicio', optional($quiz->data_inicio)->format('Y-m-d')) }}" 
                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4" required>
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                <input type="date" name="data_fim" value="{{ old('data_fim', optional($quiz->data_fim)->format('Y-m-d')) }}" 
                       class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4" required>
            </div>

            <div class="md:col-span-2">
                <label for="descricao" class="mb-3 block text-sm font-medium text-black">Descrição</label>
                <textarea name="descricao" id="descricao" rows="3"
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800">{{ old('descricao', $quiz->descricao) }}</textarea>
            </div>
        </div>
        <div class="flex justify-end mt-6">
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

{{-- Card para Gerenciar as Perguntas do Quiz --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <div class="flex items-center justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Gerenciador de Perguntas</h4>
            <p class="text-sm text-gray-500 mt-1">Adicione ou remova perguntas deste quiz.</p>
        </div>
        <a href="{{ route('admin.perguntas.create', $quiz->id) }}"
            class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            + Adicionar Pergunta
        </a>
    </div>

    @if($quiz->perguntas->isEmpty())
    <p class="text-center text-gray-500 py-6">Nenhuma pergunta adicionada a este quiz ainda.</p>
    @else
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Pergunta</th>
                    <th class="py-4 px-6 font-medium text-black text-center">Respostas</th>
                    <th class="py-4 px-6 font-medium text-black text-center">Recompensa</th>
                    <th class="py-4 px-6 font-medium text-black text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quiz->perguntas as $pergunta)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <p class="font-semibold text-black">{{ Str::limit($pergunta->texto_pergunta, 80) }}</p>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <p class="text-black">{{ $pergunta->respostas->count() }}</p>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <p class="text-black">{{ $pergunta->coins_recompensa }} 🪙</p>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.perguntas.edit', $pergunta->id) }}"
                                class="rounded-lg bg-blue-600 py-2 px-4 text-sm font-medium text-white hover:bg-blue-700">Editar</a>
                            <form action="{{ route('admin.perguntas.destroy', $pergunta->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta pergunta?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="rounded-lg bg-red-600 py-2 px-4 text-sm font-medium text-white hover:bg-red-700">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection