<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuiaDica extends Model
{
    use HasFactory;

    protected $table = 'guias_dicas';

    protected $fillable = [
        'titulo',
        'imagem_capa',
        'arquivo_url',
        'data_inicio',
        'data_fim',
        // 'status' foi removido
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    /**
     * Scope para retornar apenas guias e dicas vigentes (dentro do período de validade).
     */
    public function scopeAtivas($query)
    {
        return $query->where('data_inicio', '<=', now())
                     ->where('data_fim', '>=', now()->startOfDay());
    }
}