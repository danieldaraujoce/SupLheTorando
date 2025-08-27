<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Cashback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashbackController extends Controller
{
    /**
     * Anexa uma campanha de cashback ao usuário logado (ativa a campanha).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cashback  $cashback
     * @return \Illuminate\Http\JsonResponse
     */
    public function resgatar(Request $request, Cashback $cashback)
    {
        $user = Auth::user();

        // --- CORREÇÃO APLICADA AQUI ---
        // Validação 1: Verifica se a campanha está realmente ativa usando o escopo 'ativas()' do Model Cashback.
        // Isto remove a verificação do campo 'status' que não existe e centraliza a lógica no Model.
        $campanhaEstaAtiva = Cashback::ativas()->where('id', $cashback->id)->exists();
        if (!$campanhaEstaAtiva) {
            return response()->json(['success' => false, 'message' => 'Esta campanha não está mais disponível.'], 404);
        }

        // Validação 2: Verifica se o usuário já ativou esta campanha para evitar duplicatas.
        // Esta linha já estava correta pois a relação 'cashbacks' existe no seu User Model.
        $jaResgatou = $user->cashbacks()->where('cashback_id', $cashback->id)->exists();
        if ($jaResgatou) {
            return response()->json(['success' => false, 'message' => 'Você já ativou esta campanha.'], 409); // HTTP 409 Conflict
        }

        // Se passar nas validações, anexa a campanha ao usuário
        $user->cashbacks()->attach($cashback->id);

        // Retorna uma resposta de sucesso para o front-end
        return response()->json(['success' => true, 'message' => 'Campanha de cashback ativada com sucesso!']);
    }
}