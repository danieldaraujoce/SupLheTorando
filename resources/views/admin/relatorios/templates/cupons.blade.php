@extends('admin.layouts.app')
@section('content')
<h2 class="text-2xl font-bold text-black">Relatório de Cupons</h2>
{{-- Cards de estatísticas --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-6">
    <div class="bg-white p-6 rounded-2xl border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-700">Total de Resgates</h3>
        <p class="text-3xl font-bold text-primary mt-2">{{ $totalResgates }}</p>
    </div>
</div>

{{-- Tabela de cupons mais populares --}}
<div class="bg-white p-6 rounded-2xl border border-gray-200 mt-6">
    <h3 class="text-lg font-semibold text-black mb-4">Top 10 Cupons Mais Resgatados</h3>
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 text-left">
                <th class="py-3 px-4">Código</th>
                <th class="py-3 px-4">Descrição</th>
                <th class="py-3 px-4 text-center">Nº de Resgates</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cuponsMaisResgatados as $cupom)
            <tr class="border-b">
                <td class="py-3 px-4 font-semibold">{{ $cupom->codigo }}</td>
                <td class="py-3 px-4">{{ $cupom->descricao }}</td>
                <td class="py-3 px-4 text-center font-bold">{{ $cupom->usuarios_que_resgataram_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection