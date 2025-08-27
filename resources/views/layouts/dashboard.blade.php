@extends('layouts.cliente') {{-- Diz ao Laravel para usar nosso novo molde --}}

@section('content') {{-- Tudo dentro desta seção será injetado no @yield('content') --}}

{{-- Card Principal do Herói --}}
<div class="bg-primary text-white rounded-2xl p-6 shadow-lg mb-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm opacity-80">Seu Saldo</p>
            <p class="text-4xl font-bold flex items-center gap-2">
                <i class="fas fa-coins text-yellow-300"></i> {{ number_format($usuario->coins, 0, ',', '.') }}
            </p>
        </div>
        <div>
            <p class="text-sm opacity-80 text-right">Giros na Roleta</p>
            <p class="text-4xl font-bold">{{ $usuario->giros_roleta }}</p>
        </div>
    </div>
    <div class="mt-4 h-2.5 w-full rounded-full bg-black/20">
        <div class="h-2.5 rounded-full bg-yellow-300" style="width: 45%"></div>
        <p class="text-xs text-right mt-1 opacity-80">Progresso para o Nível Prata</p>
    </div>
</div>

{{-- Card "Sua Próxima Conquista" --}}
@if($proxima_missao)
<div class="bg-white rounded-2xl p-6 shadow-md mb-6 border-l-4 border-yellow-400">
    <h3 class="font-bold text-lg text-black">Sua Próxima Conquista</h3>
    <p class="text-gray-600 mt-1">{{ $proxima_missao->titulo }}</p>
    <div class="mt-4 flex justify-between items-center">
        <span class="font-bold text-primary">{{ $proxima_missao->coins_recompensa }} 🪙</span>
        <a href="#" class="rounded-lg bg-yellow-500 py-2 px-5 font-medium text-black hover:bg-yellow-600">Ver Missão</a>
    </div>
</div>
@endif

{{-- Botão Principal de Ação - SCAN & GO --}}
<a href="#"
    class="block w-full text-center bg-gray-800 text-white rounded-2xl p-6 shadow-lg mb-6 transform hover:scale-105 transition-transform duration-300">
    <i class="fas fa-barcode fa-3x mb-2"></i>
    <h2 class="font-bold text-2xl">Iniciar Compras</h2>
    <p class="font-light opacity-80">Escaneie os produtos e pague no caixa sem filas</p>
</a>

{{-- Carrossel de Destaques (Conteúdo da Home) --}}
<h2 class="text-2xl font-bold text-black mb-4">Para Você</h2>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($destaques as $destaque)
    <div class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col">
        <img src="{{ Storage::url($destaque->imagem) }}" alt="{{ $destaque->titulo }}" class="w-full h-40 object-cover">
        <div class="p-6 flex-grow flex flex-col">
            <h3 class="font-bold text-lg text-black">{{ $destaque->titulo }}</h3>
            <p class="text-gray-600 text-sm mt-1">{{ $destaque->subtitulo }}</p>
            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center mt-auto">
                @if($destaque->valor_moedas)
                <span class="font-bold text-primary">{{ $destaque->valor_moedas }} 🪙</span>
                @else
                <span></span>
                @endif
                <a href="{{ $destaque->link_botao }}"
                    class="rounded-lg bg-gray-200 py-2 px-4 text-sm font-medium text-black hover:bg-gray-300">{{ $destaque->texto_botao }}</a>
            </div>
        </div>
    </div>
    @empty
    <p class="col-span-full text-center text-gray-500">Nenhuma novidade no momento.</p>
    @endforelse
</div>

@endsection