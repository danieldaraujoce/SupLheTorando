@extends('layouts.app')

@section('content')

{{-- Título da Página --}}
<div class="mb-6 text-center">
    <h2 class="text-3xl font-bold text-black">🎁 Loja de Brindes</h2>
    <p class="text-gray-600">Use suas moedas para resgatar prêmios incríveis!</p>
</div>

{{-- Grid de Recompensas --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse ($recompensas as $recompensa)
    @php
        $podeResgatar = $usuario->coins >= $recompensa->custo_coins && ($recompensa->estoque == 0 || $recompensa->estoque > 0) && ($recompensa->nivel_necessario_id == null || $usuario->nivel_id >= $recompensa->nivel_necessario_id);
    @endphp
    <div class="bg-white rounded-2xl p-6 shadow-md flex flex-col">
        <div class="flex items-center gap-4">
             <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                <p style="font-size:40px">&#127873;</p>
            </div>
            <div class="flex-grow">
                <h3 class="font-bold text-lg text-black">{{ $recompensa->nome }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $recompensa->descricao }}</p>
            </div>
        </div>
        <div class="mt-4 flex justify-between items-center border-t border-gray-200 pt-4">
            <span class="text-2xl font-bold text-primary">{{ $recompensa->custo_coins }} 🪙</span>
            <form action="{{ route('cliente.recompensas.resgatar', $recompensa->id) }}" method="POST">
                @csrf
                <button type="submit" @disabled(!$podeResgatar)
                    class="rounded-lg bg-primary py-2 px-5 font-medium text-white hover:bg-opacity-90 disabled:bg-gray-400 disabled:cursor-not-allowed">
                    Resgatar
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="md:col-span-2 text-center text-gray-500 py-16">
        <i class="fas fa-gift fa-3x mb-3 text-gray-300"></i>
        <h3 class="text-lg font-medium">Nenhuma recompensa disponível no momento.</h3>
    </div>
    @endforelse
</div>
@endsection