<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Missao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MissaoController extends Controller
{
    /**
     * Exibe a central de missões para o cliente, separada por abas.
     */
    public function index()
    {
        $usuario = Auth::user();
        $dados['titulo_pagina'] = 'Central de Missões';

        $missoesAceitasIds = $usuario->missoes()->pluck('missoes.id');

        // CORREÇÃO: Usando o scope ativas() do Model Missao
        $dados['missoesDisponiveis'] = Missao::ativas()
            ->whereNotIn('id', $missoesAceitasIds)
            ->orderBy('created_at', 'desc')
            ->get();

        $dados['missoesEmProgresso'] = $usuario->missoes()
            ->wherePivot('status', 'em_progresso')
            ->orderBy('pivot_created_at', 'desc')
            ->get();

        $dados['missoesConcluidas'] = $usuario->missoes()
            ->wherePivotIn('status', ['concluida', 'pendente_validacao'])
            ->orderBy('pivot_updated_at', 'desc')
            ->get();
        
        return view('cliente.missoes.index', $dados);
    }

    /**
     * Lógica para o usuário aceitar uma nova missão.
     */
    public function aceitar(Request $request, Missao $missao)
    {
        $usuario = Auth::user();

        // CORREÇÃO: Verifica a validade da missão apenas pelas datas
        if ($missao->data_inicio > now() || $missao->data_fim < now()->startOfDay()) {
            return back()->with('error', 'Esta missão não está mais disponível.');
        }

        if ($usuario->missoes()->where('missao_id', $missao->id)->exists()) {
            return back()->with('error', 'Você já aceitou esta missão.');
        }

        $usuario->missoes()->attach($missao->id, ['status' => 'em_progresso']);

        return back()->with('success', 'Missão aceita! Boa sorte!');
    }

    /**
     * Mostra o formulário para o usuário enviar um comprovante.
     */
    public function showComprovar(Missao $missao)
    {
        return view('cliente.missoes.comprovar', ['missao' => $missao]);
    }

    /**
     * Salva o comprovante enviado pelo usuário.
     */
    public function storeComprovante(Request $request, Missao $missao)
    {
        $request->validate(['comprovacao' => 'required|image|max:2048']);

        $usuario = Auth::user();
        $path = $request->file('comprovacao')->store('comprovantes_missoes', 'public');

        $usuario->missoes()->updateExistingPivot($missao->id, [
            'status' => 'pendente_validacao',
            'comprovacao_url' => $path,
        ]);
        
        return redirect()->route('cliente.missoes.index')->with('success', 'Comprovante enviado com sucesso! Aguarde a validação.');
    }
}