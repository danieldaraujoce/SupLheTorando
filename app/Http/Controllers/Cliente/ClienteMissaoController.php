<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Missao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteMissaoController extends Controller
{
    /**
     * Exibe a central de missões para o cliente.
     */
    public function index()
    {
        $usuario = Auth::user();
        $dados['titulo_pagina'] = 'Central de Missões';
        $dados['usuario'] = $usuario; // Passando o usuário para o layout

        // Pega os IDs das missões que o usuário já aceitou
        $missoesAceitasIds = $usuario->missoes()->pluck('missoes.id');

        // Missões disponíveis = todas as ativas que o usuário AINDA NÃO aceitou
        $dados['missoesDisponiveis'] = Missao::where('status', 'ativa')
            ->whereNotIn('id', $missoesAceitasIds)
            ->where('data_fim', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();

        // Missões em progresso do usuário
        $dados['missoesEmProgresso'] = $usuario->missoes()
            ->wherePivot('status', 'em_progresso')
            ->orderBy('pivot_created_at', 'desc')
            ->get();

        // Missões concluídas do usuário
        $dados['missoesConcluidas'] = $usuario->missoes()
            ->wherePivotIn('status', ['concluida', 'pendente_validacao'])
            ->orderBy('pivot_data_conclusao', 'desc')
            ->get();
        
        return view('cliente.missoes.index', $dados);
    }

    /**
     * Lógica para o usuário aceitar uma nova missão.
     */
    public function aceitar(Missao $missao)
    {
        $usuario = Auth::user();

        // Verifica se o usuário já não aceitou esta missão
        if ($usuario->missoes()->where('missao_id', $missao->id)->exists()) {
            return back()->with('error', 'Você já aceitou esta missão.');
        }

        // O método attach() cria o registro na tabela pivot 'usuario_missoes'
        $usuario->missoes()->attach($missao->id, ['status' => 'em_progresso']);

        return back()->with('success', 'Missão aceita! Boa sorte, aventureiro!');
    }

    public function responder(Request $request, QuizPergunta $pergunta)
    {
        $request->validate(['resposta_id' => 'required|integer|exists:quiz_respostas,id']);

        $usuario = Auth::user();
        $respostaEscolhida = QuizResposta::find($request->resposta_id);

        if ($respostaEscolhida->pergunta_id !== $pergunta->id) {
            return back()->with('error', 'Resposta inválida.');
        }

        UsuarioQuizProgresso::create([
            'user_id' => $usuario->id,
            'quiz_id' => $pergunta->quiz_id,
            'quiz_pergunta_id' => $pergunta->id,
            'quiz_resposta_id' => $respostaEscolhida->id,
            'correta' => $respostaEscolhida->correta,
        ]);

        $quizId = $pergunta->quiz_id;

        if ($respostaEscolhida->correta) {
            $usuario->increment('coins', $pergunta->coins_recompensa);
            
            // MUDANÇA AQUI: Enviamos dados específicos para o splash screen
            return redirect()->route('cliente.quizzes.show', $quizId)
                             ->with('quiz_feedback', [
                                 'correta' => true,
                                 'coins' => $pergunta->coins_recompensa
                             ]);
        } else {
            // A mensagem de erro continua como um flash message padrão
            return redirect()->route('cliente.quizzes.show', $quizId)->with('error', 'Resposta incorreta. Continue tentando!');
        }
    }
}