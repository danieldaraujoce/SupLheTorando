@extends('admin.layouts.app')

@section('content')
{{-- Cabeçalho da Página com Breadcrumb --}}
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">
        Adicionar Novo Destaque
    </h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="text-sm text-gray-500" href="{{ route('admin.destaques-home.index') }}">Destaques /</a></li>
            <li class="text-sm font-medium text-primary">Adicionar</li>
        </ol>
    </nav>
</div>

{{-- Container Principal do Formulário --}}
<div class="rounded-2xl border border-gray-200 bg-white p-6">
    <form action="{{ route('admin.destaques-home.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Exibição de Erros de Validação --}}
        @if ($errors->any())
        <div class="mb-6 rounded-md border border-red-400 bg-red-100 p-4 text-sm text-red-700">
            <p class="font-bold mb-2">Foram encontrados alguns erros:</p>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="flex flex-col gap-9">
            <div>
                <h4 class="mb-6 text-lg font-semibold text-gray-800">
                    Informações do Destaque
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="mb-3 block text-sm font-medium text-black">Título</label>
                        <input type="text" name="titulo" placeholder="Título do card de destaque"
                            value="{{ old('titulo') }}"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                            required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-3 block text-sm font-medium text-black">Subtítulo (Opcional)</label>
                        <input type="text" name="subtitulo" placeholder="Texto curto abaixo do título"
                            value="{{ old('subtitulo') }}"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-3 block text-sm font-medium text-black">Descrição</label>
                        <textarea name="descricao" rows="3" placeholder="Texto principal do card"
                            class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                            required>{{ old('descricao') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-3 block text-sm font-medium text-black">Imagem de Destaque</label>
                        <input type="file" name="imagem"
                            class="h-11 w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 shadow-theme-xs file:mr-4 file:h-full file:border-0 file:border-r file:border-solid file:border-gray-300 file:bg-gray-100 file:px-4"
                            required>
                    </div>
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Valor em Moedas (Opcional)</label>
                        <input type="number" name="valor_moedas" placeholder="Ex: 100" value="{{ old('valor_moedas') }}"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                    </div>
                    <div>
                        <label class="mb-3 block text-sm font-medium text-black">Ordem de Exibição</label>
                        <input type="number" name="ordem" value="{{ old('ordem', 0) }}"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
                    </div>
                     <div>
                        <label class="mb-3 block text-sm font-medium text-black">Status</label>
                        <select name="status" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10" required>
                            <option value="ativo" {{ old('status', 'ativo') == 'ativo' ? 'selected' : '' }}>Ativo (Visível no site)</option>
                            <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo (Oculto no site)</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Texto do Botão</label>
                            <input type="text" name="texto_botao" placeholder="Ex: Participe Agora"
                                value="{{ old('texto_botao') }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                        <div>
                            <label class="mb-3 block text-sm font-medium text-black">Link do Botão</label>
                            <input type="text" name="link_botao" placeholder="Ex: /register ou https://..."
                                value="{{ old('link_botao') }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10"
                                required>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botões de Ação --}}
            <div class="mt-9 flex justify-end gap-4">
                <a href="{{ route('admin.destaques-home.index') }}"
                    class="flex justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit"
                    class="flex justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90">
                    Salvar Destaque
                </button>
            </div>
        </div>
    </form>
</div>
@endsection