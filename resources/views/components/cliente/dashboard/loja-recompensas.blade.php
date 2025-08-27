@props(['recompensas'])

<x-cliente.secao-carrossel titulo="🛍️ Loja de Recompensas">
    <div class="swiper loja-recompensas-slider w-full">
        <div class="swiper-wrapper">
            @forelse ($recompensas as $recompensa)
            <div class="swiper-slide h-auto w-48">
                <div class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col h-full group">
                    <div class="h-32 bg-gray-100 flex items-center justify-center p-4 relative">
                        @if($recompensa->imagem_url)
                            <img src="{{ Storage::url($recompensa->imagem_url) }}" alt="{{ $recompensa->nome }}" class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-110">
                        @else
                            {{-- Ícone de presente como fallback --}}
                            <i class="fas fa-gift text-6xl text-gray-300"></i>
                        @endif
                        {{-- Badge do nível, se aplicável --}}
                        @if($recompensa->nivel)
                            <span class="absolute top-2 right-2 bg-primary text-white text-xs font-bold py-1 px-2 rounded-full">{{ $recompensa->nivel->nome }}</span>
                        @endif
                    </div>
                    <div class="p-3 flex flex-col flex-grow text-center">
                        <h3 class="font-bold text-black text-sm leading-tight flex-grow mb-2">{{ $recompensa->nome }}</h3>
                        <a href="{{ route('cliente.recompensas.index') }}" class="w-full mt-auto rounded-lg bg-primary py-2 px-3 font-bold text-sm text-white hover:bg-primary-dark transition-all">
                            {{ number_format($recompensa->custo_coins, 0, ',', '.') }} 🪙
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-gray-500">Nenhuma recompensa na loja no momento.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-cliente.secao-carrossel>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.loja-recompensas-slider', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            grabCursor: true,
        });
    });
</script>
@endpush