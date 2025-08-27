<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizPergunta extends Model {

    use HasFactory;

    protected $table = 'quiz_perguntas';
    
    protected $fillable = ['quiz_id', 'texto_pergunta', 'coins_recompensa'];

    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
    public function respostas() {
        return $this->hasMany(QuizResposta::class, 'pergunta_id');
    }
}