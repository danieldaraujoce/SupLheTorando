@extends('admin.layouts.app')
@section('content')

<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-2xl font-bold text-black">Configurações do Site</h2>
    <nav>
        <ol class="flex items-center gap-1.5">
            <li><a class="text-sm text-gray-500" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="text-sm font-medium text-primary">Configurações</li>
        </ol>
    </nav>
</div>
<form action="{{ route('admin.configuracoes.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    {{-- Contêiner único para toda a página --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-6">

        {{-- Cabeçalho do formulário --}}
        <div
            class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between mb-6 border-b border-gray-200 pb-6">
            <div class="flex w-full flex-col items-center gap-6 xl:flex-row">
                <div
                    class="h-20 w-20 flex items-center justify-center overflow-hidden rounded-full border border-gray-200 bg-gray-100">
                    @if(isset($settings['logo_marca']))
                    <img src="{{ asset('storage/' . $settings['logo_marca']) }}" alt="Logo Atual"
                        class="object-contain h-full w-full">
                    @else
                    <span class="text-xs text-gray-500">Logo</span>
                    @endif
                </div>
                <div>
                    <h4 class="mb-2 text-center text-lg font-semibold text-gray-800 xl:text-left">
                        {{ $settings['site_titulo'] ?? 'Título do Site' }}
                    </h4>
                    <p class="text-sm text-center xl:text-left text-gray-500">
                        Configurações gerais e de identidade do site.
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.destaques-home.index') }}"
                class="flex w-full justify-center rounded-lg bg-primary px-5 py-2.5 text-sm font-medium text-white hover:bg-opacity-90 lg:w-auto whitespace-nowrap">
                Gerenciar Destaques
            </a>
        </div>

        {{-- Seção de Campos Gerais --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mt-9">
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Título do Site</label>
                <input type="text" name="site_titulo" value="{{ $settings['site_titulo'] ?? '' }}"
                    placeholder="Ex: Supermercado Gamifica"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
            </div>

            <div>
                <label class="mb-3 block text-sm font-medium text-black">Logo Marca (PNG ou SVG)</label>
                <input type="file" name="logo_marca"
                    class="h-11 w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 shadow-theme-xs file:mr-4 file:h-full file:border-0 file:border-r file:border-solid file:border-gray-300 file:bg-gray-100 file:px-4">
            </div>

            <div class="md:col-span-2">
                <label class="mb-3 block text-sm font-medium text-black">Descrição do Site (para SEO)</label>
                <textarea name="site_descricao" rows="3"
                    placeholder="Descreva seu site em poucas palavras para os motores de busca."
                    class="w-full rounded-lg border border-gray-300 bg-transparent py-3 px-5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">{{ $settings['site_descricao'] ?? '' }}</textarea>
            </div>

            <div>
                <label class="mb-3 block text-sm font-medium text-black">Imagem de Compartilhamento (Open Graph)</label>
                 <input type="file" name="og_image"
                    class="h-11 w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 shadow-theme-xs file:mr-4 file:h-full file:border-0 file:border-r file:border-solid file:border-gray-300 file:bg-gray-100 file:px-4">
                <p class="text-xs text-gray-500 mt-2">Recomendado: 1200x630px. Usada ao compartilhar o link em redes sociais.</p>
                @if(isset($settings['og_image']))
                    <img src="{{ asset('storage/' . $settings['og_image']) }}" alt="Imagem de compartilhamento atual" class="mt-4 h-24 object-contain rounded-lg border border-gray-200">
                @endif
            </div>

            {{-- CORREÇÃO: Adicionado campo para o Favicon --}}
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Favicon do Site</label>
                 <input type="file" name="site_favicon"
                    class="h-11 w-full cursor-pointer rounded-lg border border-gray-300 bg-transparent text-sm text-gray-800 shadow-theme-xs file:mr-4 file:h-full file:border-0 file:border-r file:border-solid file:border-gray-300 file:bg-gray-100 file:px-4">
                <p class="text-xs text-gray-500 mt-2">Arquivo .ico, .png ou .svg. Recomendado: 32x32px.</p>
                @if(isset($settings['site_favicon']))
                    <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon atual" class="mt-4 h-8 w-8 object-contain rounded-lg border border-gray-200">
                @endif
            </div>

            <div class="md:col-span-2">
                <label class="mb-3 block text-sm font-medium text-black">Texto do Copyright</label>
                <input type="text" name="copyright"
                    value="{{ $settings['copyright'] ?? '© ' . date('Y') . ' Supermercado Gamifica. Todos os direitos reservados.' }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
            </div>
        </div>

        {{-- Seção de Redes Sociais --}}
        <h4 class="mb-6 mt-9 text-lg font-semibold text-gray-800">
            Redes Sociais
        </h4>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Link do Facebook</label>
                <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}"
                    placeholder="https://facebook.com/seu-usuario"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">Link do Instagram</label>
                <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}"
                    placeholder="https://instagram.com/seu-usuario"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
            </div>
            <div>
                <label class="mb-3 block text-sm font-medium text-black">WhatsApp Suporte (Link Completo)</label>
                <input type="url" name="whatsapp_suporte" value="{{ $settings['whatsapp_suporte'] ?? '' }}"
                    placeholder="Ex: https://wa.me/5588999999999"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-3 focus:ring-brand-500/10">
            </div>
        </div>

        {{-- Botão de Salvar --}}
        <div class="mt-9 flex justify-end">
            <button type="submit"
                class="flex w-full justify-center rounded-lg bg-primary px-5 py-2.5 font-medium text-white hover:bg-opacity-90 sm:w-auto">
                Salvar Configurações
            </button>
        </div>

    </div>
</form>
@endsection