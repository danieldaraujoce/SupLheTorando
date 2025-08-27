@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">
        Editar Missão
    </h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.missoes.index') }}">Missões /</a></li>
            <li class="text-sm font-medium text-primary">Editar</li>
        </ol>
    </nav>
</div>

@if ($errors->any())
<div class="mb-4 rounded-md border border-red-400 bg-red-100 p-4 text-sm text-red-700">
    <p class="font-bold mb-2">Foram encontrados alguns erros:</p>
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.missoes.update', $missao->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div x-data="{ tipoMissao: '{{ old('tipo_missao', $missao->tipo_missao) }}' }">
            <h3 class="mb-6 text-lg font-semibold text-black">Configuração da Missão</h3>

            <div class="mb-6">
                <label class="mb-3 block text-sm font-medium text-black">Título da Missão</label>
                <input type="text" name="titulo" value="{{ old('titulo', $missao->titulo) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                    required>
            </div>
            <div class="mb-6">
                <label class="mb-3 block text-sm font-medium text-black">Descrição</label>
                <textarea name="descricao" rows="3"
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">{{ old('descricao', $missao->descricao) }}</textarea>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Recompensa em Moedas</label>
                    <input type="number" name="coins_recompensa"
                        value="{{ old('coins_recompensa', $missao->coins_recompensa) }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                        required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Tipo de Missão</label>
                    <select name="tipo_missao" x-model="tipoMissao"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        <option value="compra" @selected(old('tipo_missao', $missao->tipo_missao) == 'compra')>Compra</option>
                        <option value="engajamento" @selected(old('tipo_missao', $missao->tipo_missao) == 'engajamento')>Engajamento no App</option>
                        <option value="social" @selected(old('tipo_missao', $missao->tipo_missao) == 'social')>Social</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-6">
                <h4 class="mb-4 text-md font-medium text-black">Objetivo da Missão</h4>
                <div x-show="tipoMissao === 'compra'" class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Tipo de Item</label>
                        <select name="meta_item_tipo_compra" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                            <option value="categoria" @selected($missao->meta_item_tipo == 'categoria')>Categoria de Produtos</option>
                            <option value="produto" @selected($missao->meta_item_tipo == 'produto')>Produto Específico</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">ID do Item</label>
                        <input type="number" name="meta_item_id_compra" value="{{ $missao->meta_item_id }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                    </div>
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Quantidade</label>
                        <input type="number" name="meta_quantidade_compra" value="{{ $missao->meta_quantidade }}" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                    </div>
                </div>
                <div x-show="tipoMissao === 'engajamento'" class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Ação de Engajamento</label>
                        <select name="meta_item_tipo_engajamento" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                            <option value="responder_quiz" @selected($missao->meta_item_tipo == 'responder_quiz')>Responder Quiz</option>
                            <option value="resgatar_recompensa" @selected($missao->meta_item_tipo == 'resgatar_recompensa')>Resgatar Recompensa</option>
                            <option value="checkin_diario" @selected($missao->meta_item_tipo == 'checkin_diario')>Check-in Diário</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                    <input type="date" name="data_inicio"
                        value="{{ old('data_inicio', \Carbon\Carbon::parse($missao->data_inicio)->format('Y-m-d')) }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                        required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                    <input type="date" name="data_fim"
                        value="{{ old('data_fim', \Carbon\Carbon::parse($missao->data_fim)->format('Y-m-d')) }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                        required>
                </div>
            </div>

            <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
                <a href="{{ route('admin.missoes.index') }}"
                    class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Cancelar</a>
                <button type="submit"
                    class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Atualizar
                    Missão</button>
            </div>
        </div>
    </form>
</div>
@endsection