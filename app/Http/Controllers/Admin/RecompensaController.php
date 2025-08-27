<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nivel;
use App\Models\Recompensa;
use App\Models\UsuarioRecompensa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\WhatsAppService;

class RecompensaController extends Controller
{
    /**
     * Exibe a lista principal de recompensas (catálogo).
     */
    public function index()
    {
        $dados['titulo_pagina'] = 'Catálogo de Recompensas';
        $dados['recompensas'] = Recompensa::with('nivel')->orderBy('custo_coins')->paginate(10);
        return view('admin.recompensas.index', $dados);
    }

    /**
     * Mostra o formulário para criar uma nova recompensa.
     */
    public function create()
    {
        $dados['titulo_pagina'] = 'Nova Recompensa';
        $dados['niveis'] = Nivel::orderBy('requisito_minimo_coins')->get();
        return view('admin.recompensas.create', $dados);
    }

    /**
     * Salva uma nova recompensa no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'custo_coins' => 'required|integer|min:0',
            'nivel_necessario_id' => 'nullable|exists:niveis,id',
            'estoque' => 'nullable|integer|min:0',
            // Adicione outras validações conforme necessário
        ]);

        Recompensa::create($request->all());

        return redirect()->route('admin.recompensas.index')
                         ->with('success', 'Recompensa criada com sucesso!');
    }

    /**
     * Redireciona para a tela de edição, seguindo o padrão do MissaoController.
     */
    public function show(Recompensa $recompensa)
    {
        return redirect()->route('admin.recompensas.edit', $recompensa->id);
    }

    /**
     * Mostra o formulário para editar uma recompensa existente.
     */
    public function edit(Recompensa $recompensa)
    {
        $dados['titulo_pagina'] = 'Editar Recompensa';
        $dados['recompensa'] = $recompensa;
        $dados['niveis'] = Nivel::orderBy('requisito_minimo_coins')->get();
        return view('admin.recompensas.edit', $dados);
    }

    /**
     * Atualiza uma recompensa existente no banco de dados.
     */
    public function update(Request $request, Recompensa $recompensa)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'custo_coins' => 'required|integer|min:0',
            'nivel_necessario_id' => 'nullable|exists:niveis,id',
            'estoque' => 'nullable|integer|min:0',
            // Adicione outras validações
        ]);

        $recompensa->update($request->all());

        return redirect()->route('admin.recompensas.index')
                         ->with('success', 'Recompensa atualizada com sucesso!');
    }

    /**
     * Remove uma recompensa do banco de dados.
     */
    public function destroy(Recompensa $recompensa)
    {
        $recompensa->delete();
        return redirect()->route('admin.recompensas.index')
                         ->with('success', 'Recompensa excluída com sucesso!');
    }
    
    /**
     * Exibe a página com os resgates pendentes de validação.
     */
    public function indexPendentes()
    {
        $dados['titulo_pagina'] = 'Recompensas Pendentes de Validação';
        $dados['resgates'] = UsuarioRecompensa::where('status', 'resgatado')
            ->with('usuario', 'recompensa')
            ->latest()
            ->paginate(15);
            
        return view('admin.recompensas.pendentes', $dados);
    }

    /**
     * Exibe todos os códigos de resgate (histórico completo).
     */
    public function codigosDeResgate()
    {
        $dados['titulo_pagina'] = 'Todos os Códigos de Resgate';
        $dados['resgates'] = UsuarioRecompensa::with(['usuario', 'recompensa'])->latest()->paginate(10);

        return view('admin.recompensas.resgates.index', $dados); // Você precisará ter uma view para isso
    }

    /**
     * Aprova um resgate de recompensa.
     */
    public function aprovarResgate(UsuarioRecompensa $resgate)
    {
        DB::transaction(function () use ($resgate) {
            $resgate->status = 'utilizado';
            $resgate->data_utilizacao = now();
            $resgate->save();
        });
        
        if ($resgate->usuario->whatsapp) {
        $mensagem = "Olá, {$resgate->usuario->nome}! Sua recompensa '{$resgate->recompensa->nome}' foi aprovada! Use o código {$resgate->codigo_resgate} para retirá-la.";
        $whatsapp->enviarMensagem($resgate->usuario->whatsapp, $mensagem);
    }

        return back()->with('success', 'Resgate aprovado e código marcado como utilizado!');
    }
    
    /**
     * Rejeita um resgate de recompensa e devolve as moedas ao usuário.
     */
    public function rejeitarResgate(UsuarioRecompensa $resgate)
    {
        DB::transaction(function () use ($resgate) {
            // Devolve as moedas ao usuário
            $resgate->usuario->increment('coins', $resgate->recompensa->custo_coins);
            
            // Deleta o registro do resgate para que o usuário possa tentar novamente se quiser
            $resgate->delete();
        });

        return back()->with('success', 'Resgate rejeitado e moedas devolvidas ao cliente.');
    }
}