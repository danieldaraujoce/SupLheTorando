@extends('layouts.app')

@section('content')

{{-- Título da Página --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-black">Minhas Recompensas</h2>
    <p class="text-gray-600">Confira os prêmios que você já resgatou.</p>
</div>

{{-- Grid de Recompensas --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse ($recompensas as $recompensa)
    <div class="bg-white rounded-2xl p-6 shadow-md flex flex-col">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-gift text-3xl text-gray-400"></i>
            </div>
            <div class="flex-grow">
                <h3 class="font-bold text-lg text-black">{{ $recompensa->recompensa->nome }}</h3>
                <p class="text-sm text-gray-600 mt-1">Resgatado em: {{ $recompensa->data_resgate->format('d/m/Y') }}</p>
            </div>
        </div>
        <div class="mt-4 flex justify-between items-center border-t border-gray-200 pt-4">
            <span class="text-sm text-gray-500 font-medium">Código: <span
                    class="font-bold">{{ $recompensa->codigo_resgate }}</span></span>
            @if($recompensa->status == 'utilizado')
            <span
                class="inline-flex items-center gap-2 rounded-full bg-green-100 py-1 px-3 text-sm font-medium text-green-600">
                <i class="fas fa-check-circle"></i>
                Utilizado
            </span>
            @else
            <span
                class="inline-flex items-center gap-2 rounded-full bg-yellow-100 py-1 px-3 text-sm font-medium text-yellow-600">
                <i class="fas fa-history"></i>
                Resgatado
            </span>
            @endif
        </div>
    </div>
    @empty
    <div class="md:col-span-2 text-center text-gray-500 py-16">
        <i class="fas fa-gift fa-3x mb-3 text-gray-300"></i>
        <h3 class="text-lg font-medium">Nenhuma recompensa resgatada ainda.</h3>
        <p class="text-sm">Vá para a loja e use suas moedas!</p>
    </div>
    @endforelse
</div>
@endsection