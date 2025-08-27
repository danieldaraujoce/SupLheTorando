@extends('layouts.app')

@section('content')

{{-- Card Principal com Saldo e Progresso --}}
<x-cliente.dashboard.saldo-e-progresso 
    :nivelUsuario="$nivel_usuario" 
    :proximoNivelNome="$proximo_nivel_nome" 
    :progressoPercentual="$progresso_percentual" 
/>

{{-- Botão de Leitor de Código de Barras --}}
<x-cliente.dashboard.leitor-codigo-barras />

{{-- Promoções em Destaque --}}
<x-cliente.dashboard.promocoes :promocoes="$promocoes" />

{{-- Seção Jogue e Ganhe --}}
<x-cliente.dashboard.jogue-ganhe />

{{-- Campanhas de Cashback Ativas --}}
<x-cliente.dashboard.cashback 
    :cashbacksAtivos="$cashbacks_ativos" 
    :cashbacksResgatadosIds="$cashbacks_resgatados_ids" 
/>
{{-- Cupons Recomendados --}}
@if($cuponsRecomendados->isNotEmpty())
    <x-cliente.dashboard.ofertas-cupons 
        :cupons="$cuponsRecomendados" 
        titulo="Cupons Recomendados para Você" 
    />
@endif

{{-- Encartes de Ofertas --}}
<x-cliente.dashboard.encartes :encartes="$encartes" />

{{-- Suas Próximas Conquistas --}}
<x-cliente.dashboard.missoes :missoesAtivas="$missoes_ativas" />

{{-- Guias e Dicas --}}
<x-cliente.dashboard.guias-dicas :guiasDicas="$guias_dicas" />

{{-- Ofertas e Cupons --}}
<x-cliente.dashboard.ofertas-cupons :cupons="$cupons" />

{{-- Ofertas do Mês --}}
<x-cliente.dashboard.ofertas-mes :ofertasMes="$ofertas_mes" />

{{-- Loja de Recompensas --}}
<x-cliente.dashboard.loja-recompensas :recompensas="$recompensas" />

{{-- Ofertas Vip --}}
<x-cliente.dashboard.ofertas-vip :ofertasVip="$ofertas_vip" />

@endsection