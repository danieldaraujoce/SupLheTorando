<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roleta extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     */
    protected $fillable = [
        'titulo',
        'descricao',
        'data_inicio',
        'data_fim',
    ];

    /**
     * A conversão de tipos de atributos.
     */
    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    /**
     * Scope para retornar apenas roletas vigentes (dentro do período de validade).
     */
    public function scopeAtivas($query)
    {
        return $query->where('data_inicio', '<=', now())
                     ->where('data_fim', '>=', now());
    }

    /**
     * Define a relação: uma roleta tem muitos itens (fatias).
     */
    public function itens()
    {
        return $this->hasMany(RoletaItem::class);
    }
}