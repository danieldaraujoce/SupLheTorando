<?php

namespace App\Http\Controllers;

use App\Models\DestaqueHome; // Importa nosso model de Destaques
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Exibe a página inicial com os destaques dinâmicos.
     */
    public function index()
    {
        // CORREÇÃO: Busca no banco apenas os destaques ATIVOS e ordena pela coluna 'ordem'
        $destaques = DestaqueHome::where('status', 'ativo')->orderBy('ordem')->get();

        // Retorna a view 'home', passando a variável 'destaques' para ela
        return view('home', compact('destaques'));
    }
}