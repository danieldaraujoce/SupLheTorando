<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\UsuarioQuizProgresso; // Adicionado para clareza

class ProfileController extends Controller
{
    /**
     * Exibe o formulário de perfil do usuário com os dados calculados.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $nivel = $user->nivelAtual();

        // Lógica para contar missões concluídas
        $missoesConcluidas = $user->missoes()->wherePivot('status', 'concluida')->count();

        // --- CORREÇÃO APLICADA AQUI ---
        // Agora usando a sua estrutura de Models correta para contar os quizzes
        $quizzesConcluidos = UsuarioQuizProgresso::where('user_id', $user->id)
                                                 ->distinct('quiz_id')
                                                 ->count('quiz_id');

        return view('profile.edit', [
            'user' => $user,
            'nivel' => $nivel,
            'missoesConcluidas' => $missoesConcluidas,
            'quizzesConcluidos' => $quizzesConcluidos,
            'titulo_pagina' => 'Meu Perfil'
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->hasFile('avatar')) {
            // Apaga o avatar antigo, se existir
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            // --- CORREÇÃO APLICADA AQUI ---
            // Salva o novo avatar e guarda apenas o caminho relativo (ex: 'avatars/nome_do_arquivo.png')
            // O prefixo '/storage/' não deve ser salvo no banco de dados.
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Apaga a conta do usuário.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}