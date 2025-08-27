<?php

namespace App\Http\Controllers\Cliente;

use App\Events\AcaoDoUsuarioRealizada;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizPergunta;
use App\Models\QuizResposta;
use App\Models\UsuarioQuizProgresso;
use App\Models\Transacao; // <-- ADICIONADO
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
     public function index()
    {
        $dados['titulo_pagina'] = 'Central de Quizzes';
        $dados['quizzes'] = Quiz::ativas()->get();
        return view('cliente.quizzes.index', $dados);
    }

    public function show(Quiz $quiz)
    {
        if (!$quiz->data_inicio || $quiz->data_inicio > now() || $quiz->data_fim < now()->startOfDay()) {
            return redirect()->route('cliente.quizzes.index')->with('error', 'Este quiz não está disponível.');
        }

        $usuario = Auth::user();
        
        $perguntasRespondidasIds = UsuarioQuizProgresso::where('user_id', $usuario->id)
            ->where('quiz_id', $quiz->id)
            ->pluck('quiz_pergunta_id');

        $perguntaAtual = $quiz->perguntas()
            ->whereNotIn('id', $perguntasRespondidasIds)
            ->first();

        $dados['titulo_pagina'] = $quiz->titulo;
        $dados['quiz'] = $quiz;
        $dados['pergunta_atual'] = $perguntaAtual;
        $dados['perguntasRespondidas'] = $perguntasRespondidasIds->toArray();

        return view('cliente.quizzes.show', $dados);
    }

    public function responder(Request $request, QuizPergunta $pergunta)
    {
        $quiz = $pergunta->quiz;
        if (!$quiz->data_inicio || $quiz->data_inicio > now() || $quiz->data_fim < now()->startOfDay()) {
            return back()->with('error', 'Este quiz não está mais disponível para responder.');
        }

        $request->validate(['resposta_id' => 'required|integer|exists:quiz_respostas,id']);

        $usuario = Auth::user();
        $respostaEscolhida = QuizResposta::find($request->resposta_id);

        if ($respostaEscolhida->pergunta_id !== $pergunta->id) {
            return back()->with('error', 'Resposta inválida.');
        }

        $jaRespondeu = UsuarioQuizProgresso::where('user_id', $usuario->id)
            ->where('quiz_pergunta_id', $pergunta->id)
            ->exists();

        if ($jaRespondeu) {
            return redirect()->route('cliente.quizzes.show', $pergunta->quiz_id)->with('error', 'Você já respondeu esta pergunta.');
        }
        
        DB::transaction(function () use ($usuario, $pergunta, $respostaEscolhida) {
            UsuarioQuizProgresso::create([
                'user_id' => $usuario->id,
                'quiz_id' => $pergunta->quiz_id,
                'quiz_pergunta_id' => $pergunta->id,
                'quiz_resposta_id' => $respostaEscolhida->id,
                'correta' => $respostaEscolhida->correta,
            ]);

            if ($respostaEscolhida->correta) {
                $recompensa = $pergunta->coins_recompensa;
                $usuario->increment('coins', $recompensa);
                $usuario->increment('total_coins_acumulados', $recompensa);
                
                // --- CORREÇÃO ADICIONADA AQUI ---
                Transacao::create([
                    'usuario_id' => $usuario->id,
                    'tipo' => 'credito',
                    'valor' => $recompensa,
                    'descricao' => 'Moedas ganhas no quiz: ' . $pergunta->quiz->titulo
                ]);
                
                AcaoDoUsuarioRealizada::dispatch($usuario, 'responder_quiz', $pergunta->quiz);
            }
        });

        if ($respostaEscolhida->correta) {
            return redirect()->route('cliente.quizzes.show', $pergunta->quiz_id)
                             ->with('quiz_feedback', [
                                 'correta' => true,
                                 'coins' => $pergunta->coins_recompensa
                             ]);
        } else {
            return redirect()->route('cliente.quizzes.show', $pergunta->quiz_id)->with('error', 'Resposta incorreta. Continue tentando!');
        }
    }
}