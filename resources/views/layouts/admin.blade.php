<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    {{-- Título e Favicon Dinâmicos --}}
    @php
        $site_titulo = \App\Helpers\SettingsHelper::get('site_titulo', 'Gamifica');
        $faviconPath = \App\Helpers\SettingsHelper::get('site_favicon');
        $faviconUrl = $faviconPath ? Storage::url($faviconPath) : null;
    @endphp
    <title>{{ $titulo_pagina ?? 'Painel Admin' }} - {{ $site_titulo }}</title>
    @if($faviconUrl)
    <link rel="icon" href="{{ url($faviconUrl) }}">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ sidebarToggle: false }" class="bg-gray-100 font-sans">
    {{-- ESTRUTURA PRINCIPAL DO LAYOUT ADMIN --}}
    <div class="flex h-screen overflow-hidden">

        {{-- INCLUSÃO DA SIDEBAR --}}
        {{-- Aqui puxamos o menu lateral que já estilizámos --}}
        @include('admin.layouts.partials.sidebar')

        {{-- CONTAINER DO CONTEÚDO PRINCIPAL --}}
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            
            {{-- INCLUSÃO DO HEADER --}}
            {{-- Aqui puxamos o cabeçalho que já estilizámos --}}
            @include('admin.layouts.partials.header')

            {{-- ÁREA DE CONTEÚDO DA PÁGINA --}}
            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    
                    {{-- Mensagem de Sucesso --}}
                    @if (session('success'))
                    <div class="mb-4 rounded-md border border-green-400 bg-green-100 p-4 text-sm text-green-700" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    {{-- O @yield('content') é onde o conteúdo de cada página específica (dashboard, cupons, etc.) será injetado --}}
                    @yield('content')
                </div>
            </main>
            
        </div>
        {{-- FIM DO CONTAINER DE CONTEÚDO --}}

    </div>
    {{-- FIM DA ESTRUTURA PRINCIPAL --}}

    {{-- O @stack('scripts') permite que páginas específicas adicionem seus próprios scripts aqui --}}
    @stack('scripts')
</body>

</html>