@extends('layouts.app') {{-- AJUSTE AQUI para o nome correto do seu layout --}}

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Cabeçalho da Página --}}
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Scanner de Produtos</h1>
        <p class="text-gray-500 mt-2">Aponte a câmara para o código de barras do produto.</p>
    </div>

    {{-- Área do Scanner --}}
    <div class="bg-white rounded-2xl shadow-lg p-4 md:p-6 max-w-lg mx-auto">
        <div id="reader" class="w-full rounded-lg overflow-hidden border-2 border-dashed border-gray-300"></div>
        <div class="mt-4 text-center">
            <p id="status-text" class="text-gray-600 font-medium h-6">Aguardando para iniciar...</p>
            <div class="flex justify-center gap-4 mt-4">
                <button id="startButton" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center justify-center">
                    <i class="fas fa-play mr-2"></i> Iniciar Scanner
                </button>
                <button id="stopButton" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded-lg transition-colors flex items-center justify-center" disabled>
                    <i class="fas fa-stop mr-2"></i> Parar
                </button>
            </div>
        </div>
    </div>

    {{-- Área de Resultados --}}
    <div id="result-container" class="mt-8 max-w-lg mx-auto"></div>
</div>

{{-- Incluindo a biblioteca do scanner via CDN --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusText = document.getElementById('status-text');
    const resultContainer = document.getElementById('result-container');
    const startButton = document.getElementById('startButton');
    const stopButton = document.getElementById('stopButton');

    const html5QrCode = new Html5Qrcode("reader");

    // --- INÍCIO DA CORREÇÃO ---
    // Nova função para controlar a notificação Toast
    let toastTimeout;
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast-notification');
        const toastMessage = document.getElementById('toast-message');
        const toastIcon = document.getElementById('toast-icon');

        // Limpa qualquer timeout anterior para evitar que o toast se esconda prematuramente
        clearTimeout(toastTimeout);

        toastMessage.innerText = message;
        if (type === 'success') {
            toastIcon.innerHTML = `<i class="fas fa-check-circle text-green-500"></i>`;
            toast.classList.remove('bg-red-600');
            toast.classList.add('bg-gray-800');
        } else {
            toastIcon.innerHTML = `<i class="fas fa-exclamation-circle text-red-500"></i>`;
            toast.classList.remove('bg-gray-800');
            toast.classList.add('bg-red-600');
        }

        toast.classList.add('show');

        // Esconde o toast após 4 segundos
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 4000);
    }
    // --- FIM DA CORREÇÃO ---

    const onScanSuccess = (decodedText, decodedResult) => {
        statusText.innerHTML = `<span class="text-green-500 font-semibold">Sucesso! Código: ${decodedText}</span>`;
        stopScanning();
        buscarProduto(decodedText);
    };

    const buscarProduto = (codigoBarras) => {
        resultContainer.innerHTML = `<p class="text-center text-gray-500">Buscando produto...</p>`;
        const url = `{{ route('api.produto.buscar', ['codigo_barras' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', codigoBarras);

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error('Produto não encontrado em nosso catálogo.');
                return response.json();
            })
            .then(data => {
                const imageUrl = data.imagem_url ? `{{ asset('storage') }}/${data.imagem_url.replace('public/', '')}` : 'https://via.placeholder.com/150';

                resultContainer.innerHTML = `
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden animate-fade-in">
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row gap-6 items-center sm:items-start">
                                <img src="${imageUrl}" alt="${data.nome}" class="w-28 h-28 object-cover rounded-lg border">
                                <div class="text-center sm:text-left">
                                    <h3 class="text-xl font-bold text-gray-800">${data.nome}</h3>
                                    <p class="text-2xl font-semibold text-primary mt-2">R$ ${parseFloat(data.preco).toFixed(2).replace('.', ',')}</p>
                                </div>
                            </div>
                            <div class="mt-6">
                                <form id="form-add-carrinho" action="{{ route('cliente.carrinho.adicionar') }}" method="POST">
                                    <input type="hidden" name="produto_id" value="${data.id}">
                                    <input type="hidden" name="quantidade" value="1">
                                    <button type="submit" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                                        <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                `;

                document.getElementById('form-add-carrinho').addEventListener('submit', function(event) {
                    event.preventDefault();
                    const formData = new FormData(this);
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-CSRF-TOKEN': csrfToken }
                    })
                    .then(response => {
                        if (!response.ok) return response.json().then(err => { throw new Error(err.message) });
                        return response.json();
                    })
                    .then(data => {
                        showToast(data.message, 'success'); // CORREÇÃO: Usa o Toast
                        resultContainer.innerHTML = '';
                    })
                    .catch(error => {
                        showToast(error.message, 'error'); // CORREÇÃO: Usa o Toast
                    });
                });
            })
            .catch(error => {
                showToast(error.message, 'error'); // CORREÇÃO: Usa o Toast
                resultContainer.innerHTML = ``;
            });
    }

    const startScanning = () => {
        const config = {
            fps: 10,
            qrbox: { width: 280, height: 150 },
            rememberLastUsedCamera: true,
            formatsToSupport: [ Html5QrcodeSupportedFormats.EAN_13, Html5QrcodeSupportedFormats.EAN_8, Html5QrcodeSupportedFormats.UPC_A, Html5QrcodeSupportedFormats.UPC_E ]
        };
        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess, undefined)
            .then(() => {
                statusText.innerHTML = '<span class="text-blue-500 font-semibold">Aponte para o código de barras...</span>';
                startButton.disabled = true;
                stopButton.disabled = false;
            })
            .catch(err => {
                statusText.innerHTML = `<span class="text-red-500 font-semibold">Não foi possível iniciar a câmara. Verifique as permissões.</span>`;
            });
    };

    const stopScanning = () => {
        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop()
                .then(() => {
                    statusText.innerHTML = 'Scanner parado.';
                    startButton.disabled = false;
                    stopButton.disabled = true;
                })
                .catch(err => console.error("Falha ao parar o scanner.", err));
        }
    };

    startButton.addEventListener('click', startScanning);
    stopButton.addEventListener('click', stopScanning);
});
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.5s ease-out forwards;
    }
</style>
@endsection