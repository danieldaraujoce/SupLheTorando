<?php

namespace App\Services;

use App\Models\CashbackAtivado;
use App\Models\Pedido;
use App\Models\User;

class CashbackService
{
    /**
     * Aplica os cashbacks ativados por um usuário a um pedido recém-criado.
     *
     * @param User $user
     * @param Pedido $pedido
     * @return float|null O valor total de cashback concedido, ou null se nenhum cashback foi aplicado.
     */
    public function aplicarCashbackParaPedido(User $user, Pedido $pedido): ?float
    {
        
        $cashbackAtivado = CashbackAtivado::where('usuario_id', $usuario->id)
            ->where('utilizado', false)
            ->where('validade', '>=', now())
            ->first();
        // 1. Pega todas as campanhas que o usuário ativou (nosso "opt-in" do Passo 1)
        $campanhasAtivadas = $user->cashbacks()->get();

        if ($campanhasAtivadas->isEmpty()) {
            return null;
        }

        $totalCashbackConcedido = 0;
        $campanhasUtilizadasIds = [];

        // 2. Itera sobre cada campanha ativada para verificar a elegibilidade
        foreach ($campanhasAtivadas as $campanha) {
            // Verifica se a compra atinge o valor mínimo da campanha
            if (is_null($campanha->valor_minimo_compra) || $pedido->total >= $campanha->valor_minimo_compra) {
                
                $valorCashback = 0;

                // 3. Calcula o valor do cashback baseado no tipo da campanha
                if ($campanha->tipo == 'porcentagem') {
                    $valorCashback = ($pedido->total * $campanha->valor) / 100;
                } else { // tipo 'fixo'
                    $valorCashback = $campanha->valor;
                }

                $totalCashbackConcedido += $valorCashback;
                $campanhasUtilizadasIds[] = $campanha->id;
            }
        }

        if ($totalCashbackConcedido > 0) {
            // Arredonda para o inteiro mais próximo, como moedas geralmente são inteiras
            $coinsParaCreditar = round($totalCashbackConcedido);
            
            // 4. Credita os coins na conta do usuário
            $user->increment('coins', $coinsParaCreditar);

            // 5. Registra a transação para o histórico do usuário
            Transacao::create([
                'usuario_id' => $user->id,
                'tipo' => 'credito',
                'valor' => $coinsParaCreditar,
                'descricao' => 'Cashback de compra #' . $pedido->id,
            ]);

            // 6. "Consome" as ativações para que não sejam usadas novamente (uso único)
            $user->cashbacks()->detach($campanhasUtilizadasIds);
            
            return $totalCashbackConcedido;
        }

        return null;
    }
}