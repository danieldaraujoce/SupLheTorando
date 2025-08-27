@extends('admin.layouts.app')
@section('content')

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Gerenciar Caixa: {{ $caixa->nome }}</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.caixas-surpresa.index') }}">Caixas Surpresa /</a></li>
            <li class="text-sm font-medium text-primary">Gerenciar</li>
        </ol>
    </nav>
</div>

<div class="grid grid-cols-1 gap-9" x-data="gerenciadorDeItens()">
    {{-- Card: Formulário para Editar Dados da Caixa --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="mb-5 text-lg font-semibold text-black">Dados da Caixa</h3>
        <form action="{{ route('admin.caixas-surpresa.update', $caixa->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Nome da Caixa</label>
                    <input type="text" name="nome" value="{{ old('nome', $caixa->nome) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4" required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Descrição</label>
                    <textarea name="descricao" rows="1" class="w-full rounded-lg border border-gray-300 py-2.5 px-4">{{ old('descricao', $caixa->descricao) }}</textarea>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                    <input type="date" name="data_inicio" value="{{ old('data_inicio', $caixa->data_inicio?->format('Y-m-d')) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4" required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                    <input type="date" name="data_fim" value="{{ old('data_fim', $caixa->data_fim?->format('Y-m-d')) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4" required>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white">Salvar Dados da Caixa</button>
            </div>
        </form>
    </div>

    {{-- Card: Lista de Itens Atuais --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="mb-5 text-lg font-semibold text-black">Itens na Caixa</h3>
        <div class="flex flex-col">
            @php $totalChance = $caixa->itens->sum('chance_percentual'); @endphp
            @forelse ($caixa->itens as $item)
            <div class="grid grid-cols-5 gap-4 border-b border-gray-200 py-4 items-center">
                <div class="col-span-2">
                    <span class="font-medium text-black">{{ ucfirst(str_replace('_', ' ', $item->tipo_premio)) }}</span>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-600">
                        @if($item->tipo_premio == 'coins') {{ $item->valor_premio }} Moedas @endif
                        @if($item->tipo_premio == 'cupom') {{ $item->premio->codigo ?? 'N/A' }} @endif
                        @if($item->tipo_premio == 'produto') {{ $item->premio->nome ?? 'N/A' }} @endif
                        @if($item->tipo_premio == 'outro') {{ $item->nome_customizado }} @endif
                    </p>
                </div>
                <div class="col-span-1 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" @click="iniciarEdicao({{ $item->toJson() }})" class="rounded-lg bg-blue-600 py-2 px-3 text-xs font-medium text-white hover:bg-blue-700">Editar</button>
                        <form action="{{ route('admin.caixas-surpresa.itens.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remover este item?');">
                            @csrf @method('DELETE')
                            <button class="rounded-lg bg-red-600 py-2 px-3 text-xs font-medium text-white hover:bg-red-700">Remover</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="py-4 text-center text-gray-500">Nenhum item adicionado a esta caixa ainda.</p>
            @endforelse
            <div class="mt-4 text-right font-bold {{ $totalChance == 100 ? 'text-green-600' : 'text-orange-500' }}">
                Chance Total: {{ rtrim(rtrim(number_format($totalChance, 2), '0'), '.') }}%
                @if($totalChance != 100)
                <span class="text-sm font-normal">(Aviso: a soma ideal é 100%)</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Card: Formulário para Adicionar/Editar Itens --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-semibold text-black" x-text="tituloFormulario"></h3>
            <button x-show="modoEdicao" @click="cancelarEdicao()" class="rounded-lg bg-gray-500 py-2 px-4 text-sm font-medium text-white hover:bg-gray-600 transition-colors">Cancelar Edição</button>
        </div>
        
        <form :action="formAction" method="POST">
            @csrf
            <template x-if="modoEdicao">
                @method('PUT')
            </template>
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Tipo de Prêmio</label>
                    <select name="tipo_premio" x-model="item.tipo_premio" class="h-11 w-full rounded-lg border border-gray-300 px-4">
                        <option value="coins">Moedas (Coins)</option>
                        <option value="cupom">Cupom de Desconto</option>
                        <option value="produto">Produto Grátis</option>
                        <option value="outro">Outro (Prêmio customizado)</option>
                    </select>
                </div>
                <div>
                    <div x-show="item.tipo_premio === 'produto'">
                        <label class="mb-3 block text-sm font-medium text-black">Selecione o Produto</label>
                        <select name="premio_id_produto" x-model="item.premio_id" class="h-11 w-full rounded-lg border border-gray-300 px-4">
                            @foreach($produtos as $produto)<option value="{{ $produto->id }}">{{ $produto->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div x-show="item.tipo_premio === 'cupom'">
                        <label class="mb-3 block text-sm font-medium text-black">Selecione o Cupom</label>
                        <select name="premio_id_cupom" x-model="item.premio_id" class="h-11 w-full rounded-lg border border-gray-300 px-4">
                            @foreach($cupons as $cupom)<option value="{{ $cupom->id }}">{{ $cupom->codigo }}</option>@endforeach
                        </select>
                    </div>
                    <div x-show="item.tipo_premio === 'coins'">
                        <label class="mb-3 block text-sm font-medium text-black">Quantidade de Moedas</label>
                        <input type="number" name="valor_premio" x-model="item.valor_premio" class="h-11 w-full rounded-lg border border-gray-300 px-4">
                    </div>
                    <div x-show="item.tipo_premio === 'outro'">
                        <label class="mb-3 block text-sm font-medium text-black">Descreva o Prêmio Customizado</label>
                        <input type="text" name="nome_customizado" x-model="item.nome_customizado" class="h-11 w-full rounded-lg border border-gray-300 px-4">
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-3 block text-sm font-medium text-black">Chance de Sorteio (%)</label>
                    <input type="text" name="chance_percentual" x-model="item.chance_percentual" required placeholder="Ex: 25.5 para 25,5%" class="h-11 w-full rounded-lg border border-gray-300 px-4">
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white" x-text="textoBotao"></button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function gerenciadorDeItens() {
    return {
        modoEdicao: false,
        tituloFormulario: 'Adicionar Novo Item',
        textoBotao: 'Adicionar Item à Caixa',
        formAction: "{{ route('admin.caixas-surpresa.itens.store', $caixa->id) }}",
        item: { id: null, tipo_premio: 'coins', premio_id: null, valor_premio: null, chance_percentual: '', nome_customizado: '' },
        
        iniciarEdicao(itemData) {
            this.modoEdicao = true;
            this.tituloFormulario = 'Editar Item';
            this.textoBotao = 'Salvar Alterações';
            let updateUrl = "{{ route('admin.caixas-surpresa.itens.update', ':id') }}";
            this.formAction = updateUrl.replace(':id', itemData.id);
            this.item = { ...itemData }; 
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        },
        cancelarEdicao() {
            this.modoEdicao = false;
            this.tituloFormulario = 'Adicionar Novo Item';
            this.textoBotao = 'Adicionar Item à Caixa';
            this.formAction = "{{ route('admin.caixas-surpresa.itens.store', $caixa->id) }}";
            this.item = { id: null, tipo_premio: 'coins', premio_id: null, valor_premio: null, chance_percentual: '', nome_customizado: '' };
        }
    }
}
</script>
@endpush