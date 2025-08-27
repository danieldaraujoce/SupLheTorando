<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'nome', 'codigo_barras', 'categoria_id', 'descricao', 'preco', 'estoque', 'imagem_url', 'coins_recompensa', 'status'
    ];

    public function categoria() {
        return $this->belongsTo(CategoriaProduto::class, 'categoria_id');
    }

    /**
     * A relação com as promoções.
     */
    public function promocoes()
    {
        return $this->belongsToMany(Promocao::class, 'promocao_produto')->withPivot('quantidade_necessaria');
    }
}