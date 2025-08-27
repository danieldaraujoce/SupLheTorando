@extends('admin.layouts.app')
@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Novo Nível de Fidelidade</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.niveis.index') }}">Níveis /</a></li>
            <li class="text-sm font-medium text-primary">Adicionar</li>
        </ol>
    </nav>
</div>


<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.niveis.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div>
            <h4 class="mb-6 text-lg font-semibold text-gray-800">Informações do Nível</h4>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Nome do Nível</label>
                    <input type="text" name="nome" placeholder="Ex: Bronze, Prata, Ouro" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10" required>
                </div>
            </div>
        </div>

        <div x-data="{ valorReal: 0, taxa: {{ $taxa_conversao }} }" class="mt-6 border-t border-gray-200 pt-6">
            <h4 class="mb-6 text-lg font-semibold text-gray-800">Calculadora de Requisito</h4>
            <p class="text-sm text-gray-600 mb-6 -mt-4">Use este campo para converter um valor de gastos (R$) em moedas, com base na sua taxa de conversão atual (R$ 1.00 = {{ $taxa_conversao }} moedas).</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Valor Base em Compras (R$)</label>
                    <input type="number" step="0.01" x-model.number.debounce.300ms="valorReal" placeholder="Ex: 500.00" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Requisito Mínimo de Moedas (Calculado)</label>
                    <input type="number" name="requisito_minimo_coins" :value="Math.round(valorReal * taxa)" class="h-11 w-full rounded-lg border border-gray-200 bg-gray-100 px-4 text-sm text-gray-800 placeholder:text-gray-400" readonly>
                </div>
            </div>
        </div>

        <div class="mt-6 border-t border-gray-200 pt-6">
             <h4 class="mb-6 text-lg font-semibold text-gray-800">Recursos Visuais</h4>
            <label class="mb-3 block text-sm font-medium text-black">Imagem do Emblema (Opcional)</label>
            <input type="file" name="imagem_emblema" class="h-11 w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 file:mr-4 file:h-full file:border-0 file:border-r file:border-solid file:border-gray-300 file:bg-gray-100 file:px-4">
        </div>

        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.niveis.index') }}" class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Salvar Nível</button>
        </div>
    </form>
</div>
@endsection