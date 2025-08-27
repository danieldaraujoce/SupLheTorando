@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página com Breadcrumb --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">
        Editar Campanha de Cashback
    </h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.cashbacks.index') }}">Cashback /</a></li>
            <li class="text-sm font-medium text-primary">Editar</li>
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

{{-- Card Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.cashbacks.update', $cashback->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-9 lg:grid-cols-3">
            {{-- Coluna Principal (Esquerda) --}}
            <div class="lg:col-span-2 flex flex-col gap-9">
                {{-- Seção: Detalhes da Campanha --}}
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Detalhes da Campanha</h3>
                    <div class="flex flex-col gap-6">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Título da Campanha</label>
                            <input type="text" name="titulo" value="{{ old('titulo', $cashback->titulo) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Descrição (Opcional)</label>
                            <textarea name="descricao" rows="6"
                                class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">{{ old('descricao', $cashback->descricao) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coluna da Direita (Sidebar do Formulário) --}}
            <div class="lg:col-span-1 flex flex-col gap-9">
                {{-- Seção: Regras da Recompensa --}}
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Regras da Recompensa</h3>
                    <div class="flex flex-col gap-6">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Tipo de Recompensa</label>
                            <select name="tipo"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                                <option value="porcentagem"
                                    {{ old('tipo', $cashback->tipo) == 'porcentagem' ? 'selected' : '' }}>Porcentagem
                                    (%)</option>
                                <option value="fixo" {{ old('tipo', $cashback->tipo) == 'fixo' ? 'selected' : '' }}>
                                    Valor Fixo (R$)</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Valor da Recompensa</label>
                            <input type="text" name="valor" value="{{ old('valor', $cashback->valor) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Valor Mínimo da Compra
                                (Opcional)</label>
                            <input type="text" name="valor_minimo_compra"
                                value="{{ old('valor_minimo_compra', $cashback->valor_minimo_compra) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        </div>
                    </div>
                </div>

                {{-- Seção: Validade --}}
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Período de Validade</h3>
                    <div class="flex flex-col gap-6">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                            <input type="date" name="data_inicio"
                                value="{{ old('data_inicio', \Carbon\Carbon::parse($cashback->data_inicio)->format('Y-m-d')) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                            <input type="date" name="data_fim"
                                value="{{ old('data_fim', \Carbon\Carbon::parse($cashback->data_fim)->format('Y-m-d')) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.cashbacks.index') }}"
                class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                Atualizar Campanha
            </button>
        </div>
    </form>
</div>
@endsection