<section x-data="{ avatarPreview: null }">
    <header>
        <h2 class="text-lg font-medium text-gray-900 mt-4">
            Suas Informações
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Atualize o seu nome, e-mail e foto do perfil.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Campo de Upload do Avatar --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Foto do Perfil</label>
            <div class="mt-2 flex items-center gap-4">
                <div class="h-14 w-14 rounded-full bg-gray-200">
                    <img x-show="!avatarPreview" src="{{ Storage::url(auth()->user()->avatar) ?? 'https://via.placeholder.com/150' }}"
                        class="h-14 w-14 rounded-full object-cover">
                    <img x-show="avatarPreview" :src="avatarPreview" class="h-14 w-14 rounded-full object-cover">
                </div>
                <input type="file" name="avatar" id="avatar" class="hidden"
                    @change="avatarPreview = URL.createObjectURL($event.target.files[0])">
                <label for="avatar"
                    class="cursor-pointer rounded-md bg-white border border-gray-300 py-2 px-4 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Alterar
                </label>
            </div>
        </div>

        <div>
            <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
            <input id="nome" name="nome" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                value="{{ old('nome', $user->nome) }}" required autofocus autocomplete="name" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" name="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                value="{{ old('email', $user->email) }}" required autocomplete="username" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="rounded-lg bg-primary py-2 px-5 font-medium text-white hover:bg-opacity-90">Salvar</button>
            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">Salvo.</p>
            @endif
        </div>
    </form>
</section>