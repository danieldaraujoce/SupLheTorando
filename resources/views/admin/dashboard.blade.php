@extends('admin.layouts.app')
@section('content')

{{-- Container dos Cards de Resumo (SEM ALTERAÇÕES) --}}
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl border border-gray-200 bg-white p-6 flex flex-col justify-between">
        {{-- Card Total de Clientes --}}
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"><i class="fas fa-users text-xl"></i></div>
            <div>
                <h3 class="text-base font-semibold text-black">Total de Clientes</h3>
                <span class="text-xs text-gray-500">Contagem geral</span>
            </div>
        </div>
        <div class="mt-4 flex justify-between items-end">
            <h4 class="text-2xl font-bold text-black">{{ $total_clientes ?? 0 }}</h4>
            @if($clientes_change >= 0)
            <span class="flex items-center gap-1 rounded-full bg-green-100 py-0.5 px-2.5 text-sm font-medium text-green-600"><i class="fas fa-arrow-up"></i> {{ number_format($clientes_change, 1) }}%</span>
            @else
            <span class="flex items-center gap-1 rounded-full bg-red-100 py-0.5 px-2.5 text-sm font-medium text-red-600"><i class="fas fa-arrow-down"></i> {{ number_format($clientes_change, 1) }}%</span>
            @endif
        </div>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 flex flex-col justify-between">
        {{-- Card Missões Ativas --}}
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"><i class="fas fa-bullseye text-xl"></i></div>
            <div>
                <h3 class="text-base font-semibold text-black">Missões Ativas</h3>
                <span class="text-xs text-gray-500">Disponíveis agora</span>
            </div>
        </div>
        <div class="mt-4 flex justify-between items-end">
            <h4 class="text-2xl font-bold text-black">{{ $missoes_ativas ?? 0 }}</h4>
            @if($missoes_change >= 0)
            <span class="flex items-center gap-1 rounded-full bg-green-100 py-0.5 px-2.5 text-sm font-medium text-green-600"><i class="fas fa-arrow-up"></i> {{ number_format($missoes_change, 1) }}%</span>
            @else
            <span class="flex items-center gap-1 rounded-full bg-red-100 py-0.5 px-2.5 text-sm font-medium text-red-600"><i class="fas fa-arrow-down"></i> {{ number_format($missoes_change, 1) }}%</span>
            @endif
        </div>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 flex flex-col justify-between">
        {{-- Card Recompensas na Loja --}}
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"><i class="fas fa-gift text-xl"></i></div>
            <div>
                <h3 class="text-base font-semibold text-black">Recompensas</h3>
                <span class="text-xs text-gray-500">Itens na loja</span>
            </div>
        </div>
        <div class="mt-4 flex justify-between items-end">
            <h4 class="text-2xl font-bold text-black">{{ $total_recompensas ?? 0 }}</h4>
            @if($recompensas_change >= 0)
            <span class="flex items-center gap-1 rounded-full bg-green-100 py-0.5 px-2.5 text-sm font-medium text-green-600"><i class="fas fa-arrow-up"></i> {{ number_format($recompensas_change, 1) }}%</span>
            @else
            <span class="flex items-center gap-1 rounded-full bg-red-100 py-0.5 px-2.5 text-sm font-medium text-red-600"><i class="fas fa-arrow-down"></i> {{ number_format($recompensas_change, 1) }}%</span>
            @endif
        </div>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 flex flex-col justify-between">
        {{-- Card Total de Quizzes --}}
        <div class="flex items-center gap-3">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 text-primary"><i class="fas fa-question-circle text-xl"></i></div>
            <div>
                <h3 class="text-base font-semibold text-black">Total de Quizzes</h3>
                <span class="text-xs text-gray-500">Contagem geral</span>
            </div>
        </div>
        <div class="mt-4 flex justify-between items-end">
            <h4 class="text-2xl font-bold text-black">{{ $total_quizzes ?? 0 }}</h4>
            @if($quizzes_change >= 0)
            <span class="flex items-center gap-1 rounded-full bg-green-100 py-0.5 px-2.5 text-sm font-medium text-green-600"><i class="fas fa-arrow-up"></i> {{ number_format($quizzes_change, 1) }}%</span>
            @else
            <span class="flex items-center gap-1 rounded-full bg-red-100 py-0.5 px-2.5 text-sm font-medium text-red-600"><i class="fas fa-arrow-down"></i> {{ number_format($quizzes_change, 1) }}%</span>
            @endif
        </div>
    </div>
</div>

{{-- =============================================== --}}
{{-- =========== LAYOUT REORGANIZADO AQUI ============ --}}
{{-- =============================================== --}}
<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-12">

    {{-- Coluna 1: Gráfico de Novos Cadastros --}}
    <div class="lg:col-span-4 rounded-2xl border border-gray-200 bg-white p-6">
        <h4 class="text-xl font-semibold text-black">Novos Cadastros (Últimos 7 dias)</h4>
        <div id="chartOne" class="-ml-5 mt-4"></div>
    </div>

    {{-- Coluna 2: Ranking do Mês --}}
    <div class="lg:col-span-4 rounded-2xl border border-gray-200 bg-white p-6">
        <h4 class="text-xl font-semibold text-black mb-6">🏆 Ranking do Mês</h4>
        <div class="flex flex-col">
            @forelse ($ranking_mes as $cliente)
            <div class="flex justify-between items-center border-b border-gray-200 py-4 last:border-b-0">
                <div class="flex items-center gap-4">
                    <span class="text-xl w-6">
                        @if ($loop->iteration == 1) 🥇
                        @elseif ($loop->iteration == 2) 🥈
                        @elseif ($loop->iteration == 3) 🥉
                        @endif
                    </span>
                    <div>
                        <p class="text-sm font-medium text-black">{{ $cliente->nome }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm font-medium text-primary text-right">{{ number_format($cliente->pontos_mes, 0, ',', '.') }} pts</p>
                </div>
            </div>
            @empty
            <div class="py-4 text-center"><p class="text-sm text-gray-500">Nenhum cliente pontuou este mês.</p></div>
            @endforelse
        </div>
    </div>

    {{-- Coluna 3: Atividades Recentes (NOVO CARD) --}}
    <div class="lg:col-span-4 rounded-2xl border border-gray-200 bg-white p-6">
        <h4 class="text-xl font-semibold text-black mb-6">⚡ Atividades Recentes</h4>
        <div class="flex flex-col">
            @forelse ($atividades_recentes as $transacao)
            <div class="flex justify-between items-start border-b border-gray-200 py-4 last:border-b-0">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-full flex-shrink-0 {{ $transacao->tipo == 'credito' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center">
                        <i class="fas {{ $transacao->tipo == 'credito' ? 'fa-plus' : 'fa-minus' }}"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-black">{{ $transacao->usuario->nome ?? 'Usuário' }}</p>
                        <p class="text-xs text-gray-500">{{ $transacao->descricao }}</p>
                    </div>
                </div>
                <div class="text-right flex-shrink-0 ml-4">
                    <p class="text-sm font-bold {{ $transacao->tipo == 'credito' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transacao->tipo == 'credito' ? '+' : '-' }}{{ $transacao->valor }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $transacao->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <div class="py-4 text-center"><p class="text-sm text-gray-500">Nenhuma atividade recente.</p></div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Script do gráfico (sem alterações) --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const chartData = @json($data ?? []);
    const chartLabels = @json($labels ?? []);
    const options = { series: [{ name: 'Novos Cadastros', data: chartData }], chart: { type: 'area', height: 350, toolbar: { show: false }}, colors: ['#3C50E0'], stroke: { width: 2, curve: 'smooth' }, dataLabels: { enabled: false }, yaxis: { labels: { style: { colors: '#64748b' } } }, xaxis: { labels: { style: { colors: '#64748b' } }, categories: chartLabels }, grid: { borderColor: '#f1f5f9' } };
    const chart = new ApexCharts(document.querySelector("#chartOne"), options);
    chart.render();
});
</script>
@endpush