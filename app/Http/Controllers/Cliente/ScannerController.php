<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    /**
     * Exibe a tela de escaneamento de produtos.
     */
    public function index()
    {
        $dados['titulo_pagina'] = 'Escanear Produto';
        return view('cliente.scanner.index', $dados);
    }
}