@props(['guiasDicas'])

<x-cliente.secao-carrossel titulo="💡 Guias e Dicas">
    <div class="swiper guias-dicas-slider">
        <div class="swiper-wrapper">
            @forelse($guiasDicas as $guia)
            <div class="swiper-slide">
                <a href="{{ Storage::url($guia->arquivo_url) }}" target="_blank" class="block rounded-2xl overflow-hidden shadow-md">
                    <img src="{{ Storage::url($guia->imagem_capa) }}" alt="{{ $guia->titulo }}" class="w-full h-auto object-cover">
                </a>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-gray-500">Nenhuma dica disponível no momento.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-cliente.secao-carrossel>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.guias-dicas-slider', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            grabCursor: true,
        });
    });
</script>
@endpush