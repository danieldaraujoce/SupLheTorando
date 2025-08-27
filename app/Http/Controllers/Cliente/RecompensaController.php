<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Recompensa;
use App\Models\Transacao; // <-- ADICIONADO
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RecompensaController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Loja de Recompensas';
        $usuario = Auth::user();

        $dados['recompensas'] = Recompensa::with('nivel')
                                          ->where('status', 'ativo')
                                          ->orderBy('custo_coins', 'asc')
                                          ->get();
        
        $dados['usuario'] = $usuario;

        return view('cliente.recompensas.index', $dados);
    }
    
    public function resgatar(Recompensa $recompensa)
    {
        $usuario = Auth::user();
        
        // ... (sua lógica de validação) ...
        if ($usuario->coins < $recompensa->custo_coins) {
            return back()->with('error', 'Você não tem moedas suficientes para resgatar esta recompensa.');
        }
        if ($recompensa->nivel_necessario_id && ($usuario->nivel_id < $recompensa->nivel_necessario_id)) {
            return back()->with('error', 'Você ainda não atingiu o nível necessário para resgatar esta recompensa.');
        }
        if ($recompensa->estoque !== 0 && $recompensa->estoque <= 0) {
            return back()->with('error', 'Desculpe, esta recompensa está fora de estoque.');
        }
        
        $custo = $recompensa->custo_coins;
        $usuario->coins -= $custo; // Decrementa as moedas
        $usuario->save();
        
        if ($recompensa->estoque !== 0) {
            $recompensa->decrement('estoque');
        }

        // --- CORREÇÃO ADICIONADA AQUI ---
        Transacao::create([
            'usuario_id' => $usuario->id,
            'tipo' => 'debito',
            'valor' => $custo,
            'descricao' => 'Resgate da recompensa: ' . $recompensa->nome
        ]);

        $codigoResgate = Str::upper(Str::random(10));
        $usuario->recompensasResgatadas()->attach($recompensa->id, [
            'codigo_resgate' => $codigoResgate,
            'status' => 'resgatado'
        ]);

        return back()->with('success', 'Recompensa resgatada! Use o código ' . $codigoResgate . ' para retirá-la.');
    }

    public function resgatadas()
    {
        $dados['titulo_pagina'] = 'Minhas Recompensas';
        $dados['recompensasResgatadas'] = Auth::user()->recompensasResgatadas()->with('recompensa')->latest()->get();

        return view('cliente.recompensas.resgatadas', $dados);
    }
}