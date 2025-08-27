@props(['cashbacksAtivos', 'cashbacksResgatadosIds'])

<x-cliente.secao-carrossel titulo="💰 Cashback Disponível">
    <div class="swiper cashback-slider w-full">
        <div class="swiper-wrapper">

            @forelse ($cashbacksAtivos ?? [] as $cashback)
            <div class="swiper-slide h-auto">
                {{-- CARD COM O NOVO VISUAL APLICADO --}}
                <div class="bg-white rounded-2xl p-5 shadow-md h-full flex flex-col text-center w-80">
                    
                    {{-- Parte superior com as informações --}}
                    <div class="flex-grow">
                        <h3 class="font-bold text-lg text-black mb-2">{{ $cashback->titulo }}</h3>
                        
                        {{-- Valor do Cashback em destaque --}}
                        <div class="my-3">
                            <span class="text-4xl font-extrabold text-primary">
                                {{ $cashback->tipo == 'porcentagem' ? rtrim(rtrim(number_format($cashback->valor, 2), '0'), '.') . '%' : 'R$ ' . number_format($cashback->valor, 2, ',', '.') }}
                            </span>
                            <span class="text-base text-gray-600">de volta</span>
                        </div>

                        @if($cashback->valor_minimo_compra)
                            <p class="text-sm text-gray-500">Em compras a partir de R$ {{ number_format($cashback->valor_minimo_compra, 2, ',', '.') }}</p>
                        @endif
                    </div>
                    
                    {{-- Parte inferior com a data e o botão --}}
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 mb-4">Válido até: {{ \Carbon\Carbon::parse($cashback->data_fim)->format('d/m/Y') }}</p>

                        @if(in_array($cashback->id, $cashbacksResgatadosIds ?? []))
                            <span class="w-full inline-block rounded-lg bg-green-100 py-3 px-4 font-bold text-green-700">
                                <i class="fas fa-check"></i> Ativado
                            </span>
                        @else
                            <form action="{{ route('cliente.cashback.resgatar', $cashback->id) }}" method="POST" onsubmit="event.preventDefault(); ativarCashback(this);">
                                @csrf
                                <button type="submit" class="w-full rounded-lg bg-primary py-3 px-4 font-bold text-white hover:bg-primary-dark transition-all">
                                    Ativar Cashback
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-gray-500 p-4">Nenhuma campanha de cashback disponível no momento.</p>
            </div>
            @endforelse

        </div>
    </div>
</x-cliente.secao-carrossel>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.cashback-slider', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            grabCursor: true,
        });
    });
</script>
@endpush