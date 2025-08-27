<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Roleta;
use App\Models\RoletaItem;
use Illuminate\Http\Request;

class RoletaItemController extends Controller
{
    public function store(Request $request, Roleta $roleta)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'tipo_premio' => 'required|in:coins,cupom,produto,giro_extra,outro,nada',
            'chance_percentual' => 'required|numeric|min:0|max:100',
            'valor_premio' => 'nullable|required_if:tipo_premio,coins,giro_extra|integer|min:1',
            'premio_id' => 'nullable|required_if:tipo_premio,produto|exists:produtos,id',
            'premio_id_cupom' => 'nullable|required_if:tipo_premio,cupom|exists:cupons,id',
            'nome_customizado' => 'nullable|required_if:tipo_premio,outro|string|max:255',
            'cor_slice' => 'required|string|max:7',
        ]);

        $dados = $request->only('descricao', 'tipo_premio', 'chance_percentual', 'cor_slice');

        // Lógica para salvar o prêmio correto
        switch ($request->tipo_premio) {
            case 'coins':
            case 'giro_extra':
                $dados['valor_premio'] = $request->valor_premio;
                break;
            case 'produto':
                $dados['premio_id'] = $request->premio_id;
                break;
            case 'cupom':
                $dados['premio_id'] = $request->premio_id_cupom;
                break;
            case 'outro':
                $dados['nome_customizado'] = $request->nome_customizado;
                break;
        }

        $roleta->itens()->create($dados);

        return redirect()->route('admin.roletas.edit', $roleta)->with('success', 'Fatia adicionada à roleta!');
    }
    
    public function update(Request $request, RoletaItem $item)
    {
        $request->validate([
            'descricao' => 'required|string|max:255',
            'tipo_premio' => 'required|in:coins,cupom,produto,giro_extra,outro,nada',
            'chance_percentual' => 'required|numeric|min:0|max:100',
            'valor_premio' => 'nullable|required_if:tipo_premio,coins,giro_extra|integer|min:1',
            'premio_id' => 'nullable|required_if:tipo_premio,produto|exists:produtos,id',
            'premio_id_cupom' => 'nullable|required_if:tipo_premio,cupom|exists:cupons,id',
            'nome_customizado' => 'nullable|required_if:tipo_premio,outro|string|max:255',
            'cor_slice' => 'required|string|max:7',
        ]);

        $dados = $request->only('descricao', 'tipo_premio', 'chance_percentual', 'cor_slice');
        
        // Limpa dados antigos para evitar inconsistência
        $dados['valor_premio'] = null;
        $dados['premio_id'] = null;
        $dados['nome_customizado'] = null;
        
        // Lógica para atualizar com o prêmio correto
        switch ($request->tipo_premio) {
            case 'coins':
            case 'giro_extra':
                $dados['valor_premio'] = $request->valor_premio;
                break;
            case 'produto':
                $dados['premio_id'] = $request->premio_id;
                break;
            case 'cupom':
                $dados['premio_id'] = $request->premio_id_cupom;
                break;
            case 'outro':
                $dados['nome_customizado'] = $request->nome_customizado;
                break;
        }

        $item->update($dados);

        return redirect()->route('admin.roletas.edit', $item->roleta_id)->with('success', 'Fatia atualizada com sucesso!');
    }

    public function destroy(RoletaItem $item)
    {
        $roletaId = $item->roleta_id;
        $item->delete();
        return redirect()->route('admin.roletas.edit', $roletaId)->with('success', 'Fatia removida da roleta.');
    }
}