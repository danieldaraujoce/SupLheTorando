<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CaixaSurpresaItem extends Model
{
    use HasFactory;

    protected $table = 'caixa_surpresa_itens';

    protected $fillable = [
        'caixa_surpresa_id',
        'tipo_premio',
        'premio_id', // Usado para Produto ou Cupom
        'valor_premio', // Usado para Coins
        'chance_percentual',
        'nome_customizado', // Nosso novo campo
    ];

    /**
     * Define uma relação polimórfica para buscar o prêmio (seja Produto ou Cupom).
     * Esta é uma forma avançada, mas correta, de lidar com prêmios de tipos diferentes.
     * Para funcionar, a tabela precisa da coluna 'premio_type'.
     * Por simplicidade no seu caso, vamos carregar a relação no controller.
     */
     
    // Relação para buscar o prêmio quando é um Produto
    public function produto() {
        return $this->belongsTo(Produto::class, 'premio_id');
    }

    // Relação para buscar o prêmio quando é um Cupom
    public function cupom() {
        return $this->belongsTo(Cupom::class, 'premio_id');
    }
}