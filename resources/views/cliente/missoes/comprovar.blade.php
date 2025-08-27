@extends('layouts.app')
@section('content')
<div class="mb-6 text-center">
    {{-- 1. ÍCONE ADICIONADO AO TÍTULO --}}
    <div class="flex items-center justify-center gap-3">
        <i class="fas fa-bullseye text-primary text-2xl"></i>
        <h1 class="text-3xl font-bold text-black">Comprovar Missão</h1>
    </div>
    <p class="text-gray-600 mt-1">{{ $missao->titulo }}</p>
</div>

<div class="bg-white rounded-2xl p-6 shadow-lg max-w-2xl mx-auto">
    <form action="{{ route('cliente.missoes.storeComprovante', $missao->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="mb-6">
            <p class="text-lg font-medium text-black">
                {{ $missao->descricao }}
            </p>
            {{-- 2. LINHA DIVISÓRIA ADICIONADA --}}
            <hr class="my-6">
            <label for="comprovacao" class="mb-3 block text-sm font-medium text-black">
                Envie seu comprovante (Print da tela, etc.)
            </label>
            <input type="file" name="comprovacao" id="comprovacao"
                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                required>
            @error('comprovacao') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
        </div>
        <div class="mt-8 flex justify-end gap-4">
            <a href="{{ route('cliente.missoes.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Cancelar</a>
            {{-- 3. BOTÃO ALTERADO PARA AZUL E BRANCO --}}
            <button type="submit"
                class="rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Enviar para
                Análise</button>
        </div>
    </form>
</div>
@endsection