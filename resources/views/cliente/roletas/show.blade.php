@extends('layouts.app')

@section('content')
<div class="bg-white rounded-2xl p-6 shadow-lg max-w-3xl mx-auto"
    x-data="roleta({{ $roleta->itens->toJson() }}, {{ auth()->user()->giros_roleta }})">


    {{-- ====================================================================== --}}
    {{-- ================ CÓDIGO COMPLETO DO MODAL DE RESULTADO ================ --}}
    {{-- ====================================================================== --}}

    {{-- O modal fica "ouvindo" a variável 'modalAberto' do Alpine.js para aparecer/sumir --}}
    <div x-show="modalAberto" @click.away="fecharModalERecarregar()"
        class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" style="display: none;">

        <div class="bg-white rounded-2xl p-8 text-center w-full max-w-sm mx-4 relative overflow-hidden">
            {{-- FUNDO ANIMADO ADICIONADO --}}
            <div class="absolute -inset-4 bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 opacity-20 animate-pulse-slow z-0"></div>
            
            <div class="relative z-10">
                {{-- ÍCONE DINÂMICO ADICIONADO --}}
                <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center text-3xl mb-4" 
                        :class="{ 'bg-green-100 text-green-600': tipoPremio !== 'nada', 'bg-blue-100 text-blue-600': tipoPremio === 'nada' }">
                    <i class="fas" :class="{ 'fa-trophy': tipoPremio !== 'nada', 'fa-info-circle': tipoPremio === 'nada' }"></i>
                </div>
                
                {{-- TÍTULO AGORA É DINÂMICO --}}
                <h2 class="text-2xl font-bold text-black" x-text="modalTitulo"></h2>
                
                {{-- SUBTÍTULO AGORA É DINÂMICO --}}
                <p class="text-gray-600 mt-2" x-text="modalSubtitulo"></p>
                
                {{-- O prêmio continua dinâmico como no seu código --}}
                <p class="text-3xl font-bold text-primary my-2" x-text="premioGanho"></p>

                <button @click="fecharModalERecarregar()"
                    class="rounded-lg bg-gray-200 py-2 px-5 font-medium text-black hover:bg-gray-300 transition-colors mt-6">
                    Fechar
                </button>
            </div>
        </div>
    </div>


    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-black">{{ $roleta->titulo }}</h1>
        <p class="text-gray-600 mt-1">{{ $roleta->descricao }}</p>
    </div>

    {{-- A Roleta e Botão --}}
    <div class="relative flex flex-col items-center justify-center">
        {{-- Seta Indicadora (ponteiro da roleta) --}}
        <img src="{{ asset('img/roleta-indicador.png') }}" class="absolute -top-2 z-20 w-12 drop-shadow-md"
            style="margin-left: -2px;">

        {{-- O ELEMENTO CANVAS ONDE A ROLETA SERÁ DESENHADA --}}
        <canvas id="canvas" width="320" height="320" class="cursor-pointer"></canvas>

        {{-- Botão de Girar --}}
        <button @click="girar('{{ route('cliente.roletas.girar', $roleta->id) }}')"
            :disabled="girando || girosDisponiveis <= 0"
            class="mt-8 rounded-full bg-primary px-10 py-4 font-bold text-white text-xl uppercase tracking-widest
                   hover:bg-opacity-90 transition-transform hover:scale-105 disabled:bg-gray-400 disabled:cursor-not-allowed">
            Girar
        </button>
        <p class="mt-4 text-gray-600">Você tem <span x-text="girosDisponiveis" class="font-bold"></span> giros.</p>
    </div>



    {{-- Formulário de compra (continua igual) --}}
    <div class="bg-white rounded-2xl p-6 shadow-lg max-w-3xl mx-auto mt-6">
        {{-- O h3 agora serve como o único título da seção. --}}
        <h3 class="font-bold text-center text-lg text-black mb-4">Comprar Giros (100 🪙)</h3>
        <form action="{{ route('cliente.roletas.comprar-giro') }}" method="POST">
            @csrf
            <div class="flex items-end gap-4">
                <div class="flex-grow">
                    {{-- A tag <label> foi removida, conforme solicitado. --}}
                    <input type="number" name="quantidade" value="1" min="1"
                        placeholder="Quantidade"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                </div>
                <button type="submit"
                    class="rounded-lg bg-green-500 py-2 px-5 font-medium text-white hover:bg-green-600 h-11">
                    Comprar
                </button>
            </div>
        </form>
    </div>

</div>

{{-- SCRIPT TOTALMENTE NOVO E MAIS SIMPLES --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('roleta', (itens, girosIniciais) => ({
        // Suas propriedades originais
        itens: itens,
        girosDisponiveis: girosIniciais,
        girando: false,
        modalAberto: false,
        premioGanho: '',
        theWheel: null,

        // PROPRIEDADES ADICIONADAS PARA O MODAL
        tipoPremio: '',
        modalTitulo: '',
        modalSubtitulo: '',

        init() {
            // Sua função init() original, sem alterações
            const segments = this.itens.map(item => ({
                'fillStyle': item.cor_slice,
                'text': item.descricao,
                'id': item.id
            }));

            this.theWheel = new Winwheel({
                'numSegments': segments.length,
                'outerRadius': 155,
                'textFontSize': 14,
                'textFontFamily': 'sans-serif',
                'segments': segments,
                'animation': { 'type': 'spinToStop', 'duration': 6, 'spins': 8 }
            });
        },

        girar(url) {
            // Sua função girar() original, com a chamada para a nova função de resultado
            if (this.girando || this.girosDisponiveis <= 0) return;
            this.girando = true;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.girosDisponiveis = data.giros_restantes;
                        const premioSorteado = data.premio;
                        const indiceVencedor = this.itens.findIndex(item => item.id === premioSorteado.id);
                        
                        if (indiceVencedor !== -1) {
                            const segmentoParaParar = indiceVencedor + 1;
                            this.theWheel.animation.stopAngle = this.theWheel.getRandomForSegment(segmentoParaParar);
                            this.theWheel.startAnimation();

                            setTimeout(() => {
                                this.mostrarResultado(premioSorteado);
                            }, 6200);
                        } else { this.girando = false; }
                    } else {
                        alert(data.message);
                        this.girando = false;
                    }
                });
        },
        
        // FUNÇÃO MODIFICADA PARA CONTROLAR TODO O CONTEÚDO DO MODAL
        mostrarResultado(premio) {
            this.premioGanho = premio.descricao;
            this.tipoPremio = premio.tipo_premio;

            if (premio.tipo_premio === 'nada') {
                this.modalTitulo = 'Não foi dessa vez!';
                this.modalSubtitulo = 'Mais sorte na próxima!';
            } else {
                this.modalTitulo = 'Parabéns!';
                this.modalSubtitulo = 'Você ganhou:';
            }
            
            this.modalAberto = true;
            this.girando = false;
        },

        // FUNÇÃO ADICIONADA PARA FECHAR O MODAL E RECARREGAR
        fecharModalERecarregar() {
            this.modalAberto = false;
            setTimeout(() => { location.reload(); }, 300);
        }
    }));
});
</script>

@endsection