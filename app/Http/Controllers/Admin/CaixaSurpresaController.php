<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaixaSurpresa;
use App\Models\Cupom;
use App\Models\Produto;
use Illuminate\Http\Request;

class CaixaSurpresaController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Caixas Surpresa';
        $dados['caixas'] = CaixaSurpresa::with('itens')->paginate(10);
        return view('admin.caixas-surpresa.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Nova Caixa Surpresa';
        return view('admin.caixas-surpresa.create', $dados);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);
        $caixa = CaixaSurpresa::create($request->all());
        
        return redirect()->route('admin.caixas-surpresa.edit', $caixa)->with('success', 'Caixa criada! Agora adicione os prêmios.');
    }

    public function edit(CaixaSurpresa $caixas_surpresa)
    {
        $dados['titulo_pagina'] = 'Gerenciar Prêmios da Caixa';
        $dados['caixa'] = $caixas_surpresa->load('itens');
        $dados['produtos'] = Produto::orderBy('nome')->get();
        $dados['cupons'] = Cupom::orderBy('codigo')->get();
        
        return view('admin.caixas-surpresa.edit', $dados);
    }

    public function update(Request $request, CaixaSurpresa $caixas_surpresa)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);
        $caixas_surpresa->update($request->all());
        return redirect()->route('admin.caixas-surpresa.edit', $caixas_surpresa)->with('success', 'Dados da caixa atualizados!');
    }

    public function destroy(CaixaSurpresa $caixas_surpresa)
    {
        $caixas_surpresa->delete();
        return redirect()->route('admin.caixas-surpresa.index')->with('success', 'Caixa Surpresa excluída com sucesso!');
    }
}