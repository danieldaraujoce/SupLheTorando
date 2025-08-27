<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoricoController extends Controller
{
    /**
     * Exibe a lista de pedidos do usuário.
     */
    public function index()
    {
        $dados['titulo_pagina'] = 'Histórico de Compras';
        $usuario = Auth::user();
        
        // Busca os pedidos do usuário, excluindo os cancelados, e ordena do mais recente para o mais antigo
        $dados['pedidos'] = Pedido::where('usuario_id', $usuario->id)
                                  ->where('status', '!=', 'cancelado') // Adicionada esta linha para filtrar
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(10);

        return view('cliente.historico.index', $dados);
    }

    /**
     * Exibe os detalhes de um pedido específico.
     */
    public function show(Pedido $pedido)
    {
        $dados['titulo_pagina'] = 'Detalhes do Pedido';
        
        // Verifica se o pedido pertence ao usuário logado
        if ($pedido->usuario_id !== Auth::id()) {
            abort(403);
        }

        // Carrega os itens do pedido com os dados do produto
        $dados['pedido'] = $pedido->load('itens.produto');
        
        return view('cliente.historico.show', $dados);
    }
}