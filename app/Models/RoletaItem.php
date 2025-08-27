<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoletaItem extends Model
{
    use HasFactory;

    protected $table = 'roleta_itens';

    protected $fillable = [
        'roleta_id',
        'descricao',
        'tipo_premio',
        'premio_id',
        'valor_premio',
        'chance_percentual',
        'cor_slice',
        'nome_customizado', // Novo campo
    ];

    public function roleta()
    {
        return $this->belongsTo(Roleta::class);
    }

    // Relação para buscar o prêmio quando é um Produto
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'premio_id');
    }

    // Relação para buscar o prêmio quando é um Cupom
    public function cupom()
    {
        return $this->belongsTo(Cupom::class, 'premio_id');
    }
}