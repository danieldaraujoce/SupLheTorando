<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Roleta;
use App\Models\Transacao; // <-- ADICIONADO
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoletaController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Roleta da Sorte';
        $dados['roletas'] = Roleta::ativas()->get();
        return view('cliente.roletas.index', $dados);
    }

    public function show(Roleta $roleta)
    {
        if (!$roleta->data_inicio || $roleta->data_inicio > now() || $roleta->data_fim < now()->startOfDay()) {
            abort(404);
        }

        $dados['titulo_pagina'] = $roleta->titulo;
        $dados['roleta'] = $roleta->load('itens');
        $dados['giros_usuario'] = Auth::user()->giros_roleta;
        return view('cliente.roletas.show', $dados);
    }

    public function girar(Roleta $roleta)
    {
        if (!$roleta->data_inicio || $roleta->data_inicio > now() || $roleta->data_fim < now()->startOfDay()) {
            return response()->json(['success' => false, 'message' => 'Esta roleta não está mais disponível.'], 404);
        }

        $usuario = Auth::user();

        if ($usuario->giros_roleta <= 0) {
            return response()->json(['success' => false, 'message' => 'Você não tem giros disponíveis.']);
        }

        $usuario->decrement('giros_roleta');

        $itens = $roleta->itens->values();
        $rand = mt_rand(1, 10000) / 100;
        $chanceAcumulada = 0;
        $premioSorteado = null;
        $indiceVencedor = 0;

        foreach ($itens as $index => $item) {
            $chanceAcumulada += $item->chance_percentual;
            if ($rand <= $chanceAcumulada) {
                $premioSorteado = $item;
                $indiceVencedor = $index;
                break;
            }
        }
        
        if (!$premioSorteado) {
            $premioSorteado = $itens->last();
            $indiceVencedor = $itens->count() - 1;
        }

        if ($premioSorteado->tipo_premio == 'coins') {
            $valor_premio = $premioSorteado->valor_premio;
            $usuario->increment('coins', $valor_premio);
            $usuario->increment('total_coins_acumulados', $valor_premio);

            // --- CORREÇÃO ADICIONADA AQUI ---
            Transacao::create([
                'usuario_id' => $usuario->id,
                'tipo' => 'credito',
                'valor' => $valor_premio,
                'descricao' => 'Prêmio da Roleta da Sorte'
            ]);
        }

        return response()->json([
            'success' => true,
            'premio' => $premioSorteado,
            'giros_restantes' => $usuario->fresh()->giros_roleta
        ]);
    }

    public function comprarGiro(Request $request)
    {
        $request->validate(['quantidade' => 'required|integer|min:1']);
        
        $usuario = Auth::user();
        $custoPorGiro = 100;
        $custoTotal = $request->quantidade * $custoPorGiro;

        if ($usuario->coins < $custoTotal) {
            return back()->with('error', 'Saldo de moedas insuficiente para comprar giros.');
        }

        $usuario->coins -= $custoTotal;
        $usuario->giros_roleta += $request->quantidade;
        $usuario->save();

        // --- CORREÇÃO ADICIONADA AQUI ---
        Transacao::create([
            'usuario_id' => $usuario->id,
            'tipo' => 'debito',
            'valor' => $custoTotal,
            'descricao' => 'Compra de ' . $request->quantidade . ' giro(s) para a roleta'
        ]);

        return back()->with('success', 'Você comprou ' . $request->quantidade . ' giros por ' . $custoTotal . ' moedas!');
    }
}