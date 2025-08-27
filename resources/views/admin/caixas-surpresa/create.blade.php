@extends('admin.layouts.app')
@section('content')

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Criar Nova Caixa Surpresa</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.caixas-surpresa.index') }}">Caixas Surpresa /</a></li>
            <li class="text-sm font-medium text-primary">Adicionar</li>
        </ol>
    </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.caixas-surpresa.store') }}" method="POST">
        @csrf
        <h4 class="mb-6 text-lg font-semibold text-gray-800">Informações da Caixa</h4>

        <div class="flex flex-col gap-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Nome da Caixa</label>
                    <input type="text" name="nome" placeholder="Ex: Caixa de Verão, Caixa de Natal" class="h-11 w-full rounded-lg border border-gray-300 px-4" required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Descrição</label>
                    <textarea name="descricao" rows="1" placeholder="Tema ou propósito desta caixa" class="w-full rounded-lg border border-gray-300 py-2.5 px-4"></textarea>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                    <input type="date" name="data_inicio" class="h-11 w-full rounded-lg border border-gray-300 px-4" required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                    <input type="date" name="data_fim" class="h-11 w-full rounded-lg border border-gray-300 px-4" required>
                </div>
            </div>
        </div>

        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.caixas-surpresa.index') }}" class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5">Cancelar</a>
            <button type="submit" class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white">Salvar e Adicionar Itens</button>
        </div>
    </form>
</div>
@endsection