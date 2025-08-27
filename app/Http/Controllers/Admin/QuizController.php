<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $dados['titulo_pagina'] = 'Gerenciador de Quizzes';
        $dados['quizzes'] = Quiz::withCount('perguntas')->paginate(10);
        return view('admin.quizzes.index', $dados);
    }

    public function create()
    {
        $dados['titulo_pagina'] = 'Novo Quiz';
        return view('admin.quizzes.create', $dados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $quiz = Quiz::create($request->all());
        return redirect()->route('admin.quizzes.edit', $quiz)->with('success', 'Quiz criado! Agora adicione as perguntas.');
    }

    public function edit(Quiz $quiz)
    {
        $dados['titulo_pagina'] = 'Gerenciar Perguntas do Quiz';
        $dados['quiz'] = $quiz->load('perguntas.respostas');
        return view('admin.quizzes.edit', $dados);
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
        ]);

        $quiz->update($request->all());
        
        return redirect()->route('admin.quizzes.edit', $quiz->id)->with('success', 'Quiz atualizado com sucesso!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz e todas as suas perguntas foram excluídos!');
    }
}