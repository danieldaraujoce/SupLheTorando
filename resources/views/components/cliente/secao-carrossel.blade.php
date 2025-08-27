@props(['titulo', 'itens'])

<section class="mb-8">
    {{-- Cabeçalho da Seção --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-black">{{ $titulo }}</h2>
        {{-- Link "Ver todos", se necessário --}}
        {{-- <a href="#" class="text-sm font-semibold text-primary">Ver todos</a> --}}
    </div>

    {{-- Container do Carrossel --}}
    <div class="flex overflow-x-auto space-x-4 pb-4 scrollbar-hide">
        {{-- O conteúdo do carrossel será inserido aqui --}}
        {{ $slot }}
    </div>
</section>