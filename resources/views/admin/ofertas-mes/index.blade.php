@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Ofertas do Mês</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Ofertas do Mês</li>
        </ol>
    </nav>
</div>

{{-- Card Principal --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    {{-- Cabeçalho Interno do Card --}}
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Lista de Ofertas</h4>
            <p class="text-sm text-gray-500 mt-1">Gerencie os encartes e folhetos de ofertas mensais.</p>
        </div>
        <a href="{{ route('admin.ofertas-mes.create') }}"
            class="mt-3 sm:mt-0 flex items-center justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            + Nova Oferta
        </a>
    </div>

    {{-- Tabela de Ofertas --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Capa</th>
                    <th class="py-4 px-6 font-medium text-black">Título</th>
                    <th class="py-4 px-6 font-medium text-black">Validade</th>
                    <th class="py-4 px-6 font-medium text-black">Status</th>
                    <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ofertas as $oferta)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-3 px-6">
                        <img src="{{ Storage::url($oferta->imagem_capa) }}" alt="{{ $oferta->titulo }}"
                            class="h-12 w-12 object-cover rounded-md">
                    </td>
                    <td class="py-4 px-6">
                        <h5 class="font-semibold text-black">{{ $oferta->titulo }}</h5>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ $oferta->data_inicio->format('d/m/Y') }} -
                            {{ $oferta->data_fim->format('d/m/Y') }}</p>
                    </td>
                    <td class="py-4 px-6">
                        {{-- Lógica de Status Dinâmico --}}
                        @if (now()->between($oferta->data_inicio, $oferta->data_fim))
                            <p class="inline-flex rounded-full bg-green-100 py-1 px-3 text-sm font-medium text-green-600">
                                Vigente
                            </p>
                        @elseif (now()->lt($oferta->data_inicio))
                            <p class="inline-flex rounded-full bg-blue-100 py-1 px-3 text-sm font-medium text-blue-600">
                                Agendada
                            </p>
                        @else
                            <p class="inline-flex rounded-full bg-gray-100 py-1 px-3 text-sm font-medium text-gray-500">
                                Expirada
                            </p>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.ofertas-mes.edit', $oferta->id) }}"
                                class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Editar</a>
                            <form action="{{ route('admin.ofertas-mes.destroy', $oferta->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta oferta?');">
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
                    <td colspan="5" class="py-10 px-4 text-center text-gray-500">Nenhuma oferta do mês encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($ofertas->hasPages())
    <div class="p-6 border-t border-gray-200">{{ $ofertas->links() }}</div>
    @endif
</div>
@endsection