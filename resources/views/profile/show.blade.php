@extends('layouts.app') {{-- Use o seu layout principal do cliente --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-2xl shadow-lg">

        {{-- Uso do Componente: x-application-logo --}}
        <div class="flex justify-center mb-6">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <header>
            <h2 class="text-2xl font-bold text-gray-900 text-center">
                Perfil do Utilizador
            </h2>
            <p class="mt-1 text-sm text-gray-600 text-center">
                Visualize as informações da sua conta.
            </p>
        </header>

        {{-- Início do Formulário --}}
        <div class="mt-6 space-y-6">

            {{-- Uso dos Componentes: x-input-label e x-text-input (desativado) --}}
            <div>
                <x-input-label for="name" value="Nome" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-100" :value="old('name', $user->nome)" disabled />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-gray-100" :value="old('email', $user->email)" disabled />
            </div>

            {{-- Uso dos Componentes: x-danger-button e x-modal --}}
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Apagar Conta</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Depois que a sua conta for apagada, todos os seus recursos e dados serão permanentemente removidos.
                </p>

                <div class="mt-4">
                    {{-- Este botão abre o modal. O nome 'confirm-user-deletion' é importante. --}}
                    <x-danger-button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    >Apagar Conta</x-danger-button>
                </div>
            </div>
        </div>

        {{-- O Modal de Confirmação --}}
        {{-- O 'name' aqui corresponde ao que foi chamado no botão acima. --}}
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="#" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    Tem a certeza de que quer apagar a sua conta?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Por favor, insira a sua senha para confirmar que deseja apagar permanentemente a sua conta.
                </p>

                <div class="mt-6">
                    <x-input-label for="password" value="Senha" class="sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Senha"
                    />

                    {{-- Uso do Componente: x-input-error --}}
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    {{-- O 'x-on:click="$dispatch('close')'" fecha o modal --}}
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Cancelar
                    </x-secondary-button>

                    <x-danger-button class="ml-3">
                        Apagar Conta
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

    </div>
</div>
@endsection