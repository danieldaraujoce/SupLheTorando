@extends('admin.layouts.app')
@section('content')

{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Missões Pendentes de Validação</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.missoes.index') }}">Missões /</a></li>
            <li class="text-sm font-medium text-primary">Pendentes</li>
        </ol>
    </nav>
</div>

{{-- Card com a Tabela de Submissões --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            {{-- Cabeçalho da Tabela --}}
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Cliente</th>
                    <th class="py-4 px-6 font-medium text-black">Missão</th>
                    <th class="py-4 px-6 font-medium text-black text-center">Comprovante</th>
                    <th class="py-4 px-6 font-medium text-black text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissoes as $submissao)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <p class="font-semibold text-black">{{ $submissao->usuario->nome }}</p>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">{{ $submissao->missao->titulo }}</p>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <a href="{{ asset('storage/' . $submissao->comprovacao_url) }}" target="_blank"
                           class="text-primary hover:underline">
                            Ver Comprovante
                        </a>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            {{-- Botão Aprovar --}}
                            <form action="{{ route('admin.missoes.aprovar', $submissao->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja APROVAR esta missão?');">
                                @csrf
                                <button type="submit" class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Aprovar</button>
                            </form>
                            {{-- Botão Rejeitar --}}
                            <form action="{{ route('admin.missoes.rejeitar', $submissao->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja REJEITAR esta missão?');">
                                @csrf
                                <button type="submit" class="rounded-lg bg-red-600 py-2 px-4 text-sm font-medium text-white hover:bg-red-700">Rejeitar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-10 px-4 text-center text-gray-500">
                        Nenhuma missão pendente de validação no momento.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    @if ($submissoes->hasPages())
    <div class="p-6 border-t border-gray-200">{{ $submissoes->links() }}</div>
    @endif
</div>
@endsection