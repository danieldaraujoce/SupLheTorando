<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaixaSurpresa extends Model
{
    use HasFactory;
    
    protected $table = 'caixas_surpresa';
    
    // Atualizado para a nova estrutura com datas e sem status manual
    protected $fillable = [
        'nome', 
        'descricao', 
        'imagem_url', 
        'data_inicio', 
        'data_fim'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    /**
     * Scope para retornar apenas caixas surpresa vigentes (dentro do período de validade).
     */
    public function scopeAtivas($query)
    {
        return $query->where('data_inicio', '<=', now())
                     ->where('data_fim', '>=', now()->startOfDay());
    }

    public function itens()
    {
        return $this->hasMany(CaixaSurpresaItem::class);
    }
}