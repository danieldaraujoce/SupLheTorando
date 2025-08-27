@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Criar Nova Promoção de Combo</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.promocoes.index') }}">Promoções /</a></li>
            <li class="text-sm font-medium text-primary">Adicionar</li>
        </ol>
    </nav>
</div>

{{-- Exibição de Erros de Validação (se houver) --}}
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
    <form action="{{ route('admin.promocoes.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-9 lg:grid-cols-3">
            {{-- Coluna Principal (Esquerda) --}}
            <div class="lg:col-span-2 flex flex-col gap-9">
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Detalhes do Combo</h3>
                    <div class="mb-6">
                        <label class="mb-3 block text-sm font-medium text-black">Título do Combo</label>
                        <input type="text" name="titulo" value="{{ old('titulo') }}"
                            placeholder="Ex: Combo Noite da Pizza"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                            required>
                    </div>
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Descrição</label>
                        <textarea name="descricao" rows="4"
                            placeholder="Ex: Leve os ingredientes da pizza e ganhe desconto"
                            class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">{{ old('descricao') }}</textarea>
                    </div>
                </div>
                <div>
                    <h3 class="mb-5 text-lg font-semibold text-black">Produtos do Combo</h3>
                    <label class="mb-3 block text-sm font-medium text-black">Selecione os produtos que fazem parte desta
                        promoção</label>
                    <p class="text-xs text-gray-500 mb-3">Segure Ctrl (ou Cmd) e clique para selecionar mais de um
                        produto.</p>
                    <select name="produtos[]" multiple class="w-full rounded-lg border border-gray-300 p-4" size="8">
                        @foreach($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Coluna da Direita --}}
            <div class="lg:col-span-1 flex flex-col gap-9">
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Regras e Recompensas</h3>
                    <div class="flex flex-col gap-6">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Tipo de Desconto</label>
                            <select name="tipo_desconto"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                                <option value="porcentagem">Porcentagem (%)</option>
                                <option value="fixo">Valor Fixo (R$)</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Valor do Desconto</label>
                            <input type="text" name="valor_desconto" value="{{ old('valor_desconto') }}"
                                placeholder="Ex: 20 ou 15.00"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Caixa Surpresa (Opcional)</label>
                            <select name="caixa_surpresa_id"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                                <option value="">Nenhuma Caixa Surpresa</option>
                                @foreach($caixas as $caixa)
                                <option value="{{ $caixa->id }}">{{ $caixa->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Período de Validade</h3>
                    <div class="flex flex-col gap-6">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                            <input type="date" name="data_inicio" value="{{ old('data_inicio') }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                            <input type="date" name="data_fim" value="{{ old('data_fim') }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.promocoes.index') }}"
                class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Salvar
                Promoção</button>
        </div>
    </form>
</div>
@endsection