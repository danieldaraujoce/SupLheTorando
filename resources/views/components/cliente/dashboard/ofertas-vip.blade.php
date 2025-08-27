@props(['ofertasVip'])

<x-cliente.secao-carrossel titulo="🛒 Ofertas VIP">
    <div class="swiper ofertas-vip-slider">
        <div class="swiper-wrapper">
            @forelse($ofertasVip as $oferta)
            <div class="swiper-slide">
                <a href="{{ Storage::url($oferta->arquivo_url) }}" target="_blank" class="block rounded-2xl overflow-hidden shadow-md">
                    <img src="{{ Storage::url($oferta->imagem_capa) }}" alt="{{ $oferta->titulo }}" class="w-full h-auto object-cover">
                </a>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-gray-500">Nenhuma oferta VIP disponível no momento.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-cliente.secao-carrossel>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.ofertas-vip-slider', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            grabCursor: true,
        });
    });
</script>
@endpush