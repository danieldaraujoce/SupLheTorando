@props(['promocoes'])

<x-cliente.secao-carrossel titulo="🎁 Promoções em Destaque">
    <div class="swiper promocoes-slider w-full">
        <div class="swiper-wrapper">
            @forelse ($promocoes as $promocao)
            <div class="swiper-slide h-auto w-80">
                <div class="bg-white rounded-2xl p-5 shadow-md flex flex-col h-full text-center">
                    <div class="flex-grow">
                        <h3 class="font-bold text-lg text-black mb-2">{{ $promocao->titulo }}</h3>
                        <p class="text-sm text-gray-600">{{ Str::limit($promocao->descricao, 50) }}</p>
                        <div class="my-3">
                            <span class="text-4xl font-extrabold text-primary">
                                {{ rtrim(rtrim(number_format($promocao->valor_desconto, 2), '0'), '.') }}% OFF
                            </span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('cliente.promocoes.show', $promocao) }}" class="w-full block rounded-lg bg-primary py-3 px-4 font-bold text-white hover:bg-primary-dark transition-all">Ver Promoção</a>
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-gray-500 p-4">Nenhuma promoção disponível no momento.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-cliente.secao-carrossel>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.promocoes-slider', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            grabCursor: true,
        });
    });
</script>
@endpush