<?php

namespace App\Listeners;

use App\Events\AcaoDoUsuarioRealizada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class VerificarConclusaoDeMissao
{
    /**
     * Handle the event.
     */
    public function handle(AcaoDoUsuarioRealizada $event): void
    {
        // Busca missões em progresso do usuário que correspondem à ação
        $missoesParaVerificar = $event->usuario->missoes()
            ->wherePivot('status', 'em_progresso')
            ->where('meta_item_tipo', $event->tipoAcao)
            ->where('data_fim', '>=', now())
            ->get();

        foreach ($missoesParaVerificar as $missao) {
            $completou = false;

            // Lógica para cada tipo de missão
            switch ($missao->tipo_missao) {
                case 'engajamento':
                    // Para engajamento, uma única ação já completa a missão
                    $completou = true;
                    break;
                
                case 'compra':
                    // Lógica futura: verificar se a quantidade de produtos/categorias foi atingida
                    // Ex: $progresso = $event->usuario->comprasDoProduto($missao->meta_item_id)->count();
                    // if ($progresso >= $missao->meta_quantidade) { $completou = true; }
                    break;
            }

            if ($completou) {
                DB::transaction(function () use ($event, $missao) {
                    // 1. Atualiza o status
                    $event->usuario->missoes()->updateExistingPivot($missao->id, [
                        'status' => 'concluida',
                        'data_conclusao' => now()
                    ]);

                    // 2. Entrega a recompensa
                    $event->usuario->increment('coins', $missao->coins_recompensa);
                });
            }
        }
    }
}