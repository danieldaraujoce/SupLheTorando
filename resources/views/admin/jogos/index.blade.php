@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Jogue e Ganhe</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Jogue e Ganhe</li>
        </ol>
    </nav>
</div>

{{-- Card Principal --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    {{-- Cabeçalho Interno do Card --}}
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h4 class="text-xl font-semibold text-black">Galeria de Mini-Jogos</h4>
        <p class="text-sm text-gray-500 mt-1">Ative ou desative os jogos e gerencie os prêmios de cada um.</p>
    </div>

    {{-- Grid de Jogos --}}
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($jogos as $jogo)
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm flex flex-col">
            {{-- Aqui você pode adicionar uma imagem para o jogo, se desejar --}}
            {{-- <img src="{{ asset('path/para/imagem/' . $jogo->slug . '.jpg') }}" class="rounded-t-2xl h-40
            object-cover"> --}}
            <div class="p-6 flex-grow flex flex-col">
                <h3 class="text-xl font-bold text-black">{{ $jogo->nome }}</h3>
                <p class="text-sm text-gray-500 mt-2 flex-grow">{{ $jogo->descricao }}</p>
                <div class="mt-6 flex justify-between items-center">
                    <form action="{{ route('admin.jogos.update', $jogo->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select name="status" onchange="this.form.submit()"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                            <option value="ativo" {{ $jogo->status == 'ativo' ? 'selected' : '' }}>Ativo</option>
                            <option value="inativo" {{ $jogo->status == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </form>
                    <a href="{{ route('admin.jogos.edit', $jogo->id) }}"
                        class="rounded-lg bg-primary py-2 px-5 font-medium text-white hover:bg-opacity-90 whitespace-nowrap">
                        Gerenciar Prêmios
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection