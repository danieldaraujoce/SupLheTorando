<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log; // Importação necessária

class PdvApiController extends Controller
{
    /**
     * Busca o PEDIDO pelo código de checkout e retorna seus dados para o PDV.
     */
    public function obterCarrinho($codigo_checkout)
    {
        Log::info('PDV API recebeu requisição para buscar codigo_checkout: ' . $codigo_checkout);

        $pedido = Pedido::where('codigo_checkout', $codigo_checkout)
                              ->with(['usuario:id,nome,email', 'itens.produto:id,nome,preco,codigo_barras'])
                              ->first();
        
        if (!$pedido) {
            Log::warning('PDV API: Pedido NÃO ENCONTRADO para o codigo_checkout: ' . $codigo_checkout);
            return response()->json(['message' => 'Registro não encontrado.'], 404);
        }
        
        Log::info('PDV API: Pedido ENCONTRADO para o codigo_checkout: ' . $codigo_checkout);
        return response()->json($pedido);
    }
}