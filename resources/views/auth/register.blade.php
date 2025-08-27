<!DOCTYPE html>
<html lang="pt-br" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Supermercado Gamifica</title>
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
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors">Entrar</a>
                <a href="{{ route('register') }}" class="text-white font-bold transition-colors">Criar Conta</a>
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
                <a href="{{ route('login') }}" class="text-gray-300 hover:text-white transition-colors">Entrar</a>
                <a href="{{ route('register') }}"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition-colors w-11/12 text-center">Criar
                    Conta</a>
            </nav>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md p-8 space-y-8 bg-gray-800 rounded-2xl shadow-lg border border-gray-700">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-white">Crie sua Conta</h2>
                <p class="text-gray-400">Comece sua jornada de prêmios agora mesmo!</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300">Nome Completo</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-3 px-4 text-white">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-3 px-4 text-white">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-300">WhatsApp</label>
                    <input id="whatsapp" type="text" name="whatsapp" value="{{ old('whatsapp') }}" required
                        placeholder="(XX) XXXXX-XXXX" maxlength="15" onkeyup="handlePhone(event)"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-3 px-4 text-white">
                    @error('whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300">Senha</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-3 px-4 text-white">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirmar
                        Senha</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-3 px-4 text-white">
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700">
                        CADASTRAR
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-400">
                Já possui uma conta?
                <a href="{{ route('login') }}" class="font-medium text-indigo-400 hover:text-indigo-300">
                    Faça o login
                </a>
            </p>
        </div>
    </main>

    <footer class="bg-gray-900">
        <div class="container mx-auto px-6 py-8 text-center text-gray-500">
            <p>&copy; {{ date('Y') }} Supermercado Gamifica. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
    const handlePhone = (event) => {
        let input = event.target;
        input.value = phoneMask(input.value);
    }
    const phoneMask = (value) => {
        if (!value) return "";
        value = value.replace(/\D/g, '');
        value = value.replace(/(\d{2})(\d)/, "($1) $2");
        value = value.replace(/(\d)(\d{4})$/, "$1-$2");
        return value;
    }
    </script>
</body>

</html>