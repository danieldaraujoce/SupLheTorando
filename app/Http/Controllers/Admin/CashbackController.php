<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cashback;
use Illuminate\Http\Request;

class CashbackController extends Controller
{
    /**
     * Exibe a lista de todas as campanhas de cashback.
     */
    public function index()
    {
        $dados['titulo_pagina'] = 'Campanhas de Cashback';
        $dados['cashbacks'] = Cashback::orderBy('data_fim', 'desc')->paginate(10);
        return view('admin.cashbacks.index', $dados);
    }

    /**
     * Mostra o formulário para criar uma nova campanha.
     */
    public function create()
    {
        $dados['titulo_pagina'] = 'Nova Campanha de Cashback';
        return view('admin.cashbacks.create', $dados);
    }

    /**
     * Salva uma nova campanha no banco de dados.
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $dadosValidados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:porcentagem,fixo',
            'valor' => 'required|numeric|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'valor_minimo_compra' => 'nullable|numeric|min:0',
        ]);

        // --- CORREÇÃO APLICADA AQUI ---
        // Cria a campanha apenas com os dados validados. A linha 'status' => 'ativo' foi removida.
        Cashback::create($dadosValidados);

        // Redireciona para a lista de campanhas com uma mensagem de sucesso
        return redirect()->route('admin.cashbacks.index')
                         ->with('success', 'Campanha de cashback criada com sucesso!');
    }

    /**
     * Mostra o formulário para editar uma campanha existente.
     */
    public function edit(Cashback $cashback)
    {
        $dados['titulo_pagina'] = 'Editar Campanha de Cashback';
        $dados['cashback'] = $cashback;

        return view('admin.cashbacks.edit', $dados);
    }

    /**
     * Atualiza uma campanha existente no banco de dados.
     */
    public function update(Request $request, Cashback $cashback)
    {
        // Validação dos dados do formulário
        $dadosValidados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo' => 'required|in:porcentagem,fixo',
            'valor' => 'required|numeric|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'valor_minimo_compra' => 'nullable|numeric|min:0',
        ]);

        // --- MELHORIA APLICADA AQUI ---
        // Atualiza a campanha apenas com os dados validados para maior segurança.
        $cashback->update($dadosValidados);

        // Redireciona para a lista de campanhas com uma mensagem de sucesso
        return redirect()->route('admin.cashbacks.index')
                         ->with('success', 'Campanha de cashback atualizada com sucesso!');
    }

    /**
     * Remove uma campanha do banco de dados.
     */
    public function destroy(Cashback $cashback)
    {
        $cashback->delete();

        // Redireciona para a lista de campanhas com uma mensagem de sucesso
        return redirect()->route('admin.cashbacks.index')
                         ->with('success', 'Campanha de cashback excluída com sucesso!');
    }
}