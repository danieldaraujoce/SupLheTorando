@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Gerenciador de Quizzes</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Quizzes</li>
        </ol>
    </nav>
</div>

{{-- Card Principal --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    {{-- Cabeçalho Interno do Card --}}
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Lista de Quizzes</h4>
            <p class="text-sm text-gray-500 mt-1">Crie jogos de conhecimento para engajar seus clientes.</p>
        </div>
        <a href="{{ route('admin.quizzes.create') }}"
            class="mt-3 sm:mt-0 flex items-center justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            + Novo Quiz
        </a>
    </div>

    {{-- Tabela de Quizzes --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Quiz</th>
                    <th class="py-4 px-6 font-medium text-black">Nº de Perguntas</th>
                    <th class="py-4 px-6 font-medium text-black">Validade</th>
                    <th class="py-4 px-6 font-medium text-black">Status</th>
                    <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quizzes as $quiz)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <h5 class="font-semibold text-black">{{ $quiz->titulo }}</h5>
                        <p class="text-sm text-gray-500">{{ Str::limit($quiz->descricao, 60) }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ $quiz->perguntas_count }}</p>
                    </td>
                    <td class="py-4 px-6">
                         @if($quiz->data_inicio && $quiz->data_fim)
                            <p class="text-black">{{ $quiz->data_inicio->format('d/m/Y') }} - {{ $quiz->data_fim->format('d/m/Y') }}</p>
                        @else
                            <p class="text-gray-400">N/D</p>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        @if ($quiz->data_inicio && now()->between($quiz->data_inicio, $quiz->data_fim))
                            <p class="inline-flex rounded-full bg-green-100 py-1 px-3 text-sm font-medium text-green-600">
                                Vigente</p>
                        @elseif ($quiz->data_inicio && now()->lt($quiz->data_inicio))
                             <p class="inline-flex rounded-full bg-blue-100 py-1 px-3 text-sm font-medium text-blue-600">
                                Agendado</p>
                        @else
                            <p class="inline-flex rounded-full bg-gray-100 py-1 px-3 text-sm font-medium text-gray-500">
                                Expirado</p>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.quizzes.edit', $quiz->id) }}"
                                class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Gerenciar</a>
                            <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza?');">
                                @csrf @method('DELETE')
                                <button
                                    class="rounded-lg bg-red-600 py-2 px-4 text-sm font-medium text-white hover:bg-red-700">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 px-4 text-center text-gray-500">
                        Nenhum quiz encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($quizzes->hasPages())
    <div class="p-6 border-t border-gray-200">{{ $quizzes->links() }}</div>
    @endif
</div>
@endsection