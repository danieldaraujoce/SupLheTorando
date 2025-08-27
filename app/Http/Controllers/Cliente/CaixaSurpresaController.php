<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\CaixaSurpresa;
use App\Models\Transacao; // <-- ADICIONADO
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaixaSurpresaController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Caixas Surpresa';
        $dados['caixas'] = CaixaSurpresa::ativas()->get();
        
        return view('cliente.caixas-surpresa.index', $dados);
    }

    public function show(CaixaSurpresa $caixaSurpresa)
    {
        $dados['titulo_pagina'] = 'Abrir Caixa: ' . $caixaSurpresa->nome;
        $dados['caixa'] = $caixaSurpresa->load('itens');

        return view('cliente.caixas-surpresa.show', $dados);
    }

    public function abrir(CaixaSurpresa $caixaSurpresa)
    {
        $usuario = Auth::user();
        $custoParaAbrir = 500;

        if ($usuario->coins < $custoParaAbrir) {
            return response()->json(['success' => false, 'message' => 'Moedas insuficientes para abrir a caixa.']);
        }
        
        $usuario->decrement('coins', $custoParaAbrir);

        // --- CORREÇÃO ADICIONADA AQUI (Débito) ---
        Transacao::create([
            'usuario_id' => $usuario->id,
            'tipo' => 'debito',
            'valor' => $custoParaAbrir,
            'descricao' => 'Abertura da Caixa Surpresa: ' . $caixaSurpresa->nome
        ]);
        
        $itens = $caixaSurpresa->itens;
        $rand = mt_rand(1, 10000) / 100;
        $chanceAcumulada = 0;
        $premioSorteado = null;

        foreach ($itens as $item) {
            $chanceAcumulada += $item->chance_percentual;
            if ($rand <= $chanceAcumulada) {
                $premioSorteado = $item;
                break;
            }
        }
        
        if (!$premioSorteado) { $premioSorteado = $itens->last(); }

        $descricaoPremio = '';
        if ($premioSorteado->tipo_premio == 'coins') {
            $valor_premio = $premioSorteado->valor_premio;
            $usuario->increment('coins', $valor_premio);
            $usuario->increment('total_coins_acumulados', $valor_premio);
            $descricaoPremio = $valor_premio . ' Moedas';

            // --- CORREÇÃO ADICIONADA AQUI (Crédito) ---
            Transacao::create([
                'usuario_id' => $usuario->id,
                'tipo' => 'credito',
                'valor' => $valor_premio,
                'descricao' => 'Prêmio da Caixa Surpresa: ' . $caixaSurpresa->nome
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Você ganhou: ' . $descricaoPremio,
            'premio' => [ 'descricao' => $descricaoPremio ]
        ]);
    }
}