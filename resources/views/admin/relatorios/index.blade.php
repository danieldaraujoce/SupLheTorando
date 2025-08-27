@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Relatórios do Sistema</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Relatórios</li>
        </ol>
    </nav>
</div>

{{-- Card Principal --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <div class="border-b border-gray-200 pb-6 mb-6">
        <h4 class="text-xl font-semibold text-black">Exportar Dados</h4>
        <p class="text-sm text-gray-500 mt-1">Selecione um dos relatórios abaixo para baixar os dados em formato PDF ou CSV.</p>
    </div>

    {{-- Seção de Relatórios de Usuários --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between py-4 border-b border-gray-100">
        <div>
            <h5 class="font-semibold text-black">Relatório de Clientes</h5>
            <p class="text-sm text-gray-500 mt-1">Lista todos os clientes cadastrados, suas moedas e nível de fidelidade.</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center space-x-4">
            <a href="{{ route('admin.relatorios.usuarios', ['formato' => 'pdf']) }}" target="_blank" class="flex items-center justify-center whitespace-nowrap rounded-lg bg-red-600 px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                <i class="fas fa-file-pdf mr-2"></i> Gerar PDF
            </a>
            <a href="{{ route('admin.relatorios.usuarios', ['formato' => 'csv']) }}" class="flex items-center justify-center whitespace-nowrap rounded-lg bg-green-600 px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                <i class="fas fa-file-csv mr-2"></i> Gerar CSV
            </a>
        </div>
    </div>

    {{-- Seção de Relatórios de Recompensas (Exemplo) --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between py-4">
        <div>
            <h5 class="font-semibold text-black">Relatório de Recompensas Resgatadas</h5>
            <p class="text-sm text-gray-500 mt-1">Histórico completo de todas as recompensas resgatadas pelos clientes.</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center space-x-4">
            {{-- As rotas abaixo são exemplos e precisam ser criadas no web.php e no controller --}}
            <a href="#" class="flex items-center justify-center whitespace-nowrap rounded-lg bg-gray-400 px-5 py-2.5 font-medium text-white cursor-not-allowed">
                <i class="fas fa-file-pdf mr-2"></i> Gerar PDF
            </a>
            <a href="#" class="flex items-center justify-center whitespace-nowrap rounded-lg bg-gray-400 px-5 py-2.5 font-medium text-white cursor-not-allowed">
                <i class="fas fa-file-csv mr-2"></i> Gerar CSV
            </a>
        </div>
    </div>

</div>
@endsection