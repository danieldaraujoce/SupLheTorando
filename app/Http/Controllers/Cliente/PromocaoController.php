<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Promocao;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromocaoController extends Controller
{
    /**
     * Exibe os detalhes de uma promoção específica.
     */
    public function show(Promocao $promocao): View
    {
        // Carrega os produtos associados para evitar múltiplas consultas no banco
        $promocao->load('produtos');

        // Retorna a view com os dados da promoção
        return view('cliente.promocoes.show', [
            'promocao' => $promocao,
            'titulo_pagina' => 'Detalhes da Promoção'
        ]);
    }
}