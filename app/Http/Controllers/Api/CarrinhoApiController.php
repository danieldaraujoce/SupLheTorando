<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CarrinhoApiController extends Controller
{
    public function adicionar(Request $request)
    {
        $request->validate(['codigo_barras' => 'required|exists:produtos,codigo_barras']);

        $produto = Produto::where('codigo_barras', $request->codigo_barras)->first();
        $usuario = $request->user();

        // Encontra o carrinho aberto do usuário ou cria um novo
        $carrinho = $usuario->carrinhos()->firstOrCreate(['status' => 'aberto']);

        // Verifica se o item já está no carrinho
        $item = $carrinho->itens()->where('produto_id', $produto->id)->first();

        if ($item) {
            // Se já existe, incrementa a quantidade
            $item->increment('quantidade');
        } else {
            // Se não, adiciona o novo item
            $carrinho->itens()->create([
                'produto_id' => $produto->id,
                'quantidade' => 1,
                'preco_unitario' => $produto->preco,
            ]);
        }
        
        return response()->json(['success' => true, 'message' => "{$produto->nome} adicionado ao carrinho."]);
    }

    public function show(Request $request) {
        $carrinho = $request->user()->carrinhos()->with('itens.produto')->where('status', 'aberto')->first();
        return response()->json($carrinho);
    }

    public function finalizar(Request $request) {
        $carrinho = $request->user()->carrinhos()->where('status', 'aberto')->firstOrFail();
        
        // Gera um código único para o checkout
        $carrinho->update([
            'status' => 'finalizado',
            'codigo_checkout' => Str::uuid()
        ]);

        return response()->json(['success' => true, 'codigo_checkout' => $carrinho->codigo_checkout]);
    }
}