<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;

class RankingController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Ranking do Mês';
        
        $inicioDoMes = Carbon::now()->startOfMonth();
        $fimDoMes = Carbon::now()->endOfMonth();

        $rankingCompleto = User::select('usuarios.id', 'usuarios.nome', DB::raw('SUM(transacoes.valor) as pontos_mes'))
            ->join('transacoes', 'usuarios.id', '=', 'transacoes.usuario_id')
            ->where('transacoes.tipo', 'credito')
            ->whereBetween('transacoes.created_at', [$inicioDoMes, $fimDoMes])
            ->where('usuarios.nivel_acesso', 'cliente')
            ->groupBy('usuarios.id', 'usuarios.nome')
            ->orderBy('pontos_mes', 'desc')
            ->paginate(20); // Paginação para o ranking completo

        $dados['ranking'] = $rankingCompleto;

        return view('cliente.ranking.index', $dados);
    }
}