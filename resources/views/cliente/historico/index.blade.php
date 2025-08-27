@extends('layouts.app')

@section('content')

{{-- Título da Página --}}
<div class="mb-6">
    <h2 class="text-2xl font-bold text-black">🛒 {{ $titulo_pagina }}</h2>
    <p class="text-gray-600">Acompanhe seus pedidos e reveja suas compras anteriores.</p>
</div>

{{-- Lista de Pedidos --}}
<div class="bg-white rounded-2xl p-6 shadow-lg">
    @forelse ($pedidos as $pedido)
        <a href="{{ route('cliente.historico.show', $pedido->id) }}" class="block p-4 mb-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-bold text-lg text-black">Pedido #{{ $pedido->id }}</h3>
                <span class="text-sm font-medium text-gray-500">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-md font-medium text-gray-700">Total:</span>
                <span class="text-lg font-bold text-primary">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
            </div>
            <div class="mt-2 text-sm text-gray-500">
                <p>Status: <span class="capitalize">{{ str_replace('_', ' ', $pedido->status) }}</span></p>
            </div>
        </a>
    @empty
        <div class="text-center text-gray-500 py-10">
            <i class="fas fa-history fa-3x mb-3 text-gray-300"></i>
            <h3 class="text-lg font-medium">Você ainda não tem pedidos.</h3>
            <p class="text-sm">Finalize uma compra para começar a registrar seu histórico.</p>
        </div>
    @endforelse

    {{-- Paginação --}}
    <div class="mt-6">
        {{ $pedidos->links() }}
    </div>
</div>

@endsection