<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // Adicionado para a consulta avançada

class Cupom extends Model
{
    use HasFactory;

    protected $table = 'cupons';

    protected $fillable = [
        'codigo',
        'descricao',
        'tipo_desconto',
        'valor',
        'valor_minimo_compra',
        'data_validade',
        'limite_uso_total',
        'limite_uso_por_cliente',
        'tipo_cupom',
        'horas_validade',
        'coins_extra_fidelidade',
    ];

    protected $casts = [
        'data_validade' => 'date',
    ];

    /**
     * Scope para retornar apenas cupons vigentes (dentro da data de validade).
     * Esta nova versão corrige o erro e lida com ambos os tipos de cupom corretamente.
     */
    public function scopeAtivos($query)
    {
        return $query->where(function ($q) {
            $agora = now();
            // Condição para cupons normais: a data de validade ainda não passou.
            $q->where(function ($sub) use ($agora) {
                $sub->where('tipo_cupom', 'normal')
                    ->where('data_validade', '>=', $agora->toDateString());
            })
            // Condição para cupons relâmpago: o tempo atual ainda está dentro da janela de horas de validade após a criação.
            ->orWhere(function ($sub) use ($agora) {
                // A função DATE_SUB do SQL é usada para subtrair as horas do tempo atual
                // e verificar se o cupom foi criado depois desse momento.
                $sub->where('tipo_cupom', 'relampago')
                    ->where('created_at', '>=', DB::raw("DATE_SUB('{$agora->toDateTimeString()}', INTERVAL horas_validade HOUR)"));
            });
        });
    }

    /**
     * Os usuários que resgataram este cupom.
     */
    public function usuariosQueResgataram()
    {
        return $this->belongsToMany(User::class, 'usuario_cupons');
    }
}