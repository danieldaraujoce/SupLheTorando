@extends('admin.layouts.app')

@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Calculadora de Moedas</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Calculadora</li>
        </ol>
    </nav>
</div>

{{-- Card da Calculadora --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6" x-data="{
    valorProduto: 0,
    custoProduto: 0,
    margemLucro: 30, // Margem de lucro padrão
    moedasPorReal: 100, // 100 moedas por R$1,00
    valorCalculado: 0,
    moedasCalculadas: 0,

    calcular() {
        const margem = this.margemLucro / 100;
        const valorReal = this.custoProduto > 0 
            ? this.custoProduto / (1 - margem)
            : this.valorProduto;

        this.valorCalculado = valorReal.toFixed(2);
        this.moedasCalculadas = Math.round(valorReal * this.moedasPorReal);
    }
}">
    <h4 class="mb-6 text-lg font-semibold text-black">Cálculo de Preço e Recompensa</h4>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="mb-3 block text-sm font-medium text-black">
                Custo do Produto (R$)
            </label>
            <input type="number" step="0.01" min="0" x-model.debounce.500ms="custoProduto" @input="calcular()"
                placeholder="Ex: 10.00"
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
            <p class="mt-2 text-xs text-gray-500">O valor que você paga pelo produto.</p>
        </div>
        <div>
            <label class="mb-3 block text-sm font-medium text-black">
                Valor do Produto (R$)
            </label>
            <input type="number" step="0.01" min="0" x-model.debounce.500ms="valorProduto" @input="calcular()"
                placeholder="Ex: 15.00"
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
            <p class="mt-2 text-xs text-gray-500">O valor que será exibido para venda. (Preenche automaticamente se você usar o Custo).</p>
        </div>
        <div>
            <label class="mb-3 block text-sm font-medium text-black">
                Margem de Lucro (%)
            </label>
            <input type="number" step="1" x-model.debounce.500ms="margemLucro" @input="calcular()"
                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
        </div>
    </div>
    
    <div class="mt-6 border-t border-gray-200 pt-6">
        <h4 class="text-xl font-semibold text-black mb-4">Resultados</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="rounded-lg bg-gray-50 p-4">
                <p class="text-sm font-medium text-gray-600">Preço de Venda Sugerido (R$)</p>
                <p class="text-2xl font-bold text-black mt-1" x-text="valorCalculado > 0 ? valorCalculado : '0.00'"></p>
            </div>
            <div class="rounded-lg bg-gray-50 p-4">
                <p class="text-sm font-medium text-gray-600">Moedas de Recompensa Sugeridas</p>
                <p class="text-2xl font-bold text-primary mt-1" x-text="moedasCalculadas > 0 ? moedasCalculadas : '0'"></p>
                <p class="text-xs text-gray-500 mt-1" x-show="moedasCalculadas > 0">
                    Calculado com base em <span x-text="moedasPorReal"></span> moedas por R$1,00.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection