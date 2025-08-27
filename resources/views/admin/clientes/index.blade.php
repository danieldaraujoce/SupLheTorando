@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Clientes</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Clientes</li>
        </ol>
    </nav>
</div>

{{-- Card Principal --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6 relative">

    {{-- Cabeçalho Interno do Card --}}
    <div
        class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between border-b border-gray-200 pb-6 mb-6">
        <div>
            <h4 class="text-xl font-semibold text-black">Lista de Clientes</h4>
            <p class="text-sm text-gray-500 mt-1">Gerencie todos os clientes cadastrados no sistema.</p>
        </div>
        <a href="{{ route('admin.clientes.create') }}"
            class="mt-3 sm:mt-0 flex items-center justify-center whitespace-nowrap rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
            <i class="fas fa-plus mr-2"></i> Adicionar Cliente
        </a>
    </div>

    {{-- Notificação Popup --}}
    <div x-data="{ show: false, message: '' }" x-init="() => {
             @if(session('success'))
                 message = '{{ session('success') }}';
                 show = true;
                 setTimeout(() => show = false, 3000);
             @endif
         }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="fixed top-20 right-10 bg-white border border-green-400 text-gray-800 px-6 py-4 rounded-lg shadow-lg z-50 flex items-center gap-4"
        role="alert">
        <div class="text-green-500">
            <i class="fas fa-check-circle fa-2x"></i>
        </div>
        <div>
            <strong class="font-bold text-lg">Sucesso!</strong>
            <span class="block text-sm" x-text="message"></span>
        </div>
    </div>

    {{-- Container da Tabela --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="py-4 px-6 font-medium text-black">Cliente</th>
                    <th class="py-4 px-6 font-medium text-black">Contato (WhatsApp)</th>
                    <th class="py-4 px-6 font-medium text-black">Status</th>
                    <th class="py-4 px-6 text-center font-medium text-black">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            @if($cliente->avatar)
                                <img src="{{ $cliente->avatar }}" alt="Avatar de {{ $cliente->nome }}" class="h-11 w-11 rounded-full object-cover flex-shrink-0 mr-4">
                            @else
                                <div
                                    class="h-11 w-11 rounded-full bg-primary/10 text-primary flex-shrink-0 mr-4 flex items-center justify-center font-bold text-lg">
                                    @php
                                    $nameParts = explode(' ', $cliente->nome);
                                    $firstName = $nameParts[0] ?? '';
                                    $lastName = count($nameParts) > 1 ? end($nameParts) : '';
                                    $initials = strtoupper(substr($firstName, 0, 1) . ($lastName ? substr($lastName, 0, 1) :
                                    ''));
                                    @endphp
                                    <span>{{ $initials }}</span>
                                </div>
                            @endif
                            <div>
                                <h5 class="font-semibold text-black">{{ $cliente->nome }}</h5>
                                <p class="text-sm text-gray-500">{{ $cliente->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <p class="text-black">
                            @if($cliente->whatsapp && strlen($cliente->whatsapp) == 11)
                            {{ '(' . substr($cliente->whatsapp, 0, 2) . ') ' . substr($cliente->whatsapp, 2, 5) . '-' . substr($cliente->whatsapp, 7) }}
                            @elseif($cliente->whatsapp && strlen($cliente->whatsapp) == 10)
                            {{ '(' . substr($cliente->whatsapp, 0, 2) . ') ' . substr($cliente->whatsapp, 2, 4) . '-' . substr($cliente->whatsapp, 6) }}
                            @else
                            {{ $cliente->whatsapp ?? 'Não informado' }}
                            @endif
                        </p>
                    </td>
                    <td class="py-4 px-6">
                        @if ($cliente->status == 'ativo')
                        <p class="inline-flex rounded-full bg-green-100 py-1 px-3 text-sm font-medium text-green-600">
                            Ativo</p>
                        @else
                        <p class="inline-flex rounded-full bg-yellow-100 py-1 px-3 text-sm font-medium text-yellow-600">
                            Aguardando Liberação</p>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="{{ route('admin.clientes.edit', $cliente->id) }}"
                                class="rounded-lg bg-primary py-2 px-4 text-sm font-medium text-white hover:bg-opacity-90">Editar</a>
                            <form action="{{ route('admin.clientes.destroy', $cliente->id) }}" method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
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
                    <td colspan="4" class="py-16 px-4 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-users-slash fa-3x mb-3 text-gray-300"></i>
                            <h3 class="text-lg font-medium">Nenhum cliente encontrado.</h3>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    @if ($clientes->hasPages())
    <div class="p-6 border-t border-gray-200">
        {{ $clientes->links() }}
    </div>
    @endif
</div>
@endsection