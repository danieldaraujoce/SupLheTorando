@props(['ofertasMes'])

<x-cliente.secao-carrossel titulo="🛒 Ofertas do Mês">
    <div class="swiper ofertas-mes-slider">
        <div class="swiper-wrapper">
            @forelse($ofertasMes as $oferta)
            <div class="swiper-slide">
                <a href="{{ Storage::url($oferta->arquivo_url) }}" target="_blank" class="block rounded-2xl overflow-hidden shadow-md">
                    <img src="{{ Storage::url($oferta->imagem_capa) }}" alt="{{ $oferta->titulo }}" class="w-full h-auto object-cover">
                </a>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-gray-500">Nenhuma oferta disponível no momento.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-cliente.secao-carrossel>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.ofertas-mes-slider', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            grabCursor: true,
        });
    });
</script>
@endpush