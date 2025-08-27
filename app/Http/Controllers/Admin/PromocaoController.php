<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promocao;
use App\Models\Produto;
use App\Models\CaixaSurpresa;
use Illuminate\Http\Request;

class PromocaoController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Promoções de Combos';
        $dados['promocoes'] = Promocao::with('produtos')->orderBy('data_fim', 'desc')->paginate(10);
        return view('admin.promocoes.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Criar Novo Combo';
        $dados['produtos'] = Produto::orderBy('nome')->get();
        // Correção: Usa o scope ativas()
        $dados['caixas'] = CaixaSurpresa::ativas()->get();
        return view('admin.promocoes.create', $dados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'tipo_desconto' => 'required|in:porcentagem,fixo',
            'valor_desconto' => 'required|numeric|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'produtos' => 'required|array|min:1',
            'produtos.*' => 'exists:produtos,id',
            'caixa_surpresa_id' => 'nullable|exists:caixas_surpresa,id',
        ]);

        $promocao = Promocao::create($request->only('titulo', 'descricao', 'tipo_desconto', 'valor_desconto', 'data_inicio', 'data_fim', 'caixa_surpresa_id'));

        $promocao->produtos()->sync($request->produtos);

        return redirect()->route('admin.promocoes.index')->with('success', 'Promoção de Combo criada com sucesso!');
    }

    public function edit(Promocao $promocao)
    {   
        $dados['titulo_pagina'] = 'Editar Combo';
        $dados['promocao'] = $promocao->load('produtos');
        $dados['produtos'] = Produto::orderBy('nome')->get();
        // Correção: Usa o scope ativas()
        $dados['caixas'] = CaixaSurpresa::ativas()->get();
        return view('admin.promocoes.edit', $dados);
    }

    public function update(Request $request, Promocao $promocao)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo_desconto' => 'required|in:porcentagem,fixo',
            'valor_desconto' => 'required|numeric|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'produtos' => 'required|array|min:1',
            'produtos.*' => 'exists:produtos,id',
            'caixa_surpresa_id' => 'nullable|exists:caixas_surpresa,id',
        ]);
        
        $promocao->update($request->only('titulo', 'descricao', 'tipo_desconto', 'valor_desconto', 'data_inicio', 'data_fim', 'caixa_surpresa_id'));
        
        $promocao->produtos()->sync($request->produtos);

        return redirect()->route('admin.promocoes.index')->with('success', 'Promoção de Combo atualizada com sucesso!');
    }

    public function destroy(Promocao $promocao)
    {
        $promocao->produtos()->detach();
        $promocao->delete();

        return redirect()->route('admin.promocoes.index')->with('success', 'Promoção de Combo excluída com sucesso!');
    }
}