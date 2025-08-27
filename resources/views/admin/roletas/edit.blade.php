@extends('admin.layouts.app')
@section('content')

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Gerenciar Roleta: {{ $roleta->titulo }}</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.roletas.index') }}">Roletas /</a></li>
            <li class="text-sm font-medium text-primary">Gerenciar</li>
        </ol>
    </nav>
</div>

<div class="grid grid-cols-1 gap-9" x-data="gerenciadorDeFatias()">
    {{-- Card: Formulário para Editar Dados da Roleta --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="mb-5 text-lg font-semibold text-black">Dados da Roleta</h3>
        <form action="{{ route('admin.roletas.update', $roleta->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Título da Roleta</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $roleta->titulo) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4" required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Descrição</label>
                    <textarea name="descricao" rows="1" class="w-full rounded-lg border border-gray-300 py-2.5 px-4">{{ old('descricao', $roleta->descricao) }}</textarea>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Início</label>
                    <input type="date" name="data_inicio" value="{{ old('data_inicio', $roleta->data_inicio?->format('Y-m-d')) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4" required>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Data de Fim</label>
                    <input type="date" name="data_fim" value="{{ old('data_fim', $roleta->data_fim?->format('Y-m-d')) }}" class="h-11 w-full rounded-lg border border-gray-300 px-4" required>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white">Salvar Dados da Roleta</button>
            </div>
        </form>
    </div>

    {{-- Card: Lista de Fatias Atuais --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6">
        <h3 class="mb-5 text-lg font-semibold text-black">Fatias na Roleta</h3>
        <div class="flex flex-col">
            @php $totalChance = $roleta->itens->sum('chance_percentual'); @endphp
            @forelse ($roleta->itens as $item)
            <div class="grid grid-cols-6 gap-4 border-b border-gray-200 py-4 items-center">
                <div class="col-span-2 flex items-center gap-3">
                    <div class="h-5 w-5 rounded-sm flex-shrink-0" style="background-color: {{ $item->cor_slice }}"></div>
                    <span class="font-medium text-black">{{ $item->descricao }}</span>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-600">
                        {{-- Lógica para exibir o prêmio --}}
                        @if($item->tipo_premio == 'coins') {{ $item->valor_premio }} Moedas @endif
                        @if($item->tipo_premio == 'giro_extra') {{ $item->valor_premio }} Giro(s) Extra @endif
                        @if($item->tipo_premio == 'cupom') Cupom: {{ $item->cupom->codigo ?? 'N/A' }} @endif
                        @if($item->tipo_premio == 'produto') Produto: {{ $item->produto->nome ?? 'N/A' }} @endif
                        @if($item->tipo_premio == 'outro') {{ $item->nome_customizado }} @endif
                        @if($item->tipo_premio == 'nada') Sem prêmio @endif
                    </p>
                </div>
                <div class="col-span-1">
                    <p class="text-sm text-gray-800">Chance: <span class="font-bold">{{ rtrim(rtrim(number_format($item->chance_percentual, 2), '0'), '.') }}%</span></p>
                </div>
                <div class="col-span-1 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" @click="iniciarEdicao({{ $item->toJson() }})" class="rounded-lg bg-blue-600 py-2 px-3 text-xs font-medium text-white hover:bg-blue-700">Editar</button>
                        
                        <form action="{{ route('admin.roletas.itens.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remover esta fatia?');">
                            @csrf @method('DELETE')
                            <button class="rounded-lg bg-red-600 py-2 px-3 text-xs font-medium text-white hover:bg-red-700">Remover</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="py-4 text-center text-gray-500">Nenhuma fatia adicionada a esta roleta ainda.</p>
            @endforelse
            <div class="mt-4 text-right font-bold {{ $totalChance == 100 ? 'text-green-600' : 'text-orange-500' }}">
                Chance Total: {{ rtrim(rtrim(number_format($totalChance, 2), '0'), '.') }}%
                @if($totalChance != 100)
                <span class="text-sm font-normal">(Aviso: a soma ideal é 100%)</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Card: Formulário para Adicionar/Editar Fatias --}}
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
                <div class="sm:col-span-2">
                    <label class="mb-3 block text-sm font-medium text-black">Descrição da Fatia</label>
                    <input type="text" name="descricao" x-model="fatia.descricao" required class="h-11 w-full rounded-lg border border-gray-300 px-4" placeholder="Ex: Prêmio Principal, Tente de Novo">
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Tipo de Prêmio</label>
                    <select name="tipo_premio" x-model="fatia.tipo_premio" class="h-11 w-full rounded-lg border border-gray-300 px-4">
                        <option value="coins">Moedas (Coins)</option>
                        <option value="cupom">Cupom de Desconto</option>
                        <option value="produto">Produto Grátis</option>
                        <option value="giro_extra">Giro Extra</option>
                        <option value="outro">Outro (Prêmio customizado)</option>
                        <option value="nada">Tente de Novo (Sem prêmio)</option>
                    </select>
                </div>
                <div>
                    <div x-show="fatia.tipo_premio === 'produto'">
                        <label class="mb-3 block text-sm font-medium text-black">Selecione o Produto</label>
                        <select name="premio_id" x-model="fatia.premio_id" class="h-11 w-full rounded-lg border border-gray-300 px-4">
                            @foreach($produtos as $produto)<option value="{{ $produto->id }}">{{ $produto->nome }}</option>@endforeach
                        </select>
                    </div>
                    <div x-show="fatia.tipo_premio === 'cupom'">
                        <label class="mb-3 block text-sm font-medium text-black">Selecione o Cupom</label>
                        <select name="premio_id_cupom" x-model="fatia.premio_id" class="h-11 w-full rounded-lg border border-gray-300 px-4">
                            @foreach($cupons as $cupom)<option value="{{ $cupom->id }}">{{ $cupom->codigo }}</option>@endforeach
                        </select>
                    </div>
                    <div x-show="fatia.tipo_premio === 'coins' || fatia.tipo_premio === 'giro_extra'">
                        <label class="mb-3 block text-sm font-medium text-black">Quantidade</label>
                        <input type="number" name="valor_premio" x-model="fatia.valor_premio" class="h-11 w-full rounded-lg border border-gray-300 px-4" placeholder="Ex: 100 (para 100 moedas)">
                    </div>
                    <div x-show="fatia.tipo_premio === 'outro'">
                        <label class="mb-3 block text-sm font-medium text-black">Descreva o Prêmio Customizado</label>
                        <input type="text" name="nome_customizado" x-model="fatia.nome_customizado" class="h-11 w-full rounded-lg border border-gray-300 px-4" placeholder="Ex: Brinde Especial da Loja">
                    </div>
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Chance de Sorteio (%)</label>
                    <input type="text" name="chance_percentual" x-model="fatia.chance_percentual" required class="h-11 w-full rounded-lg border border-gray-300 px-4" placeholder="Ex: 25.5 para 25,5%">
                </div>
                <div>
                    <label class="mb-3 block text-sm font-medium text-black">Cor da Fatia</label>
                    <input type="color" name="cor_slice" x-model="fatia.cor_slice" class="w-full h-11 rounded-lg border border-gray-300">
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
function gerenciadorDeFatias() {
    return {
        modoEdicao: false,
        tituloFormulario: 'Adicionar Nova Fatia',
        textoBotao: 'Adicionar Fatia',
        formAction: "{{ route('admin.roletas.itens.store', $roleta->id) }}",
        fatia: { id: null, descricao: '', tipo_premio: 'coins', premio_id: null, valor_premio: null, chance_percentual: '', cor_slice: '#EAB308', nome_customizado: '' },
        
        iniciarEdicao(item) {
            this.modoEdicao = true;
            this.tituloFormulario = 'Editar Fatia: ' + item.descricao;
            this.textoBotao = 'Salvar Alterações';
            let updateUrl = "{{ route('admin.roletas.itens.update', ':id') }}";
            this.formAction = updateUrl.replace(':id', item.id);
            this.fatia = { ...item };
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        },
        cancelarEdicao() {
            this.modoEdicao = false;
            this.tituloFormulario = 'Adicionar Nova Fatia';
            this.textoBotao = 'Adicionar Fatia';
            this.formAction = "{{ route('admin.roletas.itens.store', $roleta->id) }}";
            this.fatia = { id: null, descricao: '', tipo_premio: 'coins', premio_id: null, valor_premio: null, chance_percentual: '', cor_slice: '#EAB308', nome_customizado: '' };
        }
    }
}
</script>
@endpush