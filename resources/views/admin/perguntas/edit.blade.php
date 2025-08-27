@extends('admin.layouts.app')
@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Editar Pergunta do Quiz</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.quizzes.index') }}">Quizzes /</a></li>
            <li><a class="text-sm text-gray-500"
                    href="{{ route('admin.quizzes.edit', $pergunta->quiz_id) }}">{{ $pergunta->quiz->titulo }} /</a>
            </li>
            <li class="text-sm font-medium text-primary">Editar Pergunta</li>
        </ol>
    </nav>
</div>

{{-- Card Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.perguntas.update', $pergunta->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div x-data='{ 
                respostas: @json($pergunta->respostas->map(function($r, $i) { return ["original_id" => $r->id, "texto" => $r->texto_resposta]; })),
                correta: @json($pergunta->respostas->search(fn($r) => $r->correta))
            }'>
            <h4 class="mb-6 text-lg font-semibold text-gray-800">Detalhes da Pergunta</h4>

            <div class="mb-6">
                <label class="mb-3 block text-sm font-medium text-black">Texto da Pergunta</label>
                <textarea name="texto_pergunta" rows="3"
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>{{ old('texto_pergunta', $pergunta->texto_pergunta) }}</textarea>
            </div>
            <div class="mb-6">
                <label class="mb-3 block text-sm font-medium text-black">Recompensa em Moedas</label>
                <input type="number" name="coins_recompensa"
                    value="{{ old('coins_recompensa', $pergunta->coins_recompensa) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>

            <h4 class="mb-4 text-md font-medium text-black">Respostas</h4>
            <div class="flex flex-col gap-4">
                <template x-for="(resposta, index) in respostas" :key="index">
                    <div class="flex items-center gap-4">
                        <input type="radio" name="correta" :value="index" x-model="correta"
                            class="h-5 w-5 text-primary focus:ring-primary/50 border-gray-300">
                        <input type="hidden" :name="'respostas[' + index + '][id]'" :value="resposta.original_id">
                        <input type="text" :name="'respostas[' + index + '][texto]'" x-model="resposta.texto"
                            :placeholder="'Resposta ' + (index + 1)"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                            required>
                    </div>
                </template>
            </div>
            <button type="button" @click="respostas.push({id: null, texto: ''})"
                class="mt-4 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">+
                Adicionar Resposta</button>

            {{-- Botões de Ação --}}
            <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.quizzes.edit', $pergunta->quiz_id) }}"
                    class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit"
                    class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Atualizar
                    Pergunta</button>
            </div>
        </div>
    </form>
</div>
@endsection