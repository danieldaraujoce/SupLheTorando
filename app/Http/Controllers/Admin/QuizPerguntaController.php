<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizPergunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizPerguntaController extends Controller
{
    public function create(Quiz $quiz)
    {
        $dados['titulo_pagina'] = 'Nova Pergunta';
        $dados['quiz'] = $quiz;
        return view('admin.perguntas.create', $dados);
    }

    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'texto_pergunta' => 'required|string',
            'coins_recompensa' => 'required|integer|min:0',
            'respostas' => 'required|array|min:2',
            'respostas.*' => 'required|string',
            'correta' => 'required|integer',
        ]);

        DB::transaction(function () use ($request, $quiz) {
            $pergunta = $quiz->perguntas()->create($request->only('texto_pergunta', 'coins_recompensa'));

            foreach ($request->respostas as $index => $textoResposta) {
                if (!empty($textoResposta)) {
                    $pergunta->respostas()->create([
                        'texto_resposta' => $textoResposta,
                        'correta' => ($index == $request->correta),
                    ]);
                }
            }
        });

        return redirect()->route('admin.quizzes.edit', $quiz)->with('success', 'Pergunta adicionada com sucesso!');
    }

    public function edit(QuizPergunta $pergunta)
    {
        $dados['titulo_pagina'] = 'Editar Pergunta';
        $dados['pergunta'] = $pergunta->load('respostas', 'quiz');
        return view('admin.perguntas.edit', $dados);
    }

    public function update(Request $request, QuizPergunta $pergunta)
    {
        $request->validate([
            'texto_pergunta' => 'required|string',
            'coins_recompensa' => 'required|integer|min:0',
            'respostas' => 'required|array|min:2',
            // Validação corrigida para esperar um array de strings
            'respostas.*' => 'required|string',
            'correta' => 'required|integer',
        ]);

        DB::transaction(function () use ($request, $pergunta) {
            // Atualiza a pergunta
            $pergunta->update($request->only('texto_pergunta', 'coins_recompensa'));

            // Apaga as respostas antigas
            $pergunta->respostas()->delete();

            // Cria as novas respostas
            foreach ($request->respostas as $index => $textoResposta) {
                 if (!empty($textoResposta)) {
                    $pergunta->respostas()->create([
                        'texto_resposta' => $textoResposta,
                        // LÓGICA CORRIGIDA: Compara o $index com o valor enviado
                        'correta' => ($index == $request->correta),
                    ]);
                }
            }
        });

        return redirect()->route('admin.quizzes.edit', $pergunta->quiz_id)->with('success', 'Pergunta atualizada com sucesso!');
    }

    public function destroy(QuizPergunta $pergunta)
    {
        $quizId = $pergunta->quiz_id;
        $pergunta->delete();
        return redirect()->route('admin.quizzes.edit', $quizId)->with('success', 'Pergunta excluída com sucesso.');
    }
}