<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalculadoraController extends Controller
{
    /**
     * Exibe a calculadora de moedas.
     */
    public function index()
    {
        return view('admin.calculadora.index');
    }
}