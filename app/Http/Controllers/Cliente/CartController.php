<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // Exibe a página do carrinho.
    public function index()
    {
        $cart = Cart::with('items.product')
            ->where('usuario_id', auth()->id())
            ->where('status', 'aberto')
            ->first();

        return view('cliente.carrinho.index', ['cart' => $cart]);
    }

    // Adiciona um item ao carrinho (via AJAX).
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:produtos,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Produto::find($data['product_id']);
        $cart = Cart::firstOrCreate(['user_id' => auth()->id(), 'status' => 'aberto']);

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity', $data['quantity']);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'price' => $product->preco,
            ]);
        }
        return response()->json(['message' => 'Produto adicionado com sucesso!']);
    }

    // ... (métodos para update e remove) ...

    // Finaliza a compra e gera o UUID para o QR Code.
    public function finalize()
    {
        $cart = auth()->user()->carrinho()->where('status', 'aberto')->firstOrFail();
        
        $cart->update([
            'status' => 'finalizado',
            'uuid' => Str::uuid()
        ]);

        // Redireciona para uma nova página que exibirá o QR Code
        return redirect()->route('cliente.carrinho.checkout', ['uuid' => $cart->uuid]);
    }
}