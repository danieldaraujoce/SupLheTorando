@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Catálogo de Recompensas</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Recompensas</li>
        </ol>
    </nav>
</div>

{{-- Card Principal --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    {{-- Cabeçalho Interno do Card --}}
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Lista de Recompensas</h4>
            <p class="text-sm text-gray-500 mt-1">Gerencie os prêmios que os clientes podem resgatar com moedas.</p>
        </div>
        <a href="{{ route('admin.recompensas.create') }}"
            class="mt-3 sm:mt-0 flex items-center justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            + Nova Recompensa
        </a>
    </div>

    {{-- Tabela de Recompensas --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Recompensa</th>
                    <th class="py-4 px-6 font-medium text-black">Custo (Moedas)</th>
                    <th class="py-4 px-6 font-medium text-black">Nível Mínimo</th>
                    <th class="py-4 px-6 font-medium text-black">Estoque</th>
                    <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recompensas as $recompensa)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <h5 class="font-semibold text-black">{{ $recompensa->nome }}</h5>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-medium text-primary">{{ $recompensa->custo_coins }} 🪙</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ $recompensa->nivel->nome ?? 'Qualquer Nível' }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ $recompensa->estoque ?? 'Ilimitado' }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.recompensas.edit', $recompensa->id) }}"
                                class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Editar</a>
                            <form action="{{ route('admin.recompensas.destroy', $recompensa->id) }}" method="POST"
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
                    <td colspan="5" class="py-10 px-4 text-center text-gray-500">Nenhuma recompensa encontrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($recompensas->hasPages())
    <div class="p-6 border-t border-gray-200">{{ $recompensas->links() }}</div>
    @endif
</div>
@endsection