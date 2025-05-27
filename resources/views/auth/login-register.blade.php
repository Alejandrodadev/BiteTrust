
@php
    use Illuminate\Support\Facades\Route;
    $startInRegister = $errors->has('name') || $errors->has('password_confirmation');
@endphp

<x-guest-layout>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('status') }}
        </div>
    @endif

    <div
        x-data="{ isLogin: {{ $startInRegister ? 'false' : 'true' }} }"
        class="max-w-md mx-auto mt-12 bg-white p-6 rounded shadow relative"
    >
        {{-- Botón Volver --}}
        <a href="{{ url()->previous() }}"
           class="absolute top-4 left-4 text-sm text-secondary hover:text-primary transition-colors">
            &larr; Volver
        </a>

        <div class="flex justify-center mb-6">
            <button @click="isLogin = true"
                    :class="isLogin
                        ? 'text-primary border-b-2 border-primary'
                        : 'text-secondary hover:text-primary hover:border-b-2 hover:border-primary'"
                    class="px-4 py-2 font-semibold focus:outline-none transition">
                Inicia sesión
            </button>
            <button @click="isLogin = false"
                    :class="!isLogin
                        ? 'text-primary border-b-2 border-primary'
                        : 'text-secondary hover:text-primary hover:border-b-2 hover:border-primary'"
                    class="px-4 py-2 font-semibold focus:outline-none transition">
                Regístrate
            </button>
        </div>

        <!-- Login form -->
        <form x-show="isLogin"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 scale-95"
              x-transition:enter-end="opacity-100 scale-100"
              x-transition:leave="transition ease-in duration-200"
              x-transition:leave-start="opacity-100 scale-100"
              x-transition:leave-end="opacity-0 scale-95"
              x-cloak
              method="POST"
              action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <x-input-label for="email" value="Correo electrónico" class="text-secondary" />
                <x-text-input id="email" type="email" name="email"
                              :value="old('email')" required autofocus
                              class="block mt-1 w-full bg-form text-white border-gray-300 focus:border-primary focus:ring-primary" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-error" />
            </div>

            <div class="mb-4">
                <x-input-label for="password" value="Contraseña" class="text-secondary" />
                <x-text-input id="password" type="password" name="password" required
                              class="block mt-1 w-full bg-form text-white border-gray-300 focus:border-primary focus:ring-primary" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-error" />
            </div>

            <div class="block mb-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                           class="rounded border-gray-600 text-primary shadow-sm focus:ring-primary" name="remember">
                    <span class="ml-2 text-sm text-secondary">Recordarme</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm text-secondary hover:text-primary" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif

                <x-primary-button>Entrar</x-primary-button>
            </div>
        </form>

        <!-- Register form -->
        <form x-show="!isLogin"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 scale-95"
              x-transition:enter-end="opacity-100 scale-100"
              x-transition:leave="transition ease-in duration-200"
              x-transition:leave-start="opacity-100 scale-100"
              x-transition:leave-end="opacity-0 scale-95"
              x-cloak
              method="POST"
              action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <x-input-label for="name" value="Nombre" class="text-secondary" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus
                              class="block mt-1 w-full bg-form text-white border-gray-300 focus:border-primary focus:ring-primary" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-error" />
            </div>

            <div class="mb-4">
                <x-input-label for="email" value="Correo electrónico" class="text-secondary" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required
                              class="block mt-1 w-full bg-form text-white border-gray-300 focus:border-primary focus:ring-primary" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-error" />
            </div>

            <div class="mb-4">
                <x-input-label for="password" value="Contraseña" class="text-secondary" />
                <x-text-input id="password" type="password" name="password" required
                              class="block mt-1 w-full bg-form text-white border-gray-300 focus:border-primary focus:ring-primary" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-error" />
            </div>

            <div class="mb-4">
                <x-input-label for="password_confirmation" value="Confirmar contraseña" class="text-secondary" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                              class="block mt-1 w-full bg-form text-white border-gray-300 focus:border-primary focus:ring-primary" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-error" />
            </div>

            <x-primary-button class="w-full justify-center">Registrarse</x-primary-button>
        </form>
    </div>
</x-guest-layout>
