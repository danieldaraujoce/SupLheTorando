<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jogo extends Model {

    use HasFactory;

    protected $fillable = ['nome', 'slug', 'descricao', 'status'];
    
    public function premios() { return $this->hasMany(JogoPremio::class); }
}