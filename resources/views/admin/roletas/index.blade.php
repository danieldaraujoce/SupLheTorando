@extends('admin.layouts.app')
@section('content')

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Gerenciador de Roletas</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Roletas</li>
        </ol>
    </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Lista de Roletas</h4>
            <p class="text-sm text-gray-500 mt-1">Crie e gerencie as roletas da sorte do seu sistema.</p>
        </div>
        <a href="{{ route('admin.roletas.create') }}"
            class="mt-3 sm:mt-0 flex items-center justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            + Nova Roleta
        </a>
    </div>

    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Título da Roleta</th>
                    <th class="py-4 px-6 font-medium text-black">Nº de Fatias</th>
                    <th class="py-4 px-6 font-medium text-black">Validade</th>
                    <th class="py-4 px-6 font-medium text-black">Status</th>
                    <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roletas as $roleta)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <h5 class="font-semibold text-black">{{ $roleta->titulo }}</h5>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ $roleta->itens->count() }} fatias</p>
                    </td>
                    <td class="py-4 px-6">
                        @if($roleta->data_inicio && $roleta->data_fim)
                            <p class="text-black">{{ $roleta->data_inicio->format('d/m/Y') }} - {{ $roleta->data_fim->format('d/m/Y') }}</p>
                        @else
                            <p class="text-gray-400">N/D</p>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        @if ($roleta->data_inicio && now()->between($roleta->data_inicio, $roleta->data_fim))
                            <p class="inline-flex rounded-full bg-green-100 py-1 px-3 text-sm font-medium text-green-600">
                                Vigente</p>
                        @elseif ($roleta->data_inicio && now()->lt($roleta->data_inicio))
                             <p class="inline-flex rounded-full bg-blue-100 py-1 px-3 text-sm font-medium text-blue-600">
                                Agendada</p>
                        @else
                            <p class="inline-flex rounded-full bg-gray-100 py-1 px-3 text-sm font-medium text-gray-500">
                                Expirada</p>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.roletas.edit', $roleta->id) }}"
                                class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Gerenciar</a>
                            <form action="{{ route('admin.roletas.destroy', $roleta->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza?');">
                                @csrf @method('DELETE')
                                <button
                                    class="rounded-lg bg-red-600 py-2 px-4 text-sm font-medium text-white hover:bg-red-700">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 px-4 text-center text-gray-500">Nenhuma roleta cadastrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($roletas->hasPages())
    <div class="p-6 border-t border-gray-200">{{ $roletas->links() }}</div>
    @endif
</div>
@endsection