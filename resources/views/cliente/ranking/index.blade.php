@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-gray-800">🏆 Ranking do Mês</h1>
        <p class="text-gray-500 mt-2">Veja quem está na frente na corrida por prêmios!</p>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6">
        @forelse ($ranking as $rank)
            <div class="flex items-center justify-between p-4 border-b last:border-b-0 {{ $loop->iteration <= 3 ? 'bg-yellow-50 rounded-lg' : '' }}">
                <div class="flex items-center gap-4">
                    <span class="font-bold text-lg text-gray-700 w-8 text-center">
                        @if ($loop->iteration == 1) 🥇
                        @elseif ($loop->iteration == 2) 🥈
                        @elseif ($loop->iteration == 3) 🥉
                        @else {{ $loop->iteration }}º
                        @endif
                    </span>
                    <div>
                        <p class="font-semibold text-black">{{ $rank->nome }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-primary">{{ number_format($rank->pontos_mes, 0, ',', '.') }} pts</p>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 py-8">Ainda não há ninguém no ranking este mês. Seja o primeiro!</p>
        @endforelse
    </div>
    
    <div class="mt-6">
        {{ $ranking->links() }}
    </div>
</div>
@endsection