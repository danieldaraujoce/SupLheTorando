@extends('layouts.app')

@section('content')
{{-- Título da Página --}}
<div class="mb-6 text-center">
    <h2 class="text-3xl font-bold text-black">🎯 Roleta da Sorte</h2>
    <p class="text-gray-600">Gire e ganhe prêmios incríveis!</p>
</div>

{{-- Grid de Roletas --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse ($roletas as $roleta)
    <div class="bg-white rounded-2xl p-6 shadow-md flex flex-col">
        <div class="flex-grow">
            <h3 class="font-bold text-xl text-black">{{ $roleta->titulo }}</h3>
            <p class="text-sm text-gray-600 mt-2">{{ $roleta->descricao }}</p>
        </div>
        <div class="mt-6 flex justify-between items-center">
            <span class="text-sm text-gray-500">{{ $roleta->itens->count() }} prêmios possíveis</span>
            <a href="{{ route('cliente.roletas.show', $roleta->id) }}"
                class="rounded-lg bg-primary py-2 px-5 font-medium text-white hover:bg-opacity-90">
                Jogar Agora
            </a>
        </div>
    </div>
    @empty
    <div class="md:col-span-2 text-center text-gray-500 py-16">
        <i class="fas fa-dharmachakra fa-3x mb-3 text-gray-300"></i>
        <h3 class="text-lg font-medium">Nenhuma roleta disponível no momento.</h3>
    </div>
    @endforelse
</div>
@endsection