<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        $settings = \App\Helpers\SettingsHelper::getMany(['site_titulo', 'site_descricao', 'logo_marca', 'og_image', 'site_favicon']);
        
        $titulo = $settings['site_titulo'] ?? 'Supermercado Gamifica';
        $descricao = $settings['site_descricao'] ?? 'Participe de missões, responda quizzes e transforme suas compras em prêmios incríveis.';
        $logoUrl = isset($settings['logo_marca']) ? Storage::url($settings['logo_marca']) : null;
        $ogImageUrl = isset($settings['og_image']) ? Storage::url($settings['og_image']) : $logoUrl;

        $faviconPath = $settings['site_favicon'] ?? null;
        $faviconUrl = $faviconPath ? Storage::url($faviconPath) : null;
        $cacheBuster = ($faviconPath && Storage::disk('public')->exists($faviconPath))
                        ? '?v=' . Storage::disk('public')->lastModified($faviconPath)
                        : '';
    @endphp

    <title>{{ $titulo }}</title>
    
    @if($faviconUrl)
    <link rel="icon" href="{{ url($faviconUrl . $cacheBuster) }}">
    <link rel="apple-touch-icon" href="{{ url($faviconUrl . $cacheBuster) }}">
    @endif
    
    <meta name="description" content="{{ $descricao }}">
    <meta property="og:title" content="{{ $titulo }}" />
    <meta property="og:description" content="{{ $descricao }}" />
    @if($ogImageUrl)
    <meta property="og:image" content="{{ url($ogImageUrl) }}" />
    @endif
    <meta property="og:type" content="website" />

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <style>
    html {
        font-family: 'Inter', sans-serif;
        scroll-behavior: smooth;
    }
    .text-shadow {
        text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.4);
    }
    [x-cloak] {
        display: none !important;
    }
    </style>
</head>

<body class="bg-gray-900 text-white">

    <header x-data="{ open: false }" class="sticky top-0 z-50 bg-gray-900/70 backdrop-blur-lg border-b border-gray-700">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold text-white flex items-center gap-3">
                @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo {{ $titulo }}" class="h-8 max-w-[150px]">
                @else
                <i class="fas fa-rocket text-yellow-400"></i>
                <span>{{ $titulo }}</span>
                @endif
            </a>

            <nav class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}"
                    class="text-gray-300 hover:text-white font-semibold transition-colors">Home</a>
                @auth
                <a href="{{ url('/dashboard') }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-4 rounded-lg transition-colors">Meu
                    Painel</a>
                @else
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors">Entrar</a>
                <a href="{{ route('register') }}"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">Criar
                    Conta</a>
                @endauth
            </nav>

            <div class="md:hidden">
                <button @click="open = !open" class="text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="open" x-cloak @click.away="open = false" x-transition class="md:hidden bg-gray-800">
            <nav class="flex flex-col items-center space-y-4 py-4">
                <a href="{{ route('home') }}"
                    class="text-gray-300 hover:text-white font-semibold transition-colors">Home</a>
                @auth
                <a href="{{ url('/dashboard') }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-2 px-6 rounded-lg transition-colors w-11/12 text-center">Meu
                    Painel</a>
                @else
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors">Entrar</a>
                <a href="{{ route('register') }}"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition-colors w-11/12 text-center">Criar
                    Conta</a>
                @endauth
            </nav>
        </div>
    </header>
    <section class="relative h-[80vh] flex items-center justify-center text-center bg-cover bg-center"
        style="background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1974&auto=format&fit=crop');">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10 px-6">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white text-shadow leading-tight">
                Suas Compras Valem <span class="text-yellow-400">Muito Mais</span>.
            </h1>
            <p class="mt-4 text-lg md:text-xl text-gray-200 max-w-2xl mx-auto text-shadow">
                {{ $descricao }}
            </p>
            <a href="{{ route('register') }}"
                class="mt-8 inline-block bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white font-bold py-3 px-8 rounded-full text-lg shadow-xl transition-transform hover:scale-105">
                Comece a Ganhar Agora
            </a>
        </div>
    </section>

    <section id="como-funciona" class="py-20 bg-gray-800">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-2">É simples, rápido e divertido!</h2>
            <p class="text-gray-400 mb-12">Em apenas 3 passos você já está ganhando.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="flex flex-col items-center">
                    <div
                        class="w-24 h-24 mb-6 flex items-center justify-center bg-gray-700 rounded-full text-green-400 text-4xl">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">1. Participe das Missões</h3>
                    <p class="text-gray-400">Ative missões no app, como "Compre 3 sucos da marca X" ou "Gaste R$50 em
                        hortifruti".</p>
                </div>
                <div class="flex flex-col items-center">
                    <div
                        class="w-24 h-24 mb-6 flex items-center justify-center bg-gray-700 rounded-full text-yellow-400 text-4xl">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">2. Ganhe Coins</h3>
                    <p class="text-gray-400">Ao realizar suas compras normalmente, nosso sistema valida suas missões e
                        credita os coins na sua conta.</p>
                </div>
                <div class="flex flex-col items-center">
                    <div
                        class="w-24 h-24 mb-6 flex items-center justify-center bg-gray-700 rounded-full text-blue-400 text-4xl">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">3. Troque por Prêmios</h3>
                    <p class="text-gray-400">Use seus coins para resgatar produtos grátis, descontos exclusivos e cupons
                        diretamente no caixa.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-900">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center mb-12">Missões e Prêmios em Destaque</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                @forelse ($destaques as $destaque)
                <div
                    class="bg-gray-800 rounded-xl shadow-lg overflow-hidden flex flex-col transition-transform hover:scale-105">
                    <img src="{{ $destaque->image_url }}" alt="{{ $destaque->titulo }}"
                        class="w-full h-40 object-cover">
                    <div class="p-6 flex-grow">
                        <h3 class="text-lg font-bold">{{ $destaque->titulo }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ $destaque->subtitulo }}</p>
                        <p class="text-sm text-gray-400 mt-2">{{ $destaque->descricao }}</p>
                    </div>
                    <div class="p-4 bg-gray-900/50 border-t border-gray-700 flex justify-between items-center">
                        @if($destaque->valor_moedas)
                        <p class="text-lg font-bold text-yellow-400">{{ $destaque->valor_moedas }} 🪙</p>
                        @else
                        <span></span>
                        @endif
                        <a href="{{ $destaque->link_botao }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2 px-3 rounded-lg transition-colors" target="_blank">
                            {{ $destaque->texto_botao }}
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-4 text-center py-10">
                    <p class="text-gray-400">Nenhuma missão ou prêmio em destaque no momento. Volte em breve!</p>
                </div>
                @endforelse

            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-800">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold mb-4">Pronto para transformar suas compras?</h2>
            <p class="text-gray-400 mb-8">Crie sua conta gratuitamente e comece a acumular prêmios hoje mesmo.</p>
            <a href="{{ route('register') }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-bold py-3 px-8 rounded-full text-lg shadow-xl transition-transform hover:scale-105">
                Quero Participar!
            </a>
        </div>
    </section>

    <footer class="bg-gray-900">
        <div class="container mx-auto px-6 py-8 text-center text-gray-500">
            {{-- CORREÇÃO APLICADA AQUI --}}
            @php
                // Busca também a configuração do WhatsApp de suporte
                $socialLinks = \App\Helpers\SettingsHelper::getMany(['social_facebook', 'social_instagram', 'whatsapp_suporte']);
            @endphp

            @if(!empty(array_filter($socialLinks)))
            <div class="flex justify-center items-center space-x-6 mb-6">
                @if(!empty($socialLinks['social_facebook']))
                <a href="{{ $socialLinks['social_facebook'] }}" target="_blank"
                    class="text-2xl text-gray-400 hover:text-blue-500 transition-colors">
                    <i class="fab fa-facebook-f"></i>
                </a>
                @endif
                @if(!empty($socialLinks['social_instagram']))
                <a href="{{ $socialLinks['social_instagram'] }}" target="_blank"
                    class="text-2xl text-gray-400 hover:text-pink-500 transition-colors">
                    <i class="fab fa-instagram"></i>
                </a>
                @endif
                {{-- Adiciona o link do WhatsApp se ele estiver configurado --}}
                @if(!empty($socialLinks['whatsapp_suporte']))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socialLinks['whatsapp_suporte']) }}" target="_blank"
                    class="text-2xl text-gray-400 hover:text-green-500 transition-colors">
                    <i class="fab fa-whatsapp"></i>
                </a>
                @endif
            </div>
            @endif
            
            <p class="text-sm">
                {!! \App\Helpers\SettingsHelper::get('copyright', '&copy; ' . date('Y') . ' ' . $titulo . '. Todos os direitos reservados.') !!}
            </p>
            <div class="mt-4 space-x-6 text-xs">
                <a href="#" class="hover:text-white transition-colors">Termos de Uso</a>
                <span>&bull;</span>
                <a href="#" class="hover:text-white transition-colors">Política de Privacidade</a>
            </div>
        </div>
    </footer>

</body>

</html>