<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Missao extends Model {
    protected $table = 'missoes';
    protected $fillable = [
        'titulo',
        'descricao',
        'tipo_missao',
        'meta_item_tipo',
        'meta_item_id',
        'meta_quantidade',
        'coins_recompensa',
        'data_inicio',
        'data_fim',
        // 'status' foi removido
    ];

    /**
     * Scope para retornar apenas missões vigentes (dentro do período de validade).
     */
    public function scopeAtivas($query)
    {
        return $query->where('data_inicio', '<=', now())
                     ->where('data_fim', '>=', now());
    }

    /**
     * Os usuários que aceitaram esta missão.
     */
    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuario_missoes')
                    ->withPivot('status', 'comprovacao_url', 'data_conclusao')
                    ->withTimestamps();
    }
}