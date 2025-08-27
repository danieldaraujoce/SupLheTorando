<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Excluir Conta</h2>
        <p class="mt-1 text-sm text-gray-600">Uma vez que sua conta for excluída, todos os seus dados serão
            permanentemente apagados. Antes de excluir, por favor, baixe qualquer dado que deseje manter.</p>
    </header>
    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="rounded-lg bg-red-600 py-2 px-5 font-medium text-white hover:bg-red-700">Excluir Conta</button>
    {{-- (O modal de confirmação do Breeze será usado aqui) --}}
</section>