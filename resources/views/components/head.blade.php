@props(['titulo' => 'Supermercado Gamifica'])

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $titulo }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/winwheel@1.0.1/dist/Winwheel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.0.0/dist/jsQR.js"></script>
    
    {{-- CSS PARA A NOTIFICAÇÃO TOAST --}}
    <style>
        .toast {
            visibility: hidden;
            opacity: 0;
            transform: translateY(-30px);
            transition: all 0.3s ease-in-out;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .toast.show {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }
    </style>

</head>