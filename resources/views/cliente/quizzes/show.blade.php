@extends('layouts.app')

@section('content')
{{-- Título do Quiz --}}
<div class="mb-6 text-center">
    <h1 class="text-3xl font-bold text-black">{{ $quiz->titulo }}</h1>
    <p class="text-gray-600 mt-1">Responda para ganhar moedas!</p>
</div>

{{-- Card da Pergunta --}}
<div class="bg-white rounded-2xl p-6 shadow-lg max-w-2xl mx-auto" x-data="quizFlow()">

    {{-- Exibe mensagens de erro do Laravel, se houver --}}
    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    @if ($pergunta_atual)
    <form action="{{ route('cliente.quizzes.responder', $pergunta_atual->id) }}" method="POST">
        @csrf
        {{-- Texto da Pergunta --}}
        <div class="text-center">
            <p class="text-sm text-primary mb-2">Recompensa: {{ $pergunta_atual->coins_recompensa }} 🪙</p>
            <h2 class="text-xl font-semibold text-black">{{ $pergunta_atual->texto_pergunta }}</h2>
        </div>

        {{-- Lista de Respostas --}}
        <div class="mt-8 space-y-4">
            @foreach ($pergunta_atual->respostas as $resposta)
            <label
                class="flex items-center p-4 rounded-lg border border-gray-200 cursor-pointer has-[:checked]:bg-primary/10 has-[:checked]:border-primary transition-colors">
                <input type="radio" name="resposta_id" value="{{ $resposta->id }}" required
                    class="h-5 w-5 text-primary focus:ring-primary/50 border-gray-300">
                <span class="ml-4 text-black font-medium">{{ $resposta->texto_resposta }}</span>
            </label>
            @endforeach
        </div>

        {{-- Botão de Envio --}}
        <div class="mt-8">
            <button type="submit"
                class="w-full text-center rounded-lg bg-primary py-3 px-6 font-bold text-white hover:bg-opacity-90 transition-transform hover:scale-105">
                Responder
            </button>
        </div>
    </form>
    @else
    {{-- Mensagem de Conclusão --}}
    <div class="text-center text-gray-600 py-10">
        <i class="fas fa-trophy fa-4x text-yellow-400 mb-4"></i>
        <h2 class="text-2xl font-bold text-black">Parabéns!</h2>
        <p class="mt-2">Você concluiu este quiz com sucesso.</p>
        <a href="{{ route('cliente.quizzes.index') }}"
            class="mt-6 inline-block rounded-lg bg-gray-200 py-2 px-5 font-medium text-black hover:bg-gray-300">
            Ver outros Quizzes
        </a>
    </div>
    @endif
</div>


{{-- ========================================================== --}}
{{-- ================ SPLASH SCREEN DE RECOMPENSA ================ --}}
{{-- ========================================================== --}}
<div x-show="splash.visible" x-transition.opacity.duration.300ms
    class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center" style="display: none;">

    <div class="text-center text-white animate-pulse">
        <i class="fas fa-check-circle fa-5x text-green-400"></i>
        <h2 class="text-3xl font-bold mt-4">Resposta Correta!</h2>
        <p class="text-xl mt-2">Você ganhou</p>
        <p class="text-5xl font-bold my-2">
            <span x-text="splash.coins"></span> 🪙
        </p>
    </div>
</div>


<script>
function quizFlow() {
    // Pega os dados de feedback que o Controller enviou para a sessão
    const feedback = @json(session('quiz_feedback'));

    return {
        splash: {
            visible: false,
            coins: 0
        },
        init() {
            // Verifica se existe um feedback de resposta correta ao iniciar o componente
            if (feedback && feedback.correta) {
                this.splash.coins = feedback.coins;
                this.splash.visible = true; // Ativa o splash screen

                // Esconde o splash screen após 2.5 segundos
                setTimeout(() => {
                    this.splash.visible = false;
                }, 2500);
            }
        }
    }
}
</script>

@endsection