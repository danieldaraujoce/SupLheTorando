<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Se o usuário não estiver logado, o middleware 'auth' já o bloqueará antes deste.
        if (!Auth::check()) {
            return redirect('login');
        }

        // Pega o usuário logado
        $user = Auth::user();

        // Verifica se o 'nivel_acesso' do usuário está na lista de roles permitidas
        if (in_array($user->nivel_acesso, $roles)) {
            // Se estiver, permite que a requisição continue
            return $next($request);
        }

        // Se não tiver a permissão, redireciona para o dashboard padrão com um erro.
        // Futuramente, podemos redirecionar para uma página de "acesso negado".
        abort(403, 'Acesso Não Autorizado.');
    }
}