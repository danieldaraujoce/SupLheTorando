@extends('layouts.app')

@section('content')

{{-- Título da Página --}}
<div class="mb-6 text-center">
    <h2 class="text-3xl font-bold text-gray-800">❓Central de Quizzes</h2>
    <p class="text-gray-600">Teste seus conhecimentos e ganhe moedas!</p>
</div>

{{-- Grid de Quizzes --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse ($quizzes as $quiz)
    <div class="bg-white rounded-2xl p-6 shadow-md flex flex-col">
        <div class="flex-grow">
            <h3 class="font-bold text-xl text-black">{{ $quiz->titulo }}</h3>
            <p class="text-sm text-gray-600 mt-2">{{ $quiz->descricao }}</p>
        </div>
        <div class="mt-6 flex justify-between items-center">
            <span class="text-sm text-gray-500">{{ $quiz->perguntas->count() }} perguntas</span>
            <a href="{{ route('cliente.quizzes.show', $quiz->id) }}"
                class="rounded-lg bg-primary py-2 px-5 font-medium text-white hover:bg-opacity-90">
                Começar Agora
            </a>
        </div>
    </div>
    @empty
    <div class="md:col-span-2 text-center text-gray-500 py-16">
        <i class="fas fa-question-circle fa-3x mb-3 text-gray-300"></i>
        <h3 class="text-lg font-medium">Nenhum quiz disponível no momento.</h3>
        <p class="text-sm">Volte em breve para novos desafios!</p>
    </div>
    @endforelse
</div>
@endsection