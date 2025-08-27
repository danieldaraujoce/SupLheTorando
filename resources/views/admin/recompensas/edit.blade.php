@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Editar Recompensa</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.recompensas.index') }}">Recompensas /</a></li>
            <li class="text-sm font-medium text-primary">Editar</li>
        </ol>
    </nav>
</div>

@if ($errors->any())
<div class="mb-6 rounded-md border border-red-400 bg-red-100 p-4 text-sm text-red-700">
    <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

{{-- Card Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.recompensas.update', $recompensa->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-9 lg:grid-cols-3">
            {{-- Coluna Principal (Esquerda) --}}
            <div class="lg:col-span-2 flex flex-col gap-9">
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Informações da Recompensa</h3>
                    <div class="mb-6">
                        <label class="mb-3 block text-sm font-medium text-black">Nome da Recompensa</label>
                        <input type="text" name="nome" value="{{ old('nome', $recompensa->nome) }}"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                            required>
                    </div>
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Descrição</label>
                        <textarea name="descricao" rows="4"
                            class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">{{ old('descricao', $recompensa->descricao) }}</textarea>
                    </div>
                </div>
            </div>
            {{-- Coluna da Direita --}}
            <div class="lg:col-span-1 flex flex-col gap-9">
                <div>
                    <h3 class="mb-6 text-lg font-semibold text-black">Regras de Resgate</h3>
                    <div class="flex flex-col gap-6">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Custo em Moedas</label>
                            <input type="number" name="custo_coins"
                                value="{{ old('custo_coins', $recompensa->custo_coins) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Nível Mínimo para Resgate</label>
                            <select name="nivel_necessario_id"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                                <option value="">Qualquer Nível</option>
                                @foreach($niveis as $nivel)
                                <option value="{{ $nivel->id }}"
                                    {{ old('nivel_necessario_id', $recompensa->nivel_necessario_id) == $nivel->id ? 'selected' : '' }}>
                                    {{ $nivel->nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Estoque (Opcional)</label>
                            <input type="number" name="estoque" value="{{ old('estoque', $recompensa->estoque) }}"
                                placeholder="Deixe em branco para ilimitado"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botões de Ação --}}
        <div class="mt-9 flex justify-end gap-4 border-t border-gray-200 pt-6">
            <a href="{{ route('admin.recompensas.index') }}"
                class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">Cancelar</a>
            <button type="submit"
                class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">Atualizar
                Recompensa</button>
        </div>
    </form>
</div>
@endsection