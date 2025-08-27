<!DOCTYPE html>
<html lang="pt-br" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Supermercado Gamifica</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="preconnect" href="https://rsms.me/">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <style>
    html {
        font-family: 'Inter', sans-serif;
    }

    [x-cloak] {
        display: none !important;
    }
    </style>
</head>

<body class="bg-gray-900 text-white flex flex-col min-h-screen">

    <header x-data="{ open: false }" class="sticky top-0 z-50 bg-gray-900/70 backdrop-blur-lg border-b border-gray-700">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-black flex items-center gap-2">
                @php $logoPath = \App\Helpers\SettingsHelper::get('logo_marca'); @endphp
                @if($logoPath)
                <img src="{{ Storage::url($logoPath) }}" alt="Logo" class="h-8">
                @else
                <i class="fas fa-rocket text-primary"></i> GAMIFICA
                @endif
            </a>
            <nav class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}"
                    class="text-gray-300 hover:text-white font-semibold transition-colors">Home</a>
                <a href="{{ route('login') }}" class="text-white font-bold transition-colors">Entrar</a>
                <a href="{{ route('register') }}"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">Criar
                    Conta</a>
            </nav>
            <div class="md:hidden">
                <button @click="open = !open" class="text-white focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m4 6H4" />
                    </svg>
                </button>
            </div>
        </div>
        <div x-show="open" x-cloak @click.away="open = false" class="md:hidden">
            <nav class="flex flex-col items-center space-y-4 py-4 border-t border-gray-700">
                <a href="{{ route('home') }}"
                    class="text-gray-300 hover:text-white font-semibold transition-colors">Home</a>
                <a href="{{ route('login') }}" class="text-white font-bold transition-colors">Entrar</a>
                <a href="{{ route('register') }}"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition-colors w-11/12 text-center">Criar
                    Conta</a>
            </nav>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md p-8 space-y-8 bg-gray-800 rounded-2xl shadow-lg border border-gray-700">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-white">Bem-vindo(a) de volta!</h2>
                <p class="text-gray-400">Faça login para continuar sua jornada de prêmios.</p>
            </div>

            @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-500">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-3 px-4 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300">Senha</label>
                    <input id="password" type="password" name="password" required
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-3 px-4 text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded bg-gray-700 border-gray-600 text-indigo-600" name="remember">
                        <span class="ms-2 text-sm text-gray-400">Lembrar-me</span>
                    </label>
                    <a class="underline text-sm text-gray-400 hover:text-gray-200"
                        href="{{ route('password.request') }}">
                        Esqueceu sua senha?
                    </a>
                </div>
                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700">
                        ENTRAR
                    </button>
                </div>
            </form>
            <p class="text-center text-sm text-gray-400">
                Não tem uma conta?
                <a href="{{ route('register') }}" class="font-medium text-indigo-400 hover:text-indigo-300">
                    Cadastre-se
                </a>
            </p>
        </div>
    </main>

    <footer class="bg-gray-900">
        <div class="container mx-auto px-6 py-8 text-center text-gray-500">
            <p>&copy; {{ date('Y') }} Supermercado Gamifica. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>

</html>