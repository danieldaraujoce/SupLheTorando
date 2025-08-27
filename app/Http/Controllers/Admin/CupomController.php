<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cupom;
use Illuminate\Http\Request;

class CupomController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Gerenciador de Cupons';
        $dados['cupons'] = Cupom::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.cupons.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Novo Cupom';
        return view('admin.cupons.create', $dados);
    }

    public function store(Request $request)
    {
        // A validação já estava correta
        $dadosValidados = $request->validate([
            'codigo' => 'required|string|unique:cupons|max:255',
            'descricao' => 'required|string',
            'tipo_desconto' => 'required|in:porcentagem,fixo',
            'valor' => 'required|numeric|min:0',
            'valor_minimo_compra' => 'nullable|numeric|min:0',
            'data_validade' => 'required|date',
            'limite_uso_total' => 'nullable|integer|min:1',
            'limite_uso_por_cliente' => 'nullable|integer|min:1',
            'tipo_cupom' => 'required|in:normal,relampago',
            'horas_validade' => 'nullable|required_if:tipo_cupom,relampago|integer|min:1',
            'coins_extra_fidelidade' => 'nullable|integer|min:0',
        ]);

        // --- CORREÇÃO DE SEGURANÇA APLICADA AQUI ---
        // Usamos os dados validados em vez de $request->all()
        Cupom::create($dadosValidados);

        return redirect()->route('admin.cupons.index')->with('success', 'Cupom criado com sucesso!');
    }

    public function edit(Cupom $cupom)
    {
        $dados['titulo_pagina'] = 'Editar Cupom';
        $dados['cupom'] = $cupom;
        return view('admin.cupons.edit', $dados);
    }

    public function update(Request $request, Cupom $cupom)
    {
        // A validação já estava correta
        $dadosValidados = $request->validate([
            'codigo' => 'required|string|max:255|unique:cupons,codigo,' . $cupom->id,
            'descricao' => 'required|string',
            'tipo_desconto' => 'required|in:porcentagem,fixo',
            'valor' => 'required|numeric|min:0',
            'valor_minimo_compra' => 'nullable|numeric|min:0',
            'data_validade' => 'required|date',
            'limite_uso_total' => 'nullable|integer|min:1',
            'limite_uso_por_cliente' => 'nullable|integer|min:1',
            'tipo_cupom' => 'required|in:normal,relampago',
            'horas_validade' => 'nullable|required_if:tipo_cupom,relampago|integer|min:1',
            'coins_extra_fidelidade' => 'nullable|integer|min:0',
        ]);

        // --- CORREÇÃO DE SEGURANÇA APLICADA AQUI ---
        // Usamos os dados validados em vez de $request->all()
        $cupom->update($dadosValidados);

        return redirect()->route('admin.cupons.index')->with('success', 'Cupom atualizado com sucesso!');
    }

    public function destroy(Cupom $cupom)
    {
        $cupom->delete();
        return redirect()->route('admin.cupons.index')->with('success', 'Cupom excluído com sucesso!');
    }
}