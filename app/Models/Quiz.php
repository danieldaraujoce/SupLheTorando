<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titulo', 
        'descricao', 
        'data_inicio', 
        'data_fim'
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    /**
     * Define a relação: um quiz tem muitas perguntas.
     */
    public function perguntas()
    {
        return $this->hasMany(QuizPergunta::class);
    }

    /**
     * Escopo para retornar apenas os quizzes ativos com base na data.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAtivas($query)
    {
        return $query->where('data_inicio', '<=', now())
                     ->where('data_fim', '>=', now()->startOfDay());
    }
}