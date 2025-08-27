<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashback extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'tipo',
        'valor',
        'valor_minimo_compra',
        'data_inicio',
        'data_fim',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function scopeAtivas($query)
    {
        return $query->where('data_inicio', '<=', now())
                     ->where('data_fim', '>=', now()->startOfDay());
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}