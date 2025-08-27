@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Editar Cliente</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.clientes.index') }}">Clientes /</a></li>
            <li class="text-sm font-medium text-primary">Editar</li>
        </ol>
    </nav>
</div>

{{-- Container Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.clientes.update', $cliente->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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

        {{-- GRID PRINCIPAL --}}
        <div class="grid grid-cols-1 gap-9 lg:grid-cols-3">

            {{-- Coluna da Esquerda (Maior) --}}
            <div class="lg:col-span-2 flex flex-col gap-9">
                {{-- Seção: Informações Pessoais --}}
                <div>
                    <h4 class="mb-6 text-lg font-semibold text-gray-800">Informações Pessoais</h4>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Nome Completo</label>
                            <input type="text" name="nome" placeholder="Nome do Cliente"
                                value="{{ old('nome', $cliente->nome) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">WhatsApp</label>
                            <input type="text" name="whatsapp" x-data x-mask="(99) 99999-9999"
                                placeholder="(99) 99999-9999" value="{{ old('whatsapp', $cliente->whatsapp) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-3 block text-sm font-medium text-black">Endereço de E-mail</label>
                            <input type="email" name="email" placeholder="email@exemplo.com"
                                value="{{ old('email', $cliente->email) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                    </div>
                </div>

                {{-- Seção: Segurança --}}
                <div>
                    <h4 class="mb-6 text-lg font-semibold text-gray-800">Alterar Senha</h4>
                    <p class="mb-4 text-sm text-gray-600 -mt-4">Deixe os campos de senha em branco para não alterá-la.
                    </p>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Nova Senha</label>
                            <input type="password" name="senha" placeholder="Digite a nova senha"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Confirmar Nova Senha</label>
                            <input type="password" name="senha_confirmation" placeholder="Repita a nova senha"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coluna da Direita (Menor) --}}
            <div class="lg:col-span-1 flex flex-col gap-9">
                {{-- Seção: Status e Moedas --}}
                <div>
                    <h4 class="mb-6 text-lg font-semibold text-gray-800">Status e Moedas</h4>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Status</label>
                            <select name="status"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                                <option value="ativo"
                                    {{ old('status', $cliente->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="inativo"
                                    {{ old('status', $cliente->status) == 'inativo' ? 'selected' : '' }}>Aguardando
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Moedas (Coins)</label>
                            <input type="number" name="coins" placeholder="0"
                                value="{{ old('coins', $cliente->coins) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.clientes.index') }}"
                class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                Atualizar Cliente
            </button>
        </div>
    </form>
</div>
@endsection