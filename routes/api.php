<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// CORREÇÃO: Apontando para os controllers corretos
use App\Http\Controllers\Cliente\CarrinhoController;
use App\Http\Controllers\Api\PdvApiController;
use App\Http\Controllers\Api\ProdutoApiController;
use App\Http\Controllers\Api\V1\CartController;


// Rotas para o App do Cliente (precisarão de autenticação via Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // CORREÇÃO: Estas rotas agora usam o CarrinhoController principal e funcional
    Route::get('/carrinho', [CarrinhoController::class, 'index']);
    Route::post('/carrinho/adicionar', [CarrinhoController::class, 'adicionarPorCodigoBarras']);
    Route::post('/carrinho/finalizar', [CarrinhoController::class, 'finalizarCompra']);
});

// Rota para buscar produtos na API externa
Route::get('/produtos/buscar/{codigo_barras}', [ProdutoApiController::class, 'buscarPorCodigo']);

// Rota para o PDV (precisará de autenticação de API Key ou IP)
Route::get('/pdv/carrinho/{codigo_checkout}', [PdvApiController::class, 'obterCarrinho']);

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/carts/{uuid}', [CartController::class, 'show']);
    Route::post('/carts/{uuid}/complete', [CartController::class, 'markAsCompleted']);
});