<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizPergunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerguntaController extends Controller
{
    /**
     * Mostra o formulário para criar uma nova pergunta para um quiz específico.
     */
    public function create(Quiz $quiz)
    {
        $dados['titulo_pagina'] = 'Adicionar Nova Pergunta';
        $dados['quiz'] = $quiz;
        return view('admin.perguntas.create', $dados);
    }

    /**
     * Salva a nova pergunta e suas respostas no banco de dados.
     */
    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'texto_pergunta' => 'required|string|max:255',
            'coins_recompensa' => 'required|integer|min:0',
            'respostas' => 'required|array|min:2',
            'respostas.*' => 'required|string|max:255',
            'correta' => 'required|integer',
        ]);

        DB::transaction(function () use ($request, $quiz) {
            $pergunta = $quiz->perguntas()->create([
                'texto_pergunta' => $request->texto_pergunta,
                'coins_recompensa' => $request->coins_recompensa,
            ]);

            foreach ($request->respostas as $index => $textoResposta) {
                $pergunta->respostas()->create([
                    'texto_resposta' => $textoResposta,
                    'correta' => ($index == $request->correta),
                ]);
            }
        });

        return redirect()->route('admin.quizzes.edit', $quiz)->with('success', 'Pergunta adicionada com sucesso!');
    }

    /**
     * Mostra o formulário para editar uma pergunta existente.
     */
    public function edit(QuizPergunta $pergunta)
    {
        $dados['titulo_pagina'] = 'Editar Pergunta';
        $dados['pergunta'] = $pergunta->load('respostas', 'quiz');
        return view('admin.perguntas.edit', $dados);
    }

    /**
     * Atualiza a pergunta e suas respostas no banco de dados.
     */
    public function update(Request $request, QuizPergunta $pergunta)
    {
        $request->validate([
            'texto_pergunta' => 'required|string|max:255',
            'coins_recompensa' => 'required|integer|min:0',
            'respostas' => 'required|array|min:2',
            'respostas.*.texto' => 'required|string|max:255',
            'correta' => 'required|integer',
        ]);

        DB::transaction(function () use ($request, $pergunta) {
            $pergunta->update([
                'texto_pergunta' => $request->texto_pergunta,
                'coins_recompensa' => $request->coins_recompensa,
            ]);

            // Apaga as respostas antigas
            $pergunta->respostas()->delete();

            // Cria as novas respostas
            foreach ($request->respostas as $index => $respostaData) {
                $pergunta->respostas()->create([
                    'texto_resposta' => $respostaData['texto'],
                    'correta' => ($index == $request->correta),
                ]);
            }
        });

        return redirect()->route('admin.quizzes.edit', $pergunta->quiz_id)->with('success', 'Pergunta atualizada com sucesso!');
    }


    /**
     * Remove uma pergunta do banco de dados.
     */
    public function destroy(QuizPergunta $pergunta)
    {
        $quizId = $pergunta->quiz_id;
        $pergunta->delete();
        return redirect()->route('admin.quizzes.edit', $quizId)->with('success', 'Pergunta excluída com sucesso.');
    }
}