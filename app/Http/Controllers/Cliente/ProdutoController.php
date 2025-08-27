<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    /**
     * Exibe a página de listagem de produtos para o cliente.
     */
    public function index()
    {
        $dados['titulo_pagina'] = 'Nossos Produtos';
        
        // Busca todos os produtos ativos, com paginação
        $dados['produtos'] = Produto::where('status', 'ativo')->latest()->paginate(20);

        return view('cliente.produtos.index', $dados);
    }

    /**
     * Busca um produto pelo código de barras via API e o retorna como JSON.
     *
     * @param  string  $codigo_barras
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarPorCodigoBarras($codigo_barras)
    {
        $produto = \App\Models\Produto::where('codigo_barras', $codigo_barras)->where('status', 'ativo')->first();

        if ($produto) {
            // Se o produto for encontrado, retorna os seus dados
            return response()->json($produto);
        }

        // Se não for encontrado, retorna um erro 404
        return response()->json(['message' => 'Produto não encontrado.'], 404);
    }

    /**
     * Adiciona um produto ao carrinho com base no seu código de barras.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adicionarPorCodigoBarras(Request $request)
    {
        $request->validate([
            'codigo_barras' => 'required|string|max:255',
        ]);

        // Encontra o produto pelo código de barras
        $produto = \App\Models\Produto::where('codigo_barras', $request->codigo_barras)
                                      ->where('status', 'ativo')
                                      ->first();

        // Se o produto não for encontrado, retorna erro
        if (!$produto) {
            return response()->json(['message' => 'Produto não encontrado com este código de barras.'], 404);
        }

        // Se encontrou o produto, continua com a lógica de adicionar ao carrinho
        $usuario = auth()->user();
        $carrinho = $usuario->carrinho()->firstOrCreate([]); // Encontra ou cria um carrinho aberto para o usuário

        // Verifica se o item já está no carrinho
        $itemCarrinho = $carrinho->itens()->where('produto_id', $produto->id)->first();

        if ($itemCarrinho) {
            // Se já existe, apenas incrementa a quantidade
            $itemCarrinho->increment('quantidade');
        } else {
            // Se não existe, cria um novo item no carrinho
            $carrinho->itens()->create([
                'produto_id' => $produto->id,
                'quantidade' => 1,
                'preco_unitario' => $produto->preco,
            ]);
        }

        return response()->json([
            'message' => "Produto '{$produto->nome}' adicionado ao carrinho com sucesso!",
            // Opcional: pode retornar o total de itens no carrinho para atualizar a UI
            'total_itens' => $carrinho->itens()->sum('quantidade'),
        ]);
    }
}