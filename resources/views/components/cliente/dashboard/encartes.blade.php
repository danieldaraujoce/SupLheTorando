@props(['encartes'])

<x-cliente.secao-carrossel titulo="🛒 Encartes de Ofertas">
    <div class="swiper encartes-slider w-full">
        <div class="swiper-wrapper">
            @forelse($encartes as $encarte)
            <div class="swiper-slide w-60"> {{-- Largura padronizada --}}
                <a href="{{ Storage::url($encarte->arquivo_url) }}" target="_blank" class="block rounded-2xl overflow-hidden shadow-md transition-transform duration-300 hover:scale-105">
                    <img src="{{ Storage::url($encarte->imagem_capa) }}" alt="{{ $encarte->titulo }}" class="w-full h-auto object-cover">
                </a>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-gray-500">Nenhum encarte disponível no momento.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-cliente.secao-carrossel>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.encartes-slider', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            grabCursor: true,
        });
    });
</script>
@endpush