<?php
namespace App\Listeners;
use App\Events\CartCompleted;

class AwardGamificationPoints
{
    public function handle(CartCompleted $event): void
    {
        $cart = $event->cart;
        $user = $cart->user;

        // 1. Conceder pontos/XP pela compra (ex: 1 ponto por cada real gasto)
        $pointsFromPurchase = floor($cart->items->sum(fn($i) => $i->quantity * $i->price));
        
        // 2. Conceder bônus por usar o "scan-and-go"
        $scanAndGoBonus = 50; // Exemplo: 50 pontos de bônus

        $totalPoints = $pointsFromPurchase + $scanAndGoBonus;
        
        $user->increment('total_coins_acumulados', $totalPoints);
        $user->increment('coins', $totalPoints);

        // 3. Verificar missões (exemplo)
        // foreach ($cart->items as $item) {
        //     // ... lógica para verificar se $item->product_id corresponde a alguma missão ativa ...
        // }
    }
}