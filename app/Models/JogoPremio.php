<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JogoPremio extends Model {

    use HasFactory;

    protected $table = 'jogo_premios';

    protected $fillable = ['jogo_id', 'descricao_premio', 'tipo_premio', 'premio_id', 'valor_premio', 'chance_percentual'];
    
    public function jogo() { return $this->belongsTo(Jogo::class); }
}