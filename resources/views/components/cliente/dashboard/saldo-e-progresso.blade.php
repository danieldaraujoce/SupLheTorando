@props(['nivelUsuario', 'proximoNivelNome', 'progressoPercentual'])

<div class="bg-primary text-white rounded-2xl p-6 shadow-lg mb-8">
    {{-- Seção Superior: Saldo e Nível --}}
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm opacity-80">Seu Saldo</p>
            <p class="text-4xl font-bold pt-4 flex items-center gap-2">
                <i class="fas fa-coins text-yellow-300"></i> {{ number_format(auth()->user()->coins, 0, ',', '.') }}
            </p>
        </div>
        
        @if ($nivelUsuario)
            <div class="text-right">
                <p class="text-sm mr-4 opacity-80">Nível Atual</p>
                <div class="mt-1">
                    <span class="font-bold text-lg bg-white/20 text-white py-1 px-3 rounded-full">
                        {{ $nivelUsuario->nome }}
                    </span>
                </div>
            </div>
        @endif
    </div>
   
    {{-- Seção Inferior: Barra de Progresso --}}
    <div class="mt-4">
        <div class="flex justify-between text-xs opacity-80 mb-1">
            <span>Progresso para o próximo nível</span>
            <span>{{ $proximoNivelNome }}</span>
        </div>
        <div class="h-2.5 w-full rounded-full bg-black/20">
            <div class="h-2.5 rounded-full bg-yellow-300 transition-all duration-500" style="width: {{ $progressoPercentual }}%"></div>
        </div>
    </div>
</div>