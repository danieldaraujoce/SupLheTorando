@extends('layouts.app') {{-- Usando seu layout de cliente --}}

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- =================================================================== --}}
    {{-- SEÇÃO DE APRESENTAÇÃO DO PERFIL (O SEU CÓDIGO ORIGINAL MANTIDO) --}}
    {{-- =================================================================== --}}
    <div class="bg-white rounded-2xl p-6 shadow-md mb-6 text-center">
        <div class="relative w-24 h-24 mx-auto mb-4">
            @if(auth()->user()->avatar)
            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar de {{ auth()->user()->nome }}"
                class="w-full h-full rounded-full object-cover">
            @else
            <div
                class="w-full h-full rounded-full bg-primary/20 text-primary flex items-center justify-center text-4xl font-bold">
                @php
                $nameParts = explode(' ', auth()->user()->nome);
                $initials = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
                @endphp
                <span>{{ $initials }}</span>
            </div>
            @endif

            @if($nivel && isset($nivel->imagem_emblema))
            <img src="{{ asset('storage/' . $nivel->imagem_emblema) }}" alt="Emblema do Nível {{ $nivel->nome }}"
                class="absolute -bottom-1 -right-1 h-10 w-10 object-cover" title="Nível {{ $nivel->nome }}">
            @else
            <div class="absolute -bottom-1 -right-1 bg-gray-400 text-white rounded-full h-8 w-8 flex items-center justify-center border-2 border-white"
                title="Nível não definido">
                <i class="fas fa-question"></i>
            </div>
            @endif
        </div>
        <h2 class="text-2xl font-bold text-black">{{ auth()->user()->nome }}</h2>
        <p class="text-gray-500">{{ auth()->user()->email }}</p>
        <span class="mt-2 inline-flex rounded-full bg-yellow-100 py-1 px-3 text-sm font-medium text-yellow-600">
            Membro {{ $nivel ? $nivel->nome : 'Iniciante' }}
        </span>
    </div>

    {{-- Painel de Estatísticas (O SEU CÓDIGO ORIGINAL MANTIDO) --}}
    <div class="grid grid-cols-2 gap-4 mb-8 sm:grid-cols-4">
        <div class="bg-white rounded-2xl p-4 shadow-md text-center">
            <p class="text-2xl font-bold text-primary"><i class="fas fa-coins"></i> {{ number_format(auth()->user()->coins, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500">Moedas</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-md text-center">
            <p class="text-2xl font-bold text-primary">{{ auth()->user()->giros_roleta }}</p>
            <p class="text-xs text-gray-500">Giros</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-md text-center">
            <p class="text-2xl font-bold text-primary">{{ $missoesConcluidas }}</p>
            <p class="text-xs text-gray-500">Missões</p>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-md text-center">
            <p class="text-2xl font-bold text-primary">{{ $quizzesConcluidos }}</p>
            <p class="text-xs text-gray-500">Quizzes</p>
        </div>
    </div>
    
    {{-- =================================================================== --}}
    {{-- SEÇÃO DE FORMULÁRIOS (AGORA USANDO OS COMPONENTES BREEZE) --}}
    {{-- =================================================================== --}}
    <div class="space-y-6">
        {{-- Card para Atualizar Informações do Perfil --}}
        <div class="p-4 sm:p-8 bg-white shadow-lg rounded-2xl">
            <div class="max-w-xl">
                {{-- Aqui incluímos o formulário que você enviou --}}
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Card para Atualizar Senha --}}
        <div class="p-4 sm:p-8 bg-white shadow-lg rounded-2xl">
            <div class="max-w-xl">
                {{-- Aqui incluímos o formulário que você enviou --}}
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- Card para Excluir Conta --}}
        <div class="p-4 sm:p-8 bg-white shadow-lg rounded-2xl">
            <div class="max-w-xl">
                {{-- Aqui incluímos o formulário que você enviou --}}
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

</div>

{{-- O componente <x-modal> que você enviou será usado pelos formulários acima automaticamente --}}
{{-- Certifique-se de que os seus controllers de perfil/senha estão a retornar as views parciais corretas em caso de erro de validação --}}
@endsection