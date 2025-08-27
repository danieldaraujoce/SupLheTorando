<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pedido;
use Carbon\Carbon;

class CancelarPedidosAntigos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pedidos:cancelar-antigos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancela pedidos com status "aguardando_pagamento" com mais de 15 minutos.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limite = Carbon::now()->subMinutes(15);

        $pedidosParaCancelar = Pedido::where('status', 'aguardando_pagamento')
                                     ->where('created_at', '<=', $limite)
                                     ->get();

        if ($pedidosParaCancelar->isEmpty()) {
            $this->info('Nenhum pedido expirado encontrado.');
            return;
        }

        foreach ($pedidosParaCancelar as $pedido) {
            $pedido->status = 'cancelado';
            $pedido->save();
            $this->info("Pedido #{$pedido->id} cancelado por expiração.");
        }

        $this->info('Verificação de pedidos expirados concluída.');
    }
}