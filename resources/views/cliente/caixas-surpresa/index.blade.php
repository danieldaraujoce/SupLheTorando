@extends('layouts.app')

@section('content')
{{-- Título da Página --}}
<div class="mb-6 text-center">
    <h2 class="text-3xl font-bold text-black">🎁 Caixas Surpresa</h2>
    <p class="text-gray-600">Use suas moedas para abrir caixas e ganhar prêmios!</p>
</div>

{{-- Grid de Caixas --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($caixas as $caixa)
    <div class="bg-white rounded-2xl p-6 shadow-md flex flex-col">
        <div class="flex-grow">
            <div class="h-32 bg-gray-200 rounded-lg flex items-center justify-center mb-4">
            <p style="font-size:80px">&#127873;</p>           
            </div>
            <h3 class="font-bold text-center text-xl text-black">{{ $caixa->nome }}</h3>
            <p class="text-sm text-center text-gray-600 mt-2">{{ $caixa->descricao }}</p>
        </div>
        <div class="mt-6">
            <a href="{{ route('cliente.caixas-surpresa.show', $caixa->id) }}"
                class="w-full text-center block rounded-lg bg-primary py-3 px-5 font-medium text-white hover:bg-opacity-90">
                Ver Caixa
            </a>
        </div>
    </div>
    @empty
    <div class="md:col-span-3 text-center text-gray-500 py-16">
        <i class="fas fa-box fa-3x mb-3 text-gray-300"></i>
        <h3 class="text-lg font-medium">Nenhuma caixa surpresa disponível no momento.</h3>
    </div>
    @endforelse
</div>
@endsection