<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResposta extends Model {

    use HasFactory;

    protected $table = 'quiz_respostas';
    
    protected $fillable = ['pergunta_id', 'texto_resposta', 'correta'];

    public function pergunta() {
        return $this->belongsTo(QuizPergunta::class, 'pergunta_id');
    }
}