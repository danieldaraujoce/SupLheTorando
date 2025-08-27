<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Missao;
use App\Models\Recompensa;
use App\Models\Quiz;
use App\Models\Transacao;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // --- SEU CÓDIGO PARA O GRÁFICO (MANTIDO) ---
        $cadastros = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(6))->groupBy('date')->orderBy('date', 'ASC')
            ->get()->pluck('count', 'date');

        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
            $dateKey = $date->format('Y-m-d');
            $data[] = $cadastros->get($dateKey, 0); 
        }

        // --- LÓGICA PARA CALCULAR VARIAÇÃO (MANTIDA) ---
        $calculateChange = function ($current, $previous) {
            if ($previous == 0) return $current > 0 ? 100 : 0;
            return (($current - $previous) / $previous) * 100;
        };

        // --- CÁLCULO DOS TOTAIS E VARIAÇÕES ---
        $total_clientes = User::where('nivel_acesso', 'cliente')->count();
        $clientesSemanaPassada = User::where('nivel_acesso', 'cliente')->where('created_at', '<', now()->subWeek())->count();
        
        // --- CORREÇÃO APLICADA AQUI ---
        // Substituímos a chamada ao scope 'ativas()' por uma verificação de datas explícita.
        $hoje = now();
        $missoes_ativas = Missao::where('data_inicio', '<=', $hoje)->where('data_fim', '>=', $hoje)->count();
        $missoesSemanaPassada = Missao::where('data_inicio', '<=', $hoje->copy()->subWeek())->where('data_fim', '>=', $hoje->copy()->subWeek())->count();
        
        $total_recompensas = Recompensa::count();
        $recompensasSemanaPassada = Recompensa::where('created_at', '<', now()->subWeek())->count();
        
        $total_quizzes = Quiz::count();
        $quizzesSemanaPassada = Quiz::where('created_at', '<', now()->subWeek())->count();

        // --- LÓGICA DO RANKING MENSAL (MANTIDA) ---
        $inicioDoMes = Carbon::now()->startOfMonth();
        $fimDoMes = Carbon::now()->endOfMonth();
        $ranking_mes = User::select('usuarios.id', 'usuarios.nome', DB::raw('SUM(transacoes.valor) as pontos_mes'))
            ->join('transacoes', 'usuarios.id', '=', 'transacoes.usuario_id')
            ->where('transacoes.tipo', 'credito')
            ->whereBetween('transacoes.created_at', [$inicioDoMes, $fimDoMes])
            ->where('usuarios.nivel_acesso', 'cliente')
            ->groupBy('usuarios.id', 'usuarios.nome')
            ->orderBy('pontos_mes', 'desc')->limit(5)->get();

        // --- LÓGICA PARA ATIVIDADES RECENTES (MANTIDA) ---
        $atividades_recentes = Transacao::with('usuario')->latest()->limit(5)->get();

        // --- ARRAY DE DADOS PARA A VIEW ---
        $dados = [
            'titulo_pagina' => 'Painel Administrativo',
            'total_clientes' => $total_clientes,
            'missoes_ativas' => $missoes_ativas,
            'total_recompensas' => $total_recompensas,
            'total_quizzes' => $total_quizzes,
            'clientes_change' => $calculateChange($total_clientes, $clientesSemanaPassada),
            'missoes_change' => $calculateChange($missoes_ativas, $missoesSemanaPassada),
            'recompensas_change' => $calculateChange($total_recompensas, $recompensasSemanaPassada),
            'quizzes_change' => $calculateChange($total_quizzes, $quizzesSemanaPassada),
            'ranking_mes' => $ranking_mes,
            'atividades_recentes' => $atividades_recentes,
            'labels' => $labels,
            'data' => $data,
        ];
        
        return view('admin.dashboard', $dados);
    }
}