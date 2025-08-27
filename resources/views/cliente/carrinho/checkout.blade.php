@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h1 class="text-2xl font-bold">Apresente no Caixa</h1>
        <p class="text-gray-600">Escaneie o código abaixo para finalizar sua compra.</p>
        
        <div class="mt-4 inline-block p-4 bg-white rounded-lg shadow-lg">
            {{-- O QR Code será renderizado aqui --}}
            {!! QrCode::size(250)->generate(route('api.v1.carts.show', ['uuid' => $uuid])) !!}
        </div>

        <p class="mt-4 text-sm text-gray-500">UUID: {{ $uuid }}</p>
    </div>
@endsection