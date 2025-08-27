<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recompensa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'imagem_url',
        'custo_coins',
        'estoque',
        'tipo',
        'nivel_necessario_id', // Adicionamos o campo de relacionamento
    ];

    /**
     * Uma recompensa pertence a um nível.
     */
    public function nivel()
    {
        return $this->belongsTo(Nivel::class, 'nivel_necessario_id');
    }

    /**
     * Define o relacionamento de muitos-para-muitos com Usuários.
     * Retorna os usuários que resgataram esta recompensa.
     */
    public function usuariosQueResgataram()
    {
        return $this->belongsToMany(User::class, 'usuario_recompensas', 'recompensa_id', 'usuario_id')
                    ->withPivot('codigo_resgate', 'status', 'created_at');
    }
}