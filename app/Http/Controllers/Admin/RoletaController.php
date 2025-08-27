<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Roleta;
use App\Models\Produto;
use App\Models\Cupom;
use Illuminate\Http\Request;

class RoletaController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Gerenciador de Roletas';
        $dados['roletas'] = Roleta::with('itens')->paginate(10);
        return view('admin.roletas.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Nova Roleta';
        return view('admin.roletas.create', $dados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);
        $roleta = Roleta::create($request->all());
        return redirect()->route('admin.roletas.edit', $roleta)->with('success', 'Roleta criada! Agora adicione os prêmios de cada fatia.');
    }
    
    public function edit(Roleta $roleta)
    {
        $dados['titulo_pagina'] = 'Gerenciar Roleta';
        $dados['roleta'] = $roleta->load('itens');
        $dados['produtos'] = Produto::orderBy('nome')->get();
        $dados['cupons'] = Cupom::orderBy('codigo')->get();
        return view('admin.roletas.edit', $dados);
    }

    public function update(Request $request, Roleta $roleta)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);
        $roleta->update($request->all());
        return redirect()->route('admin.roletas.edit', $roleta)->with('success', 'Dados da roleta atualizados!');
    }
    
    public function destroy(Roleta $roleta)
    {
        $roleta->delete();
        return redirect()->route('admin.roletas.index')->with('success', 'Roleta excluída com sucesso!');
    }
}