<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carrinhos';

    protected $fillable = [
        'user_id',
        'status',
        'uuid',
    ];

    // Relacionamento: Um carrinho pertence a um usuário.
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relacionamento: Um carrinho tem muitos itens.
    public function items()
    {
        return $this->hasMany(CartItem::class, 'carrinho_id');
    }
}