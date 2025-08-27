@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Níveis de Cliente</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Níveis</li>
        </ol>
    </nav>
</div>

{{-- Card Principal --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    {{-- Cabeçalho Interno do Card --}}
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Lista de Níveis</h4>
            <p class="text-sm text-gray-500 mt-1">Gerencie os níveis de fidelidade dos clientes.</p>
        </div>
        <a href="{{ route('admin.niveis.create') }}"
            class="mt-3 sm:mt-0 flex items-center justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            + Novo Nível
        </a>
    </div>

    {{-- Tabela de Níveis --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Nome do Nível</th>
                    <th class="py-4 px-6 font-medium text-black">Requisito (Moedas)</th>
                    <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($niveis as $nivel)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <h5 class="font-semibold text-black">{{ $nivel->nome }}</h5>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ number_format($nivel->requisito_minimo_coins, 0, ',', '.') }} 🪙</p>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.niveis.edit', $nivel->id) }}"
                                class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Editar</a>
                            <form action="{{ route('admin.niveis.destroy', $nivel->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza?');">
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
                    <td colspan="3" class="py-10 px-4 text-center text-gray-500">Nenhum nível encontrado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection