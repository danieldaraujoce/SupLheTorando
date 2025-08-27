@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Gerenciador de Missões</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Missões</li>
        </ol>
    </nav>
</div>

{{-- Card Principal --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    {{-- Cabeçalho Interno do Card --}}
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Lista de Missões</h4>
            <p class="text-sm text-gray-500 mt-1">Crie desafios e jornadas para seus clientes.</p>
        </div>
        <a href="{{ route('admin.missoes.create') }}"
            class="mt-3 sm:mt-0 flex items-center justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            + Nova Missão
        </a>
    </div>

    {{-- Tabela de Missões --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Missão</th>
                    <th class="py-4 px-6 font-medium text-black">Tipo</th>
                    <th class="py-4 px-6 font-medium text-black">Recompensa</th>
                    <th class="py-4 px-6 font-medium text-black">Validade</th>
                    <th class="py-4 px-6 font-medium text-black text-center">Status</th>
                    <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($missoes as $missao)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <h5 class="font-semibold text-black">{{ $missao->titulo }}</h5>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black capitalize">{{ $missao->tipo_missao }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-medium text-primary">{{ $missao->coins_recompensa }} 🪙</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ \Carbon\Carbon::parse($missao->data_inicio)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($missao->data_fim)->format('d/m/Y') }}</p>
                    </td>
                    <td class="py-4 px-6 text-center">
                        @if (now()->between($missao->data_inicio, $missao->data_fim))
                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-700">Vigente</span>
                        @elseif (now()->lt($missao->data_inicio))
                            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-600">Agendada</span>
                        @else
                            <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-500">Expirada</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.missoes.edit', $missao->id) }}"
                                class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Editar</a>
                            <form action="{{ route('admin.missoes.destroy', $missao->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta missão?');">
                                @csrf @method('DELETE')
                                <button
                                    class="rounded-lg bg-red-600 py-2 px-4 text-sm font-medium text-white hover:bg-red-700">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 px-4 text-center text-gray-500">Nenhuma missão criada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($missoes->hasPages())
    <div class="p-6 border-t border-gray-200">{{ $missoes->links() }}</div>
    @endif
</div>
@endsection