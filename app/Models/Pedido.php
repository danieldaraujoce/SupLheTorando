<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario_id',
        'total',
        'status',
        'codigo_checkout', // CORREÇÃO APLICADA AQUI
    ];

    /**
     * Get the user that owns the order.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function itens()
    {
        return $this->hasMany(PedidoItem::class);
    }
}