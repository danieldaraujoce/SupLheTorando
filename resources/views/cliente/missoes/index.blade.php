@extends('layouts.app')

@section('content')
<div x-data="{ tab: 'disponiveis' }">

    {{-- Título da Página --}}
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Central de Missões</h2>
        <p class="text-gray-600">Realize suas missões e ganhe moedas!</p>
    </div>


    {{-- Abas de Navegação --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            <button @click="tab = 'disponiveis'"
                :class="tab === 'disponiveis' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                Disponíveis
            </button>
            <button @click="tab = 'progresso'"
                :class="tab === 'progresso' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                Em Progresso
            </button>
            <button @click="tab = 'concluidas'"
                :class="tab === 'concluidas' ? 'border-primary text-primary' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                Concluídas
            </button>
        </nav>
    </div>

    {{-- Conteúdo das Abas --}}
    <div>
        {{-- Missões Disponíveis --}}
        <div x-show="tab === 'disponiveis'" class="space-y-4">
            @forelse ($missoesDisponiveis as $missao)
            <div class="bg-white rounded-2xl p-5 shadow-md flex flex-col">
                <div class="flex-grow">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-bullseye text-primary mt-1"></i>
                        <div>
                            <h3 class="font-bold text-lg text-black leading-tight">{{ $missao->titulo }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $missao->descricao }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 pt-5 border-t border-gray-200 flex items-center justify-between">
                    <span class="text-3xl font-bold text-primary">{{ $missao->coins_recompensa }} 🪙</span>
                    <form action="{{ route('cliente.missoes.aceitar', $missao->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="rounded-lg bg-primary py-3 px-6 font-bold text-white hover:bg-opacity-90 transition-transform hover:scale-105">Aceitar</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-16">
                <i class="fas fa-search fa-3x mb-3 text-gray-300"></i>
                <h3 class="text-lg font-medium">Nenhuma nova missão por aqui.</h3>
                <p class="text-sm">Volte em breve, aventureiro!</p>
            </div>
            @endforelse
        </div>

        {{-- Missões em Progresso --}}
        <div x-show="tab === 'progresso'" style="display: none;" class="space-y-4">
            @forelse ($missoesEmProgresso as $missao)
            <div class="bg-white rounded-2xl p-5 shadow-md flex flex-col">
                <div class="flex-grow">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-tasks text-primary mt-1"></i>
                        <div>
                            <h3 class="font-bold text-lg text-black leading-tight">{{ $missao->titulo }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $missao->descricao }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 pt-5 border-t border-gray-200">
                    @if($missao->tipo_missao == 'social')
                    <div class="text-right">
                        <a href="{{ route('cliente.missoes.comprovar', $missao->id) }}"
                            class="rounded-lg bg-primary py-2 px-4 font-medium text-white hover:bg-opacity-90">
                            Enviar Comprovação
                        </a>
                    </div>
                    @else
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 mb-1">Seu Progresso</p>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-primary h-2.5 rounded-full" style="width: 25%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-16">
                <i class="fas fa-stream fa-3x mb-3 text-gray-300"></i>
                <h3 class="text-lg font-medium">Nenhuma missão em progresso.</h3>
                <p class="text-sm">Vá para a aba "Disponíveis" e aceite um novo desafio!</p>
            </div>
            @endforelse
        </div>

        {{-- Missões Concluídas --}}
        <div x-show="tab === 'concluidas'" style="display: none;" class="space-y-4">
            @forelse ($missoesConcluidas as $missao)

            @if ($missao->pivot->status == 'concluida')
            {{-- CARD DE MISSÃO CONCLUÍDA --}}
            <div class="bg-white rounded-2xl p-5 shadow-md">
                <div class="flex justify-between items-center">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-green-500 mt-1"></i>
                        <div>
                            <h3 class="font-bold text-black">{{ $missao->titulo }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Concluída em {{ $missao->pivot->data_conclusao->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-green-500">+{{ $missao->coins_recompensa }} 🪙</span>
                </div>
            </div>
            @elseif ($missao->pivot->status == 'pendente_validacao')
            {{-- CARD DE MISSÃO PENDENTE --}}
            <div class="bg-white rounded-2xl p-5 shadow-md">
                <div class="flex justify-between items-center">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-hourglass-half text-yellow-500 mt-1"></i>
                        <div>
                            <h3 class="font-bold text-black">{{ $missao->titulo }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Aguardando validação...</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-yellow-500">{{ $missao->coins_recompensa }} 🪙</span>
                </div>
            </div>
            @endif

            @empty
            <div class="text-center text-gray-500 py-16">
                <i class="fas fa-trophy fa-3x mb-3 text-gray-300"></i>
                <h3 class="text-lg font-medium">Sua jornada está apenas começando.</h3>
                <p class="text-sm">Complete missões para vê-las aqui.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection