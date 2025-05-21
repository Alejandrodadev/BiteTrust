<section
    x-data="{
        photoPreview: null,
        hasAvatar: {{ $user->avatar ? 'true' : 'false' }},
        defaultAvatar: '{{ asset("images/default-avatar.png") }}'
    }">
    <form
        method="POST"
        action="{{ route('profile.update') }}"
        enctype="multipart/form-data"
        class="mt-6 space-y-6"
    >
        @csrf
        @method('patch')

        <div class="flex flex-col sm:flex-row sm:items-start sm:space-x-6">

            {{-- AVATAR --}}
            <div class="flex-shrink-0 relative">
                <span class="block rounded-full w-24 h-24 overflow-hidden bg-gray-100 flex items-center justify-center">
                    {{-- Icono si no hay avatar ni preview --}}
                    <template x-if="!photoPreview && !hasAvatar">
                        <x-heroicon-o-user-circle class="w-16 h-16 text-gray-300" />
                    </template>

                    {{-- Imagen (preview o la guardada en DB) --}}
                    <img
                        x-show="photoPreview || hasAvatar"
                        :src="photoPreview
                            ? photoPreview
                            : '{{ asset("storage/{$user->avatar}") }}'"
                        class="object-cover w-full h-full"
                        alt="{{ $user->name }}"
                    />
                </span>

                {{-- Icono “editar” --}}
                <label
                    for="avatar"
                    class="absolute bottom-0 right-0 bg-white rounded-full p-1 border border-gray-300
                           hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer"
                >
                    <input
                        id="avatar"
                        name="avatar"
                        type="file"
                        accept="image/*"
                        class="hidden"
                        @change="
                            const f = $event.target.files[0];
                            if (!f) return;
                            const reader = new FileReader();
                            reader.onload = e => photoPreview = e.target.result;
                            reader.readAsDataURL(f);
                            hasAvatar = true;
                        "
                    />
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5 text-gray-600 hover:text-gray-800"
                         viewBox="0 0 20 20" fill="currentColor">
                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                        <path fill-rule="evenodd"
                              d="M2 15a1 1 0 011-1h5v2H3a1 1 0 01-1-1z"
                              clip-rule="evenodd" />
                    </svg>
                </label>

                @error('avatar')
                <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- INFO + FORM FIELDS --}}
            <div class="flex-1">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Información de perfil
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Actualiza tu nombre, email y foto de perfil.
                    </p>
                </header>

                {{-- NOMBRE --}}
                <div class="mt-6">
                    <x-input-label for="name" :value="__('Nombre')" />
                    <x-text-input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('name', $user->name)"
                        required autofocus autocomplete="name"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                {{-- EMAIL --}}
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input
                        id="email"
                        name="email"
                        type="email"
                        class="mt-1 block w-full"
                        :value="old('email', $user->email)"
                        required autocomplete="username"
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                {{-- BOTÓN GUARDAR --}}
                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Guardar cambios') }}</x-primary-button>

                    @if (session('status') === 'profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400"
                        >{{ __('¡Guardado!') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </form>
</section>
