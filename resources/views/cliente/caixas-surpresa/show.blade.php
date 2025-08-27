@extends('layouts.app')

@section('content')
<div class="bg-white rounded-2xl p-6 shadow-lg max-w-2xl mx-auto text-center" x-data="caixaSurpresa()">
    <h1 class="text-3xl font-bold text-black">{{ $caixa->nome }}</h1>
    <p class="text-gray-600 mt-1 mb-8">{{ $caixa->descricao }}</p>

    {{-- A Caixa --}}
    <div class="relative w-48 h-48 mx-auto cursor-pointer" @click="abrirCaixa('{{ route('cliente.caixas-surpresa.abrir', $caixa->id) }}')">
        <p style="font-size:130px" :class="{ 'scale-110': animando }">&#127873;</p>
    
    
    </div>
    
    <button @click="abrirCaixa('{{ route('cliente.caixas-surpresa.abrir', $caixa->id) }}')" :disabled="abrindo"
        class="mt-8 rounded-full bg-primary px-10 py-4 font-bold text-white text-xl uppercase tracking-widest hover:bg-opacity-90 disabled:bg-gray-400">
        <span x-show="!abrindo">Abrir 500🪙</span>
        <span x-show="abrindo"><i class="fas fa-spinner animate-spin"></i> Abrindo...</span>
    </button>
    
    {{-- Modal de Resultado --}}
    <div x-show="modalAberto" @click.away="modalAberto = false" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-2xl p-8 text-center max-w-sm w-full">
            <i class="fas fa-trophy text-6xl text-yellow-400"></i>
            <h2 class="text-2xl font-bold text-black mt-4">Parabéns!</h2>
            <p class="text-gray-600 mt-2">Você ganhou:</p>
            <p class="text-3xl font-bold text-primary my-4" x-text="premioGanho"></p>
            <button @click="modalAberto = false" class="w-full rounded-lg bg-primary py-3 font-medium text-white hover:bg-opacity-90">Continuar</button>
        </div>
    </div>
</div>

<script>
function caixaSurpresa() {
    return {
        abrindo: false,
        animando: false,
        modalAberto: false,
        premioGanho: '',

        abrirCaixa(url) {
            if (this.abrindo) return;
            this.abrindo = true;
            this.animando = true;

            setTimeout(() => { // Simula uma animação de "chacoalhar"
                fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        this.premioGanho = data.premio.descricao;
                        this.modalAberto = true;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(() => alert('Ocorreu um erro.'))
                .finally(() => {
                    this.abrindo = false;
                    this.animando = false;
                });
            }, 1000);
        }
    }
}
</script>
@endsection