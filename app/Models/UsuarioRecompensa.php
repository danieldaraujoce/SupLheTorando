<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioRecompensa extends Pivot
{
    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'usuario_recompensas';

    /**
     * Indica se os IDs são auto-incremento.
     *
     * @var bool
     */
    public $incrementing = true;
    
    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'data_resgate' => 'datetime',
        'data_utilizacao' => 'datetime',
    ];

    /**
     * Relação para buscar os dados completos do usuário.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relação para buscar os dados completos da recompensa.
     */
    public function recompensa(): BelongsTo
    {
        return $this->belongsTo(Recompensa::class, 'recompensa_id');
    }
}