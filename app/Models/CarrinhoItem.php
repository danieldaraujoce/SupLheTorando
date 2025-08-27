<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $table = 'carrinho_itens';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Relacionamento: Um item de carrinho pertence a um carrinho.
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'carrinho_id');
    }

    // Relacionamento: Um item de carrinho está associado a um produto.
    public function product()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
}