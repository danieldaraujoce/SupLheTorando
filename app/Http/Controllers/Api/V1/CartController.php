<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show($uuid)
    {
        $cart = Cart::where('uuid', $uuid)
                    ->where('status', 'finalizado')
                    ->with('user', 'items.product')
                    ->firstOrFail();

        // Formata o JSON de acordo com os requisitos
        $response = [
            'cart_uuid' => $cart->uuid,
            'customer' => [
                'name' => $cart->user->nome,
                'document' => $cart->user->cpf ?? null, // Exemplo
            ],
            'items' => $cart->items->map(function ($item) {
                return [
                    'sku' => $item->product->codigo_barras,
                    'name' => $item->product->nome,
                    'quantity' => $item->quantity,
                    'unit_price' => number_format($item->price, 2, '.', ''),
                ];
            }),
            'total_amount' => number_format($cart->items->sum(fn($i) => $i->quantity * $i->price), 2, '.', ''),
        ];

        return response()->json($response);
    }

    // Endpoint que o PDV chama DEPOIS de concluir o pagamento
    public function markAsCompleted(Request $request, $uuid)
    {
        $cart = Cart::where('uuid', $uuid)->where('status', 'finalizado')->firstOrFail();
        
        $cart->update(['status' => 'concluido']);

        // Dispara o evento de gamificação
        event(new \App\Events\CartCompleted($cart));

        return response()->json(['message' => 'Carrinho marcado como concluído.']);
    }
}