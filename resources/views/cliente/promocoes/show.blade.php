@extends('layouts.app')

@section('content')

{{-- Card com Título e Descrição da Promoção --}}
<div class="bg-white rounded-2xl p-6 shadow-md mb-6">
    <h1 class="text-3xl font-bold text-black">💰 {{ $promocao->titulo }}</h1>
    @if($promocao->descricao)
        <p class="text-gray-600 mt-2">{{ $promocao->descricao }}</p>
    @endif
</div>

{{-- Card Principal com os Detalhes --}}
<div class="bg-white rounded-2xl p-6 shadow-md">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        {{-- Lado Esquerdo: Lista de Produtos --}}
        <div class="md:col-span-2">
            <h2 class="text-xl font-bold text-black mb-4">Produtos Inclusos no Combo</h2>
            <div class="space-y-4">
                @forelse($promocao->produtos as $produto)
                    <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <img src="{{ $produto->imagem_url ? Storage::url($produto->imagem_url) : 'https://via.placeholder.com/100' }}" 
                             alt="{{ $produto->nome }}" class="w-20 h-20 object-cover rounded-md flex-shrink-0">
                        <div>
                            <h3 class="font-semibold text-black">{{ $produto->nome }}</h3>
                            <p class="text-sm text-gray-500">Preço original: R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500">Não há produtos associados a esta promoção no momento.</p>
                @endforelse
            </div>
        </div>

        {{-- Lado Direito: Card de Ação --}}
        <div class="md:col-span-1">
             <div class="bg-primary/10 rounded-lg p-6 text-center sticky top-6">
                  <p class="text-sm text-primary font-semibold">DESCONTO DO COMBO</p>
                  <p class="text-5xl font-bold text-primary my-2">
                    {{ $promocao->tipo_desconto == 'porcentagem' ? rtrim(rtrim(number_format($promocao->valor_desconto, 2), '0'), '.') . '%' : 'R$ ' . number_format($promocao->valor_desconto, 2, ',', '.') }}
                  </p>
                  <p class="text-sm text-gray-600">sobre o valor total dos produtos.</p>
                  
                  {{-- Formulário para adicionar o combo ao carrinho --}}
                  <form action="{{ route('cliente.carrinho.adicionarCombo', $promocao->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="mt-6 w-full rounded-lg bg-primary py-3 px-5 text-lg font-medium text-white hover:bg-opacity-90 transition-transform hover:scale-105 focus:outline-none focus:ring-4 focus:ring-primary/30">
                        Adicionar Combo ao Carrinho
                    </button>
                  </form>
             </div>
        </div>

    </div>
</div>
@endsection