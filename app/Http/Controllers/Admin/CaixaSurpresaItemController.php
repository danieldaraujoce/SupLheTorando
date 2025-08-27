<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CaixaSurpresa;
use App\Models\CaixaSurpresaItem;
use Illuminate\Http\Request;

class CaixaSurpresaItemController extends Controller
{
    public function store(Request $request, CaixaSurpresa $caixaSurpresa)
    {
        $request->validate([
            'tipo_premio' => 'required|in:coins,cupom,produto,outro',
            'chance_percentual' => 'required|numeric|min:0|max:100',
            'valor_premio' => 'nullable|required_if:tipo_premio,coins|integer|min:1',
            'premio_id_produto' => 'nullable|required_if:tipo_premio,produto|exists:produtos,id',
            'premio_id_cupom' => 'nullable|required_if:tipo_premio,cupom|exists:cupons,id',
            'nome_customizado' => 'nullable|required_if:tipo_premio,outro|string|max:255',
        ]);

        $dados = $request->only('tipo_premio', 'chance_percentual');

        // Lógica para salvar o prêmio correto
        switch ($request->tipo_premio) {
            case 'coins':
                $dados['valor_premio'] = $request->valor_premio;
                break;
            case 'produto':
                $dados['premio_id'] = $request->premio_id_produto;
                break;
            case 'cupom':
                $dados['premio_id'] = $request->premio_id_cupom;
                break;
            case 'outro':
                $dados['nome_customizado'] = $request->nome_customizado;
                break;
        }

        $caixaSurpresa->itens()->create($dados);

        return redirect()->route('admin.caixas-surpresa.edit', $caixaSurpresa)->with('success', 'Item adicionado à caixa!');
    }
    
    public function update(Request $request, CaixaSurpresaItem $item)
    {
        $request->validate([
            'tipo_premio' => 'required|in:coins,cupom,produto,outro',
            'chance_percentual' => 'required|numeric|min:0|max:100',
            'valor_premio' => 'nullable|required_if:tipo_premio,coins|integer|min:1',
            'premio_id_produto' => 'nullable|required_if:tipo_premio,produto|exists:produtos,id',
            'premio_id_cupom' => 'nullable|required_if:tipo_premio,cupom|exists:cupons,id',
            'nome_customizado' => 'nullable|required_if:tipo_premio,outro|string|max:255',
        ]);

        $dados = $request->only('tipo_premio', 'chance_percentual');
        
        // Limpa dados antigos para evitar inconsistência
        $dados['valor_premio'] = null;
        $dados['premio_id'] = null;
        $dados['nome_customizado'] = null;
        
        // Lógica para atualizar com o prêmio correto
        switch ($request->tipo_premio) {
            case 'coins':
                $dados['valor_premio'] = $request->valor_premio;
                break;
            case 'produto':
                $dados['premio_id'] = $request->premio_id_produto;
                break;
            case 'cupom':
                $dados['premio_id'] = $request->premio_id_cupom;
                break;
            case 'outro':
                $dados['nome_customizado'] = $request->nome_customizado;
                break;
        }

        $item->update($dados);

        return redirect()->route('admin.caixas-surpresa.edit', $item->caixa_surpresa_id)->with('success', 'Item atualizado com sucesso!');
    }

    public function destroy(CaixaSurpresaItem $item)
    {
        $caixaId = $item->caixa_surpresa_id;
        $item->delete();
        return redirect()->route('admin.caixas-surpresa.edit', $caixaId)->with('success', 'Item removido da caixa.');
    }
}