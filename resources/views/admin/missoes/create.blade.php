@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Criar Nova Missão</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.missoes.index') }}">Missões /</a></li>
            <li class="text-sm font-medium text-primary">Adicionar</li>
        </ol>
    </nav>
</div>

{{-- Card Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.missoes.store') }}" method="POST">
        @csrf
        <div x-data="{ tipoMissao: 'compra' }">
            <h3 class="mb-6 text-lg font-semibold text-black">Configuração da Missão</h3>

            {{-- Campos Principais --}}
            <div class="mb-6"><label class="mb-3 block text-sm font-medium text-black">Título da Missão</label><input
                    type="text" name="titulo" placeholder="Ex: Semana das Frutas"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required></div>
            <div class="mb-6"><label class="mb-3 block text-sm font-medium text-black">Descrição</label><textarea
                    name="descricao" rows="3" placeholder="Descreva o que o cliente precisa fazer"
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"></textarea>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div><label class="mb-3 block text-sm font-medium text-black">Recompensa em Moedas</label><input
                        type="number" name="coins_recompensa" placeholder="Ex: 100"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                        required></div>
                <div><label class="mb-3 block text-sm font-medium text-black">Tipo de Missão</label>
                    <select name="tipo_missao" x-model="tipoMissao"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        <option value="compra">Compra</option>
                        <option value="engajamento">Engajamento no App</option>
                        <option value="social">Social</option>
                    </select>
                </div>
            </div>

            {{-- CAMPOS INTERATIVOS DO OBJETIVO DA MISSÃO --}}
            <div class="mt-6 border-t border-gray-200 pt-6">
                <h4 class="mb-4 text-md font-medium text-black">Objetivo da Missão</h4>

                {{-- Objetivo para Missão de COMPRA --}}
                <div x-show="tipoMissao === 'compra'" class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div><label class="mb-3 block text-sm font-medium text-black">Tipo de Item</label><select
                            name="meta_item_tipo_compra"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                            <option value="categoria">Categoria de Produtos</option>
                            <option value="produto">Produto Específico</option>
                        </select></div>
                    <div><label class="mb-3 block text-sm font-medium text-black">ID do Item</label><input type="number"
                            name="meta_item_id_compra" placeholder="ID do Produto/Categoria"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                    </div>
                    <div><label class="mb-3 block text-sm font-medium text-black">Quantidade</label><input type="number"
                            name="meta_quantidade_compra" value="1"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                    </div>
                </div>

                {{-- Objetivo para Missão de ENGAJAMENTO --}}
                <div x-show="tipoMissao === 'engajamento'" class="grid grid-cols-1 gap-6">
                    <div><label class="mb-3 block text-sm font-medium text-black">Ação de Engajamento</label><select
                            name="meta_item_tipo_engajamento"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                            <option value="responder_quiz">Responder Quiz</option>
                            <option value="resgatar_recompensa">Resgatar Recompensa</option>
                            <option value="checkin_diario">Check-in Diário</option>
                        </select></div>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div><label class="mb-3 block text-sm font-medium text-black">Data de Início</label><input type="date"
                        name="data_inicio"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                        required>
                </div>
                <div><label class="mb-3 block text-sm font-medium text-black">Data de Fim</label><input type="date"
                        name="data_fim"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                        required></div>
            </div>

            {{-- Botões de Ação --}}
            <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.missoes.index') }}"
                    class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit"
                    class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Salvar
                    Missão</button>
            </div>
        </div>
    </form>
</div>
@endsection