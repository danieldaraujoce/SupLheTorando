<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Atualizar Senha</h2>
        <p class="mt-1 text-sm text-gray-600">Garanta que sua conta use uma senha longa e aleatória para se manter
            segura.</p>
    </header>
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')
        <div>
            <label for="current_password" class="block text-sm font-medium text-gray-700">Senha Atual</label>
            <input id="current_password" name="current_password" type="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" autocomplete="current-password" />
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Nova Senha</label>
            <input id="password" name="password" type="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" autocomplete="new-password" />
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nova
                Senha</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" autocomplete="new-password" />
        </div>
        <div class="flex items-center gap-4">
            <button type="submit"
                class="rounded-lg bg-primary py-2 px-5 font-medium text-white hover:bg-opacity-90">Salvar</button>
            @if (session('status') === 'password-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">Salvo.</p>
            @endif
        </div>
    </form>
</section>