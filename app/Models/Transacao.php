<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     * @var string
     */
    protected $table = 'transacoes';

    /**
     * Os atributos que podem ser preenchidos em massa.
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario_id',
        'tipo',
        'valor',
        'descricao',
    ];

    /**
     * Relação para buscar os dados do usuário desta transação.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}