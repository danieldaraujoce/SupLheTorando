<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveis';

    protected $fillable = [
        'nome',
        'imagem_emblema',
        'requisito_minimo_coins',
    ];
}