@extends('layouts.app')

@section('content')

{{-- Título da Página --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-black">🛒 {{ $titulo_pagina }}</h2>
    <p class="text-gray-600">Detalhes do Pedido #{{ $pedido->id }}</p>
</div>

{{-- Detalhes do Pedido --}}
<div class="bg-white rounded-2xl p-6 shadow-lg">
    <div class="mb-6">
        <p class="text-sm text-gray-500">Data do Pedido: <span class="font-medium text-gray-700">{{ $pedido->created_at->format('d/m/Y H:i') }}</span></p>
        <p class="text-sm text-gray-500">Status: <span class="font-medium capitalize text-gray-700">{{ str_replace('_', ' ', $pedido->status) }}</span></p>
    </div>

    {{-- Lista de Itens do Pedido --}}
    <div class="space-y-4">
        @foreach ($pedido->itens as $item)
            <div class="flex items-center gap-4 border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                <img src="{{ $item->produto->imagem_url ? Storage::url($item->produto->imagem_url) : 'https://via.placeholder.com/64x64' }}"
                     alt="{{ $item->produto->nome }}" class="h-16 w-16 object-cover rounded-lg flex-shrink-0">
                <div class="flex-grow">
                    <h3 class="font-bold text-lg text-black">{{ $item->produto->nome }}</h3>
                    <p class="text-sm text-gray-600">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-lg font-bold text-primary">{{ $item->quantidade }}x</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Total do Pedido --}}
    <div class="mt-6 pt-6 border-t border-gray-200 flex justify-between items-center">
        <h4 class="text-xl font-bold text-black">Total do Pedido:</h4>
        <span class="text-3xl font-bold text-primary">
            R$ {{ number_format($pedido->total, 2, ',', '.') }}
        </span>
    </div>
</div>

{{-- Botão para voltar --}}
<div class="mt-6">
    <a href="{{ route('cliente.historico.index') }}" class="inline-block py-2 px-4 rounded-md text-gray-600 font-semibold bg-gray-200 hover:bg-gray-300 transition-colors">
        Voltar para o Histórico
    </a>
</div>

@endsection