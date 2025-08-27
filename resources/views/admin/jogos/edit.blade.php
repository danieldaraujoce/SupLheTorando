@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Gerenciar Jogo: {{ $jogo->nome }}</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.jogos.index') }}">Jogue e Ganhe /</a></li>
            <li class="text-sm font-medium text-primary">Gerenciar</li>
        </ol>
    </nav>
</div>

<div class="grid grid-cols-1 gap-9">
    {{-- Card: Lista de Prêmios Atuais --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="mb-5 text-lg font-semibold text-black">Prêmios do Jogo</h3>
        <div class="flex flex-col">
            @php $totalChance = $jogo->premios->sum('chance_percentual'); @endphp
            @forelse ($jogo->premios as $premio)
            <div class="grid grid-cols-5 gap-4 border-b border-gray-200 py-4 items-center">
                <div class="col-span-2"><span class="font-medium text-black">{{ $premio->descricao_premio }}</span>
                </div>
                <div class="col-span-1">
                    <p class="text-gray-600">{{ ucfirst($premio->tipo_premio) }}</p>
                </div>
                <div class="col-span-1">
                    <p class="text-sm text-gray-800">Chance: <span
                            class="font-bold">{{ rtrim(rtrim(number_format($premio->chance_percentual, 2), '0'), '.') }}%</span>
                    </p>
                </div>
                <div class="col-span-1 text-right">
                    <form action="{{ route('admin.jogos.premios.destroy', $premio->id) }}" method="POST"
                        onsubmit="return confirm('Remover este prêmio?');">
                        @csrf @method('DELETE')
                        <button
                            class="rounded-lg bg-red-600 py-2 px-3 text-xs font-medium text-white hover:bg-red-700">Remover</button>
                    </form>
                </div>
            </div>
            @empty
            <p class="py-4 text-center text-gray-500">Nenhum prêmio adicionado a este jogo ainda.</p>
            @endforelse
            <div class="mt-4 text-right font-bold {{ $totalChance == 100 ? 'text-green-600' : 'text-orange-500' }}">
                Chance Total: {{ rtrim(rtrim(number_format($totalChance, 2), '0'), '.') }}%
                @if($totalChance != 100)
                <span class="text-sm font-normal">(Aviso: a soma ideal é 100%)</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Card: Formulário para Adicionar Novo Prêmio --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6" x-data="{ tipo: 'coins' }">
        <h3 class="mb-5 text-lg font-semibold text-black">Adicionar Novo Prêmio</h3>
        <form action="{{ route('admin.jogos.premios.store', $jogo->id) }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="mb-3 block text-sm font-medium text-black">Descrição do Prêmio</label>
                    <input type="text" name="descricao_premio" placeholder="Ex: 100 Moedas, Cupom Especial"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                        required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Tipo de Prêmio</label>
                    <select name="tipo_premio" x-model="tipo"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        <option value="coins">Moedas (Coins)</option>
                        <option value="cupom">Cupom de Desconto</option>
                        <option value="produto">Produto Grátis</option>
                    </select>
                </div>
                <div>
                    {{-- Campos Condicionais --}}
                    <div x-show="tipo === 'produto'">
                        <label class="mb-3 block text-sm font-medium text-black">Selecione o Produto</label>
                        <select name="premio_id"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                            @foreach($produtos as $produto)<option value="{{ $produto->id }}">{{ $produto->nome }}
                            </option>@endforeach
                        </select>
                    </div>
                    <div x-show="tipo === 'cupom'">
                        <label class="mb-3 block text-sm font-medium text-black">Selecione o Cupom</label>
                        <select name="premio_id_cupom"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                            @foreach($cupons as $cupom)<option value="{{ $cupom->id }}">{{ $cupom->codigo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div x-show="tipo === 'coins'">
                        <label class="mb-3 block text-sm font-medium text-black">Quantidade de Moedas</label>
                        <input type="number" name="valor_premio" placeholder="Ex: 100"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-3 block text-sm font-medium text-black">Chance de Sorteio (%)</label>
                    <input type="text" name="chance_percentual" placeholder="Ex: 25.5 (para 25,5%)"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                        required>
                </div>
            </div>
            <div class="mt-6 flex justify-end border-t border-gray-200 pt-6">
                <button type="submit"
                    class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                    Adicionar Prêmio
                </button>
            </div>
        </form>
    </div>
</div>
@endsection