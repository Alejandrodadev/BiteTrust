<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
</head>
@php
    $startInRegister = $errors->has('name') || $errors->has('password_confirmation');
@endphp

<x-guest-layout>
    <div
        x-data="{ isLogin: {{ $startInRegister ? 'false' : 'true' }} }"
        class="max-w-md mx-auto mt-12 bg-white p-6 rounded shadow"
    >
        <div class="flex justify-center mb-6">
            <button @click="isLogin = true"
                    :class="isLogin
                ? 'text-indigo-600 border-b-2 border-indigo-600'
                : 'text-gray-500 hover:text-indigo-600 hover:border-b-2 hover:border-indigo-600'"
                    class="px-4 py-2 font-semibold focus:outline-none transition">
                Iniciar sesión
            </button>
            <button @click="isLogin = false"
                    :class="!isLogin
                ? 'text-indigo-600 border-b-2 border-indigo-600'
                : 'text-gray-500 hover:text-indigo-600 hover:border-b-2 hover:border-indigo-600'"
                    class="px-4 py-2 font-semibold focus:outline-none transition">
                Registrarse
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

            <div class="mb-4 ">
                <x-input-label for="email" value="Correo electrónico"/>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="password" value="Contraseña" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="block mb-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ml-2 text-sm text-darkgray-300">Recordarme</span>
                </label>
            </div>

            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
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
                <x-input-label for="name" value="Nombre"  />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="email" value="Correo electrónico"  />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="password" value="Contraseña" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-4">
                <x-input-label for="password_confirmation" value="Confirmar contraseña" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <x-primary-button class="w-full justify-center">Registrarse</x-primary-button>
        </form>
    </div>
</x-guest-layout>

