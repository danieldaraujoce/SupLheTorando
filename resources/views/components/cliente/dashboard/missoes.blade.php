@props(['missoesAtivas'])

<x-cliente.secao-carrossel titulo="🪙 Suas Próximas Conquistas">
    <div class="swiper missoes-slider w-full">
        <div class="swiper-wrapper">
            @forelse($missoesAtivas as $missao)
            <div class="swiper-slide h-auto w-80">
                <div class="bg-white rounded-2xl p-5 shadow-md flex flex-col h-full text-center">
                    <div class="flex-grow">
                        <h3 class="font-bold text-lg text-black mb-2">{{ $missao->titulo }}</h3>
                        <p class="text-sm text-gray-600">{{ Str::limit($missao->descricao, 50) }}</p>
                        <div class="my-3">
                            <span class="text-xs text-gray-500">Recompensa</span><br>
                            <span class="text-4xl font-extrabold text-primary">{{ $missao->coins_recompensa }} 🪙</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ ($missao->meta_item_tipo == 'responder_quiz' && $missao->meta_item_id) ? route('cliente.quizzes.show', $missao->meta_item_id) : route('cliente.missoes.index') }}" 
                           class="w-full block rounded-lg bg-primary py-3 px-4 font-bold text-white hover:bg-primary-dark transition-all">
                            Ver Missão
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-gray-500 p-4">Nenhuma missão ativa no momento. Volte em breve!</p>
            </div>
            @endforelse
        </div>
    </div>
</x-cliente.secao-carrossel>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.missoes-slider', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            grabCursor: true,
        });
    });
</script>
@endpush