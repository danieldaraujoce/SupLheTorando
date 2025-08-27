<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsuarioQuizProgresso extends Model
{
    use HasFactory;

    /**
     * O nome da tabela associada ao model.
     *
     * @var string
     */
    protected $table = 'usuario_quiz_progresso';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'quiz_id',
        'quiz_pergunta_id',
        'quiz_resposta_id',
        'correta',
    ];

    /**
     * Define a relação com o Usuário.
     */
    public function usuario(): BelongsTo
    {
        // Assumindo que o seu model de usuário é 'User'
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Define a relação com o Quiz.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Define a relação com a Pergunta.
     */
    public function pergunta(): BelongsTo
    {
        return $this->belongsTo(QuizPergunta::class, 'quiz_pergunta_id');
    }

    /**
     * Define a relação com a Resposta.
     */
    public function resposta(): BelongsTo
    {
        return $this->belongsTo(QuizResposta::class, 'quiz_resposta_id');
    }
}