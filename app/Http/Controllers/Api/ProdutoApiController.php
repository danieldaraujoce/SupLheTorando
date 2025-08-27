<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProdutoApiController extends Controller
{
    public function buscarPorCodigo(string $codigo_barras)
    {
        // URL da API pública Open Food Facts
        $apiUrl = "https://world.openfoodfacts.org/api/v0/product/{$codigo_barras}.json";

        try {
            $response = Http::get($apiUrl);

            if ($response->successful() && $response->json('status') === 1) {
                $productData = $response->json('product');
                
                // Monta uma resposta padronizada com os dados que nos interessam
                $dadosFormatados = [
                    'nome' => $productData['product_name'] ?? 'Nome não encontrado',
                    'imagem_url' => $productData['image_url'] ?? null,
                    'categorias' => $productData['categories'] ?? '',
                ];

                return response()->json([
                    'success' => true,
                    'data' => $dadosFormatados,
                ]);
            }

            // Se o produto não for encontrado na API
            return response()->json(['success' => false, 'message' => 'Produto não encontrado na base de dados externa.'], 404);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao consultar a API externa.'], 500);
        }
    }
}