<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Missao;
use App\Models\Nivel;
use App\Models\Cupom;
use App\Models\Recompensa;
use App\Models\Promocao;
use App\Models\Encarte;
use App\Models\GuiaDica;
use App\Models\OfertaMes;
use App\Models\OfertaVip;
use App\Models\Produto;
use App\Models\Cashback;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Carbon\Carbon; 

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $dados['titulo_pagina'] = 'Meu Painel';
        $dados['usuario'] = $usuario;

        // --- LÓGICA DE RECOMENDAÇÃO DE CUPONS ---
        $cuponsJaResgatadosIds = $usuario->cuponsResgatados()->pluck('cupons.id');
        
        $dados['nivel_usuario'] = $usuario->nivelAtual();
        $progresso = $usuario->progressoNivel();
        $dados['progresso_percentual'] = $progresso['percentual'];
        $dados['proximo_nivel_nome'] = $progresso['proximo_nivel_nome'];
        
        $missoesAceitasIds = $usuario->missoes()->pluck('missao_id');
        $dados['missoes_ativas'] = Missao::ativas()
                                         ->whereNotIn('id', $missoesAceitasIds)
                                         ->get();
        
        $dados['cupons'] = Cupom::ativos()->orderBy('data_validade')->get();

        $dados['cuponsRecomendados'] = Cupom::withCount('usuariosQueResgataram')
        ->whereNotIn('id', $cuponsJaResgatadosIds)
        ->orderBy('usuarios_que_resgataram_count', 'desc')
        ->limit(5)
        ->get();

        $dados['recompensas'] = Recompensa::where('status', 'ativo')
                                         ->orderBy('custo_coins')
                                         ->get();
        
        $dados['promocoes'] = Promocao::query()->ativas()->with('produtos.categoria')->orderBy('data_inicio', 'desc')->get();
        
        $dados['encartes'] = Encarte::ativas()->orderBy('data_inicio', 'desc')->get();

        $dados['guias_dicas'] = GuiaDica::ativas()->orderBy('created_at', 'desc')->get();
                                        
        $dados['ofertas_mes'] = OfertaMes::ativas()->orderBy('created_at', 'desc')->get();
                                         
        $dados['ofertas_vip'] = OfertaVip::ativas()->orderBy('created_at', 'desc')->get();

        $dados['cashbacks_ativos'] = Cashback::ativas()->orderBy('data_fim', 'asc')->get();
        
        $dados['cashbacks_resgatados_ids'] = $usuario->cashbacks()->pluck('cashbacks.id')->toArray();

        // =============================================== //
        // =========== LÓGICA DO RANKING ADICIONADA ========== //
        // =============================================== //
        $inicioDoMes = Carbon::now()->startOfMonth();
        $fimDoMes = Carbon::now()->endOfMonth();

        $rankingMensal = User::select('usuarios.id', 'usuarios.nome', DB::raw('SUM(transacoes.valor) as pontos_mes'))
            ->join('transacoes', 'usuarios.id', '=', 'transacoes.usuario_id')
            ->where('transacoes.tipo', 'credito')
            ->whereBetween('transacoes.created_at', [$inicioDoMes, $fimDoMes])
            ->where('usuarios.nivel_acesso', 'cliente')
            ->groupBy('usuarios.id', 'usuarios.nome')
            ->orderBy('pontos_mes', 'desc')
            ->limit(3) // Pega apenas o Top 3 para o dashboard
            ->get();

        $dados['rankingMensal'] = $rankingMensal;

        return view('cliente.dashboard', $dados);
    }
}