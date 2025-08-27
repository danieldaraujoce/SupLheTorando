@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página com Breadcrumb --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Gerenciar Destaques</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Destaques</li>
        </ol>
    </nav>
</div>

{{-- Container Principal --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    {{-- Cabeçalho Interno do Card --}}
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Destaques da Home</h4>
            <p class="text-sm text-gray-500 mt-1">Controle os cards de missões e prêmios da página inicial.</p>
        </div>
        <a href="{{ route('admin.destaques-home.create') }}"
            class="mt-3 sm:mt-0 flex justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            + Novo Destaque
        </a>
    </div>

    {{-- Tabela de Destaques --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Imagem</th>
                    <th class="py-4 px-6 font-medium text-black">Título</th>
                    <th class="py-4 px-6 font-medium text-black">Ordem</th>
                    <th class="py-4 px-6 font-medium text-black">Status</th> <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($destaques as $destaque)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-6">
                        {{-- Usa o novo acessor image_url do model --}}
                        <img src="{{ $destaque->image_url }}" alt="{{ $destaque->titulo }}"
                            class="h-12 w-16 object-cover rounded-md">
                    </td>
                    <td class="py-4 px-6">
                        <h5 class="font-semibold text-black">{{ $destaque->titulo }}</h5>
                        <p class="text-sm text-gray-500">{{ $destaque->subtitulo }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ $destaque->ordem }}</p>
                    </td>
                    <td class="py-4 px-6"> @if($destaque->status == 'ativo')
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">Ativo</span>
                        @else
                            <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-700">Inativo</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.destaques-home.edit', $destaque->id) }}"
                                class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Editar</a>
                            <form action="{{ route('admin.destaques-home.destroy', $destaque->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir este destaque?');">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="rounded-lg bg-red-600 py-2 px-4 text-sm font-medium text-white hover:bg-red-700">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    {{-- CORREÇÃO: Ajuste no colspan para 5 colunas --}}
                    <td colspan="5" class="py-10 px-4 text-center text-gray-500">
                        Nenhum destaque cadastrado para a home.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection