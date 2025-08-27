<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaDestaque extends Model
{
    use HasFactory;

    protected $table = 'categorias_destaques';

    protected $fillable = [
        'nome',
        'slug',
    ];

    /**
     * Define a relação: Uma Categoria de Destaque pode ter muitos Destaques da Home.
     */
    public function destaques()
    {
        return $this->hasMany(DestaqueHome::class, 'categoria_destaque_id');
    }
}