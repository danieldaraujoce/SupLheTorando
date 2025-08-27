<?php

namespace App\Http\Controllers\Cliente;

use App\Events\AcaoDoUsuarioRealizada;
use App\Http\Controllers\Controller;
use App\Models\Cupom;
use App\Models\User;
use App\Models\Transacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CupomResgatadoNotification;

class CupomController extends Controller
{
    public function resgatar(Request $request, Cupom $cupom)
    {
        $usuario = Auth::user();
        
        // --- LÓGICA DE VALIDAÇÃO SIMPLIFICADA ---
        // 1. O cupom ainda existe na lista de cupons ativos?
        // Esta única verificação substitui toda a lógica manual de datas, pois o scope 'ativos' já faz isso.
        $cupomAindaValido = Cupom::ativos()->where('id', $cupom->id)->exists();
        if (!$cupomAindaValido) {
            return back()->with('error', 'Este cupom não está mais disponível.');
        }

        // 2. O usuário já resgatou este cupom?
        $jaResgatou = $usuario->cuponsResgatados()->where('cupom_id', $cupom->id)->exists();
        if ($jaResgatou) {
            return back()->with('error', 'Você já resgatou este cupom.');
        }

        // 3. O cupom atingiu o limite de uso total?
        if ($cupom->limite_uso_total) {
            $usos = $cupom->usuariosQueResgataram()->count();
            if ($usos >= $cupom->limite_uso_total) {
                return back()->with('error', 'Este cupom atingiu o limite de resgates.');
            }
        }

        // Se todas as validações passarem, continua o processo.
        $usuario->cuponsResgatados()->attach($cupom->id);
        
        AcaoDoUsuarioRealizada::dispatch($usuario, 'resgatar_cupom', $cupom);
        $usuario->notify(new CupomResgatadoNotification($cupom));
        
        if ($cupom->coins_extra_fidelidade > 0) {
            $recompensa = $cupom->coins_extra_fidelidade;
            $usuario->increment('coins', $recompensa);
            $usuario->increment('total_coins_acumulados', $recompensa);

            Transacao::create([
                'usuario_id' => $usuario->id,
                'tipo' => 'credito',
                'valor' => $recompensa,
                'descricao' => 'Moedas extras pelo cupom: ' . $cupom->codigo
            ]);

            return back()->with('success', "Cupom '{$cupom->codigo}' resgatado! Você ganhou {$recompensa} moedas extras!");
        }

        return back()->with('success', "Cupom '{$cupom->codigo}' resgatado com sucesso!");
    }
}