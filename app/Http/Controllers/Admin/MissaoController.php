<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Missao;
use App\Models\Produto;
use App\Models\CategoriaProduto;
use Illuminate\Http\Request;
use App\Models\UsuarioMissao; 
use Illuminate\Support\Facades\DB;

class MissaoController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Gerenciador de Missões';
        $dados['missoes'] = Missao::orderBy('data_fim', 'desc')->paginate(10);
        return view('admin.missoes.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Criar Nova Missão';
        $dados['produtos'] = Produto::orderBy('nome')->get(['id', 'nome']);
        $dados['categorias'] = CategoriaProduto::orderBy('nome')->get(['id', 'nome']);
        return view('admin.missoes.create', $dados);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo_missao' => 'required|in:compra,engajamento,social',
            'coins_recompensa' => 'required|integer|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            // 'status' removido da validação
        ]);

        $dadosMissao = $validated;

        if ($request->tipo_missao === 'compra') {
            $dadosMissao['meta_item_tipo'] = $request->meta_item_tipo_compra;
            $dadosMissao['meta_item_id'] = $request->meta_item_id_compra;
            $dadosMissao['meta_quantidade'] = $request->meta_quantidade_compra;
        } elseif ($request->tipo_missao === 'engajamento') {
            $dadosMissao['meta_item_tipo'] = $request->meta_item_tipo_engajamento;
            $dadosMissao['meta_quantidade'] = 1;
        }

        Missao::create($dadosMissao);

        return redirect()->route('admin.missoes.index')->with('success', 'Missão criada com sucesso!');
    }

    public function edit(Missao $missao)
    {
        $dados['titulo_pagina'] = 'Editar Missão';
        $dados['missao'] = $missao;
        $dados['produtos'] = Produto::orderBy('nome')->get(['id', 'nome']);
        $dados['categorias'] = CategoriaProduto::orderBy('nome')->get(['id', 'nome']);
        return view('admin.missoes.edit', $dados);
    }

    public function update(Request $request, Missao $missao)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'tipo_missao' => 'required|in:compra,engajamento,social',
            'coins_recompensa' => 'required|integer|min:0',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            // 'status' removido da validação
        ]);

        $dadosMissao = $validated;
        
        $dadosMissao['meta_item_tipo'] = null;
        $dadosMissao['meta_item_id'] = null;
        $dadosMissao['meta_quantidade'] = 1;

        if ($request->tipo_missao === 'compra') {
            $dadosMissao['meta_item_tipo'] = $request->meta_item_tipo_compra;
            $dadosMissao['meta_item_id'] = $request->meta_item_id_compra;
            $dadosMissao['meta_quantidade'] = $request->meta_quantidade_compra;
        } elseif ($request->tipo_missao === 'engajamento') {
            $dadosMissao['meta_item_tipo'] = $request->meta_item_tipo_engajamento;
        }

        $missao->update($dadosMissao);

        return redirect()->route('admin.missoes.index')->with('success', 'Missão atualizada com sucesso!');
    }

    // ... (demais métodos como destroy, indexPendentes, etc., permanecem inalterados)
    
    public function destroy(Missao $missao)
    {
        $missao->delete();
        return redirect()->route('admin.missoes.index')->with('success', 'Missão excluída com sucesso!');
    }

    public function indexPendentes()
    {
        $dados['titulo_pagina'] = 'Missões Pendentes de Validação';
        $dados['submissoes'] = UsuarioMissao::where('status', 'pendente_validacao')
            ->with('usuario', 'missao')
            ->paginate(15);
            
        return view('admin.missoes.pendentes', $dados);
    }

    public function aprovar(UsuarioMissao $submissao)
    {
        DB::transaction(function () use ($submissao) {
            $submissao->status = 'concluida';
            $submissao->data_conclusao = now();
            $submissao->save();

            $submissao->usuario->increment('coins', $submissao->missao->coins_recompensa);
            // Corrigindo variável que não existia. Assumindo que a recompensa é o valor em coins.
            $submissao->usuario->increment('total_coins_acumulados', $submissao->missao->coins_recompensa);
        });

        return back()->with('success', 'Missão aprovada com sucesso!');
    }

    public function rejeitar(UsuarioMissao $submissao)
    {
        $submissao->status = 'em_progresso';
        $submissao->comprovacao_url = null;
        $submissao->save();

        return back()->with('success', 'Missão rejeitada. O usuário foi notificado para tentar novamente.');
    }
    
    public function show(Missao $missao)
    {
        return redirect()->route('admin.missoes.edit', $missao->id);
    }
}