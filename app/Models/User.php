<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
    * A tabela associada ao modelo.
    */
    protected $table = 'usuarios';

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'nome',
        'email',
        'senha',
        'whatsapp',
        'nivel_acesso',
        'coins',
        'total_coins_acumulados',
        'status',
        'giros_roleta',
    ];

    /**
     * Os atributos que devem ser escondidos.
     */
    protected $hidden = [
        'senha',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos (casted).
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'senha' => 'hashed',
    ];
    
    /**
     * Pega o valor da senha para autenticação.
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }

    /**
     * As missões que o usuário aceitou.
     */
    public function missoes()
    {
        return $this->belongsToMany(Missao::class, 'usuario_missoes', 'usuario_id', 'missao_id')
                    ->using(UsuarioMissao::class)
                    ->withPivot('id', 'status', 'comprovacao_url', 'data_conclusao')
                    ->withTimestamps();
    }

    /**
     * As respostas corretas que o usuário deu em quizzes.
     */
    public function respostasCorretas()
    {
        // Um usuário pode ter muitas respostas corretas (relação Muitos-para-Muitos)
        return $this->belongsToMany(Pergunta::class, 'usuario_respostas_corretas');
    }

    /**
     * Os cupons que este usuário resgatou.
     */
    public function cuponsResgatados()
    {
        return $this->belongsToMany(Cupom::class, 'usuario_cupons')->withTimestamps();
    }

    /**
     * Calcula e retorna o nível atual do usuário com base em suas moedas.
     */
    public function nivelAtual()
    {
        $nivel = Nivel::orderBy('requisito_minimo_coins', 'desc')
                    ->where('requisito_minimo_coins', '<=', $this->total_coins_acumulados)
                    ->first();

        // **INÍCIO DA CORREÇÃO**
        // Se nenhum nível for encontrado (usuário com 0 pontos),
        // criamos um objeto padrão para evitar o erro 'null'.
        if (!$nivel) {
            return (object)[
                'nome' => 'Iniciante',
                'requisito_minimo_coins' => 0
            ];
        }
        // **FIM DA CORREÇÃO**

        return $nivel;
    }

    /**
     * Calcula o progresso percentual para o próximo nível.
     * Retorna um array com o percentual e o nome do próximo nível.
     */
    public function progressoNivel()
    {
        $nivelAtual = $this->nivelAtual();
        
        $proximoNivel = Nivel::where('requisito_minimo_coins', '>', $nivelAtual->requisito_minimo_coins ?? 0)
                           ->orderBy('requisito_minimo_coins', 'asc')
                           ->first();
        
        if (!$proximoNivel) {
            return ['percentual' => 100, 'proximo_nivel_nome' => 'Máximo'];
        }

        $requisitoNivelAtual = $nivelAtual->requisito_minimo_coins ?? 0;
        $rangeDoNivel = $proximoNivel->requisito_minimo_coins - $requisitoNivelAtual;
        
        $progressoNoNivelAtual = $this->total_coins_acumulados - $requisitoNivelAtual;
        
        $percentual = ($rangeDoNivel > 0) ? round(($progressoNoNivelAtual / $rangeDoNivel) * 100) : 100;

        return [
            'percentual' => max(0, min(100, $percentual)),
            'proximo_nivel_nome' => $proximoNivel->nome,
        ];
    }

    public function carrinho()
    {
        return $this->hasOne(Cart::class, 'usuario_id')->ofMany(
            ['created_at' => 'max', 'id' => 'max'],
            function ($query) {
                $query->where('status', 'aberto');
            }
        );
    }

    /**
     * Define o relacionamento de muitos-para-muitos com Recompensas.
     * Retorna as recompensas que o usuário já resgatou.
     */
    public function recompensasResgatadas()
    {
        return $this->belongsToMany(Recompensa::class, 'usuario_recompensas', 'usuario_id', 'recompensa_id')
                    ->using(UsuarioRecompensa::class) 
                    ->withPivot('id', 'codigo_resgate', 'status', 'created_at'); 
    }

    /**
     * Define a relação de que um usuário pode ter ativado vários cashbacks.
     * O nome "cashbacks" corresponde ao que é chamado no CashbackController.
     */
    public function cashbacks()
    {
        return $this->belongsToMany(\App\Models\Cashback::class, 'cashback_user');
    }

}