<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jogo;
use App\Models\JogoPremio;
use App\Models\Produto;
use App\Models\Cupom;
use Illuminate\Http\Request;

class JogoController extends Controller
{
    /**
     * Mostra a galeria de jogos disponíveis para o admin ativar/desativar.
     */
    public function index()
    {
        $dados['titulo_pagina'] = 'Galeria de Mini-Jogos';
        $dados['jogos'] = Jogo::with('premios')->get();
        return view('admin.jogos.index', $dados);
    }

    /**
     * Mostra o "estúdio" para gerenciar os prêmios de um jogo específico.
     */
    public function edit(Jogo $jogo)
    {
        $dados['titulo_pagina'] = 'Gerenciar Jogo: ' . $jogo->nome;
        $dados['jogo'] = $jogo->load('premios');
        $dados['produtos'] = Produto::orderBy('nome')->get();
        $dados['cupons'] = Cupom::orderBy('codigo')->get();
        return view('admin.jogos.edit', $dados);
    }
    
    /**
     * Atualiza o status de um jogo (ativo/inativo).
     */
    public function update(Request $request, Jogo $jogo)
    {
        $jogo->update($request->only('status'));
        return back()->with('success', 'Status do jogo atualizado!');
    }

    /**
     * Adiciona um novo prêmio a um jogo.
     */
    public function storePremio(Request $request, Jogo $jogo)
    {
        $request->validate([
            'descricao_premio' => 'required|string|max:255',
            'tipo_premio' => 'required|in:coins,cupom,produto',
            'premio_id' => 'nullable|integer',
            'valor_premio' => 'nullable|integer|min:1',
            'chance_percentual' => 'required|numeric|min:0.01|max:100',
        ]);

        $dados = $request->all();
        // Unifica os IDs de cupom/produto em uma única coluna
        if ($request->tipo_premio == 'cupom') {
            $dados['premio_id'] = $request->premio_id_cupom;
        }

        $jogo->premios()->create($dados);
        return back()->with('success', 'Prêmio adicionado ao jogo!');
    }

    /**
     * Remove um prêmio de um jogo.
     */
    public function destroyPremio(JogoPremio $premio)
    {
        $premio->delete();
        return back()->with('success', 'Prêmio removido do jogo.');
    }
}