<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocao extends Model
{
    use HasFactory;

    protected $table = 'promocoes';

    protected $fillable = [
        'titulo',
        'descricao',
        'imagem_promocao',
        'tipo_desconto',
        'valor_desconto',
        'data_inicio',
        'data_fim',
        'caixa_surpresa_id',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    /**
     * Scope para retornar apenas promoções vigentes (dentro do período de validade).
     */
    public function scopeAtivas($query)
    {
        return $query->where('data_inicio', '<=', now())
                     ->where('data_fim', '>=', now()->startOfDay());
    }

    public function produtos()
    {
        return $this->belongsToMany(Produto::class, 'promocao_produto')->withPivot('quantidade_necessaria');
    }

    public function caixaSurpresa()
    {
        return $this->belongsTo(CaixaSurpresa::class);
    }
}