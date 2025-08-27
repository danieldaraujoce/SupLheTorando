@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Cupons de Desconto</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Cupons</li>
        </ol>
    </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Lista de Cupons</h4>
            <p class="text-sm text-gray-500 mt-1">Gerencie todos os cupons promocionais do sistema.</p>
        </div>
        <a href="{{ route('admin.cupons.create') }}"
            class="mt-3 sm:mt-0 flex items-center justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            + Novo Cupom
        </a>
    </div>

    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Código</th>
                    <th class="py-4 px-6 font-medium text-black">Descrição</th>
                    <th class="py-4 px-6 font-medium text-black">Desconto</th>
                    <th class="py-4 px-6 font-medium text-black">Validade</th>
                    <th class="py-4 px-6 font-medium text-black">Status</th>
                    <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cupons as $cupom)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <p class="font-mono text-base font-bold text-primary">{{ $cupom->codigo }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ Str::limit($cupom->descricao, 50) }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black font-medium">
                            {{ $cupom->tipo_desconto == 'porcentagem' ? rtrim(rtrim(number_format($cupom->valor, 2), '0'), '.') . '%' : 'R$ ' . number_format($cupom->valor, 2, ',', '.') }}
                        </p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ $cupom->data_validade->format('d/m/Y') }}</p>
                    </td>
                    <td class="py-4 px-6">
                        @if ($cupom->data_validade->isFuture() || $cupom->data_validade->isToday())
                        <p class="inline-flex rounded-full bg-green-100 py-1 px-3 text-sm font-medium text-green-600">
                            Vigente</p>
                        @else
                        <p class="inline-flex rounded-full bg-gray-100 py-1 px-3 text-sm font-medium text-gray-500">
                            Expirado</p>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.cupons.edit', $cupom->id) }}"
                                class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Editar</a>
                            <form action="{{ route('admin.cupons.destroy', $cupom->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir este cupom?');">
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
                    <td colspan="6" class="py-10 px-4 text-center text-gray-500">Nenhum cupom de desconto encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($cupons->hasPages())
    <div class="p-6 border-t border-gray-200">{{ $cupons->links() }}</div>
    @endif
</div>
@endsection