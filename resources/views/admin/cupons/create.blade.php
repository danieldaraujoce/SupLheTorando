@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Novo Cupom de Desconto</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.cupons.index') }}">Cupons /</a></li>
            <li class="text-sm font-medium text-primary">Adicionar</li>
        </ol>
    </nav>
</div>

@if ($errors->any())
<div class="mb-6 rounded-md border border-red-400 bg-red-100 p-4 text-sm text-red-700">
    <p class="font-bold mb-2">Foram encontrados alguns erros:</p>
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.cupons.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-9 lg:grid-cols-3">
            {{-- Coluna Principal (Esquerda) --}}
            <div class="lg:col-span-2 flex flex-col gap-9">
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Informações do Cupom</h3>
                    <div class="flex flex-col gap-6">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Código do Cupom</label>
                            <input type="text" name="codigo" placeholder="Ex: BEMVINDO20 (maiúsculas, sem espaço)"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Descrição</label>
                            <textarea name="descricao" rows="4" placeholder="Ex: Cupom de 20% para primeira compra"
                                class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required></textarea>
                        </div>
                        
                        <div x-data="{ tipo: '{{ old('tipo_cupom', 'normal') }}' }" class="p-4 border border-gray-200 rounded-lg mt-6">
                            <h4 class="text-md font-semibold text-black mb-4">Opções Avançadas</h4>
                            <div class="mb-4">
                                <label class="mb-3 block text-sm font-medium text-black">Tipo de Cupom</label>
                                <select name="tipo_cupom" x-model="tipo" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                                    <option value="normal">Normal (Validade por dia)</option>
                                    <option value="relampago">Cupom-Relâmpago (Validade por horas)</option>
                                </select>
                            </div>
                            <div x-show="tipo === 'relampago'" x-transition>
                                <label class="mb-3 block text-sm font-medium text-black">Validade em Horas</label>
                                <input type="number" name="horas_validade" value="{{ old('horas_validade', '') }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800" placeholder="Ex: 2 (para 2 horas)">
                            </div>
                            <div class="mt-4">
                                <label class="mb-3 block text-sm font-medium text-black">Moedas Extras de Fidelidade (Opcional)</label>
                                <input type="number" name="coins_extra_fidelidade" value="{{ old('coins_extra_fidelidade', 0) }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coluna da Direita (Sidebar do Formulário) --}}
            <div class="lg:col-span-1 flex flex-col gap-9">
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Regras do Desconto</h3>
                    <div class="flex flex-col gap-6">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Tipo de Desconto</label>
                            <select name="tipo_desconto" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                                <option value="porcentagem">Porcentagem (%)</option>
                                <option value="fixo">Valor Fixo (R$)</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Valor do Desconto</label>
                            <input type="text" name="valor" placeholder="Ex: 20 (para 20%) ou 15.00 (para R$15)"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Valor Mínimo da Compra (Opcional)</label>
                            <input type="text" name="valor_minimo_compra" placeholder="Ex: 150.00"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Limites e Validade</h3>
                    <div class="flex flex-col gap-6">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Data de Validade</label>
                            <input type="date" name="data_validade"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Limite de Uso Total (Opcional)</label>
                            <input type="number" name="limite_uso_total" placeholder="Ex: 100 (para os 100 primeiros)"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Limite de Uso por Cliente (Opcional)</label>
                            <input type="number" name="limite_uso_por_cliente" value="{{ old('limite_uso_por_cliente', 1) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.cupons.index') }}"
                class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Salvar
                Cupom</button>
        </div>
    </form>
</div>
@endsection