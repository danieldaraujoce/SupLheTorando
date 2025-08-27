<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcaoDoUsuarioRealizada
{
    use Dispatchable, SerializesModels;

    public User $usuario;
    public string $tipoAcao; // Ex: 'responder_quiz', 'comprar_produto'
    public $modeloRelacionado; // O objeto relacionado (ex: o Quiz, o Produto)

    /**
     * Create a new event instance.
     */
    public function __construct(User $usuario, string $tipoAcao, $modeloRelacionado = null)
    {
        $this->usuario = $usuario;
        $this->tipoAcao = $tipoAcao;
        $this->modeloRelacionado = $modeloRelacionado;
    }
}