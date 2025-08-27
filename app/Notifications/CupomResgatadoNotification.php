<?php

namespace App\Notifications;

use App\Models\Cupom;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CupomResgatadoNotification extends Notification
{
    use Queueable;

    public $cupom;

    public function __construct(Cupom $cupom)
    {
        $this->cupom = $cupom;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }
    
    // Formata a notificação para ser salva no banco de dados
    public function toArray(object $notifiable): array
    {
        // --- CORREÇÃO APLICADA AQUI ---
        // A chave 'link' foi removida para não causar o erro de rota não definida,
        // alinhando-se com a sua decisão de não ter uma página de listagem de cupons.
        return [
            'titulo' => 'Cupom Resgatado!',
            'mensagem' => "Seu cupom '{$this->cupom->codigo}' foi resgatado. Não se esqueça de usar!",
            'icone' => 'fas fa-tags',
        ];
    }
}