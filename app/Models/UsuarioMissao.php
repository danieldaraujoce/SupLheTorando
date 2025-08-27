<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioMissao extends Pivot
{
    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'usuario_missoes';

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
        'data_conclusao' => 'datetime',
    ];

    /**
     * Relação para buscar os dados completos do usuário.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relação para buscar os dados completos da missão.
     */
    public function missao(): BelongsTo
    {
        return $this->belongsTo(Missao::class, 'missao_id');
    }
}