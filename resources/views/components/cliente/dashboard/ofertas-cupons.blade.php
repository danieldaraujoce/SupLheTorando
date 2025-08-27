@props(['cupons', 'titulo' => '🛒 Ofertas e Cupons'])

<section class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-black">{{ $titulo }}</h2>
    </div>

    <div class="swiper ofertas-cupons-slider w-full">
        <div class="swiper-wrapper">
            @forelse($cupons as $cupom)
            <div class="swiper-slide h-auto w-64">
                @php
                    $jaResgatou = auth()->user()->cuponsResgatados->contains($cupom->id);
                    $classesBotao = $jaResgatou ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-primary hover:bg-primary-dark';
                    $dataFinal = $cupom->data_validade->endOfDay()->toIso8601String();
                @endphp
                <div x-data="countdown('{{ $dataFinal }}')" class="bg-white rounded-2xl p-5 shadow-md text-center flex flex-col justify-between h-full">
                    <div>
                        <p class="font-bold text-primary">{{ $cupom->codigo }}</p>
                        <h3 class="text-3xl font-extrabold text-black my-2">{{ $cupom->tipo_desconto == 'porcentagem' ? rtrim(rtrim(number_format($cupom->valor, 2), '0'), '.') . '%' : 'R$ ' . number_format($cupom->valor, 2, ',', '.') }} OFF</h3>
                        <p class="text-xs text-gray-500">{{ $cupom->descricao }}</p>
                    </div>
                    <div class="w-full mt-4">
                        <div class="text-xs text-red-500 font-semibold my-2 h-4" x-show="!expirado && tempoRestante"><i class="fas fa-clock"></i> <span x-text="`Expira em: ${tempoRestante}`"></span></div>
                        <div class="text-xs text-gray-500 font-semibold my-2 h-4" x-show="expirado">Expirado</div>
                        <form action="{{ route('cliente.cupons.resgatar', $cupom->id) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="w-full rounded-lg py-3 px-5 font-bold text-white transition-all {{ $classesBotao }}" :disabled="expirado || {{ $jaResgatou ? 'true' : 'false' }}">
                                {{ $jaResgatou ? 'Resgatado' : 'Pegar Cupom' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <p class="text-gray-500 p-4">Nenhum cupom disponível no momento.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('.ofertas-cupons-slider', {
            slidesPerView: 'auto',
            spaceBetween: 16,
            grabCursor: true,
        });
    });
</script>
@endpush