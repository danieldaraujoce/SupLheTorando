@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Adicionar Novo Cliente</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.clientes.index') }}">Clientes /</a></li>
            <li class="text-sm font-medium text-primary">Adicionar</li>
        </ol>
    </nav>
</div>

{{-- Container Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.clientes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Exibição de Erros de Validação --}}
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
                            <input type="text" name="nome" placeholder="Nome do Cliente" value="{{ old('nome') }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">WhatsApp</label>
                            <input type="text" name="whatsapp" x-data x-mask="(99) 99999-9999"
                                placeholder="(99) 99999-9999" value="{{ old('whatsapp') }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="mb-3 block text-sm font-medium text-black">Endereço de E-mail</label>
                            <input type="email" name="email" placeholder="email@exemplo.com" value="{{ old('email') }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                    </div>
                </div>

                {{-- Seção: Segurança --}}
                <div>
                    <h4 class="mb-6 text-lg font-semibold text-gray-800">Segurança</h4>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Senha</label>
                            <input type="password" name="senha" placeholder="Crie uma senha"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Confirmar Senha</label>
                            <input type="password" name="senha_confirmation" placeholder="Repita a senha"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coluna da Direita (Menor) --}}
            <div class="lg:col-span-1 flex flex-col gap-9">
                {{-- Seção: Avatar --}}
                <div
                    x-data="{ avatarPreview: null, updatePreview(event) { const file = event.target.files[0]; if (file) { this.avatarPreview = URL.createObjectURL(file); } } }">
                    <h4 class="mb-6 text-lg font-semibold text-gray-800">Avatar do Cliente</h4>
                    <div class="mb-4 flex items-center gap-4">
                        <img x-show="!avatarPreview" src="{{ asset('img/perfis/default.png') }}" alt="Avatar Padrão"
                            class="rounded-full h-20 w-20 object-cover">
                        <img x-show="avatarPreview" :src="avatarPreview" alt="Preview do Avatar"
                            class="rounded-full h-20 w-20 object-cover">
                        <div>
                            <input type="file" name="avatar" @change="updatePreview" class="hidden" id="avatar-upload">
                            <label for="avatar-upload"
                                class="cursor-pointer rounded-lg bg-primary py-2 px-4 text-sm text-white hover:bg-opacity-90">
                                Escolher Imagem
                            </label>
                            <p class="mt-2 text-xs text-gray-500">PNG, JPG. Máx 2MB.</p>
                        </div>
                    </div>
                </div>
                {{-- Seção: Status e Moedas --}}
                <div>
                    <h4 class="mb-6 text-lg font-semibold text-gray-800">Status e Moedas</h4>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Status</label>
                            <select name="status"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Aguardando Liberação</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Moedas (Coins)</label>
                            <input type="number" name="coins" placeholder="0" value="{{ old('coins', 0) }}"
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
                Salvar Cliente
            </button>
        </div>
    </form>
</div>
@endsection