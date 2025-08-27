@extends('admin.layouts.app')

@section('content')
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Recompensas Pendentes</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.recompensas.index') }}">Recompensas /</a></li>
            <li class="text-sm font-medium text-primary">Pendentes</li>
        </ol>
    </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Cliente</th>
                    <th class="py-4 px-6 font-medium text-black">Recompensa</th>
                    <th class="py-4 px-6 font-medium text-black">Código de Resgate</th>
                    <th class="py-4 px-6 font-medium text-black">Data do Resgate</th>
                    <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($resgates as $resgate)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <p class="font-semibold text-black">{{ $resgate->usuario->nome }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ $resgate->recompensa->nome }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="font-mono text-base font-bold text-primary">{{ $resgate->codigo_resgate }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ $resgate->created_at->format('d/m/Y') }}</p>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <form action="{{ route('admin.recompensas.resgates.aprovar', $resgate->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja APROVAR este resgate?');">
                                @csrf
                                <button type="submit" class="rounded-lg bg-green-500 py-2 px-3 text-sm font-medium text-white hover:bg-green-600">Aprovar</button>
                            </form>
                            <form action="{{ route('admin.recompensas.resgates.rejeitar', $resgate->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja REJEITAR este resgate? As moedas serão devolvidas ao cliente.');">
                                @csrf
                                <button type="submit" class="rounded-lg bg-red-500 py-2 px-3 text-sm font-medium text-white hover:bg-red-600">Rejeitar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 px-4 text-center text-gray-500">Nenhuma recompensa pendente de validação.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($resgates->hasPages())
    <div class="p-6 border-t border-gray-200">{{ $resgates->links() }}</div>
    @endif
</div>
@endsection