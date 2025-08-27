<!DOCTYPE html>

<html lang="pt-br">

<x-head :titulo="$titulo_pagina ?? 'Supermercado Gamifica'" />

<body class="bg-gray-200 safe-areas">

    {{-- =============================================== --}}
    {{-- =========== NOVO CABEÇALHO APLICADO ============ --}}
    {{-- =============================================== --}}
    <header class="container mx-auto px-4 py-6 flex justify-between items-center">
        {{-- Saudação ao Utilizador --}}
        <div>
            <h1 class="text-2xl font-bold text-black">
                Olá, <span class="font-light">{{ explode(' ', Auth::user()->nome)[0] }}</span>!
            </h1>
        </div>

        {{-- Dropdown do Perfil --}}
        <div x-data="{ dropdownOpen: false }" class="relative" @click.outside="dropdownOpen = false">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2">
                <div class="h-12 w-12 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold">
                    @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="h-12 w-12 rounded-full object-cover">
                    @else
                    @php
                    $nameParts = explode(' ', auth()->user()->nome);
                    $initials = strtoupper(substr($nameParts[0], 0, 1) . (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : ''));
                    @endphp
                    <span>{{ $initials }}</span>
                    @endif
                </div>
            </button>
            {{-- Conteúdo do Dropdown --}}
            <div x-show="dropdownOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-64 rounded-lg border bg-white shadow-lg"
                style="display: none;">
                
                <div class="px-6 py-4 border-b">
                    <h4 class="text-sm font-semibold text-black">{{ auth()->user()->nome }}</h4>
                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                </div>

                <ul class="flex flex-col gap-1 p-3">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 rounded-md py-2 px-3 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary">
                            <i class="fas fa-user-edit w-5"></i> Perfil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('cliente.historico.index') }}" class="flex items-center gap-3 rounded-md py-2 px-3 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary">
                            <i class="fas fa-history w-5"></i> Histórico
                        </a>
                    </li>
                    @php $numeroSuporte = \App\Helpers\SettingsHelper::get('whatsapp_suporte'); @endphp
                    @if ($numeroSuporte)
                    <li>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $numeroSuporte) }}" target="_blank" class="flex items-center gap-3 rounded-md py-2 px-3 text-sm text-gray-600 hover:bg-gray-100 hover:text-primary">
                            <i class="fab fa-whatsapp w-5"></i> Suporte
                        </a>
                    </li>
                    @endif
                </ul>

                <div class="p-3 border-t">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3.5 rounded-md py-2 px-3 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-primary">
                            <i class="fas fa-sign-out-alt w-5"></i> Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="container mx-auto p-4 pb-24">
        @if (session('success'))
        <div class="mb-4 rounded-md border border-green-400 bg-green-100 p-4 text-sm text-green-700" role="alert">
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="mb-4 rounded-md border border-red-400 bg-red-100 p-4 text-sm text-red-700" role="alert">
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>
    
    <div id="barcodeScannerModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-full max-w-md m-4 text-center relative">
            <h2 class="text-2xl font-bold text-black mb-4">Escanear Produto</h2>
            <p class="text-gray-600 mb-6">Aponte a câmera para o código de barras ou digite manualmente.</p>
            
            <div class="relative w-full h-48 mb-4 border-2 border-gray-300 rounded-lg overflow-hidden bg-gray-900">
                <video id="video" class="absolute inset-0 w-full h-full object-cover"></video>
                <canvas id="canvas" class="hidden"></canvas>
                <div id="scanner-line" class="absolute top-0 left-0 right-0 h-1 bg-green-500"></div>
            </div>

            <div class="mb-4">
                <input type="text" id="manual-barcode-input" placeholder="Código de barras"
                       class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" onclick="closeBarcodeScannerModal()" class="bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-md">Fechar</button>
                <button type="button" onclick="adicionarAoCarrinho()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md">Adicionar</button>
            </div>
        </div>
    </div>

    <x-cliente-menu />

    <div id="toast-notification" class="toast fixed top-5 right-5 z-50 flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 bg-white divide-x divide-gray-200 rounded-lg shadow-lg">
    <div id="toast-icon-container" class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full">
        <i id="toast-icon" class="fas"></i>
    </div>
    <div class="pl-4 text-sm font-normal" id="toast-message"></div>
</div>

<style>
    @keyframes pulse-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(calc(12rem - 1px)); }
    }
    #scanner-line {
        animation: pulse-slow 2s infinite ease-in-out;
    }
</style>

<script>
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast-notification');
        const toastMessage = document.getElementById('toast-message');
        const toastIconContainer = document.getElementById('toast-icon-container');
        const toastIcon = document.getElementById('toast-icon');

        if (toast) {
            toastMessage.textContent = message;

            toastIconContainer.className = 'flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full';
            toastIcon.className = 'fas';

            if (type === 'success') {
                toastIconContainer.classList.add('bg-green-100', 'text-green-500');
                toastIcon.classList.add('fa-check-circle'); 
            } else { 
                toastIconContainer.classList.add('bg-red-100', 'text-red-500');
                toastIcon.classList.add('fa-times-circle');
            }

            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 3000);
        }
    }
    
    function countdown(dataFinal) {
        return {
            expirado: false,
            tempoRestante: '',
            dataAlvo: new Date(dataFinal).getTime(),
            init() {
                this.atualizarContador();
                this.intervalo = setInterval(() => {
                    this.atualizarContador();
                }, 1000);
            },
            destroy() {
                 clearInterval(this.intervalo);
            },
            atualizarContador() {
                const agora = new Date().getTime();
                const distancia = this.dataAlvo - agora;

                if (distancia < 0) {
                    this.expirado = true;
                    this.tempoRestante = 'Expirado';
                    clearInterval(this.intervalo);
                    return;
                }
                const dias = Math.floor(distancia / (1000 * 60 * 60 * 24));
                const horas = Math.floor((distancia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
                const segundos = Math.floor((distancia % (1000 * 60)) / 1000);
                
                if (dias > 0) {
                    this.tempoRestante = `${dias}d ${horas}h`;
                } else if (horas > 0) {
                    this.tempoRestante = `${horas}h ${minutos}m`;
                } else {
                    this.tempoRestante = `${minutos}m ${segundos}s`;
                }
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', (event) => {
        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');
        let ctx = canvas ? canvas.getContext('2d') : null;
        let modalBarcodeIput = document.getElementById('manual-barcode-input');
        
        let stream;
        let scanning = false;
        
        function tick() {
            if (!scanning || !video || video.readyState !== video.HAVE_ENOUGH_DATA) {
                if (scanning) requestAnimationFrame(tick);
                return;
            }
            
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert",
            });

            if (code) {
                console.log("Código encontrado:", code.data);
                modalBarcodeIput.value = code.data;
                adicionarAoCarrinho(code.data);
                closeBarcodeScannerModal();
            } else {
                requestAnimationFrame(tick);
            }
        }

        async function startScanner() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert("A API da câmera não é suportada ou a página não está em um contexto seguro (HTTPS/localhost).");
                return;
            }
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
                video.srcObject = stream;
                video.setAttribute("playsinline", true);
                await video.play();
                scanning = true;
                requestAnimationFrame(tick);
            } catch (err) {
                console.error("Erro ao acessar a câmera: " + err);
                alert("Erro ao acessar a câmera. Verifique as permissões.");
            }
        }

        window.openBarcodeScannerModal = function() {
            document.getElementById('barcodeScannerModal').classList.remove('hidden');
            startScanner();
        }

        window.closeBarcodeScannerModal = function() {
            document.getElementById('barcodeScannerModal').classList.add('hidden');
            scanning = false;
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }

        window.adicionarAoCarrinho = function(codigoBarras) {
    if (!codigoBarras || codigoBarras.trim() === '') {
        // Se não veio parâmetro, pega o input manual
        const manualInput = document.getElementById('manual-barcode-input');
        if (manualInput && manualInput.value.trim() !== '') {
            codigoBarras = manualInput.value.trim();
        } else {
            showToast('Por favor, digite ou escaneie um código de barras.', 'error');
            return;
        }
    }

    fetch("{{ route('cliente.carrinho.adicionarPorCodigoBarras') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify({ codigo_barras: codigoBarras })
    })
    .then(async (response) => {
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Ocorreu um erro desconhecido.');
        }
        return response.json();
    })
    .then(data => {
        showToast(data.message, 'success');
        const input = document.getElementById('barcode-input-dashboard');
        if (input) input.value = '';
        const manualInput = document.getElementById('manual-barcode-input');
        if (manualInput) manualInput.value = '';
    })
    .catch(error => {
        showToast(error.message, 'error');
    });
}

    // Função para ativar o cashback via AJAX
    function ativarCashback(form) {
        const button = form.querySelector('button');
        button.disabled = true;
        button.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Ativando...`;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name=_token]').value,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            showToast(data.message, data.success ? 'success' : 'error');
            if (data.success) {
                form.parentElement.innerHTML = `<span class="inline-block rounded-lg bg-green-100 py-2 px-4 text-sm font-medium text-green-700"><i class="fas fa-check"></i> Ativado</span>`;
            } else {
                button.disabled = false;
                button.textContent = 'Ativar Cashback';
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Ocorreu um erro de comunicação.', 'error');
            button.disabled = false;
            button.textContent = 'Ativar Cashback';
        });
    }

</script>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

@stack('scripts')

</body>

</html>