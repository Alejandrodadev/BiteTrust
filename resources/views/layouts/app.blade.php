<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BiteTrust') }}</title>

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Favicons y soporte PWA/Android -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/android-chrome-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicons/android-chrome-512x512.png') }}">
    <link rel="manifest" href="{{ asset('favicons/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('favicons/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-TileImage" content="{{ asset('favicons/favicon.ico') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Google Scripts -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_PLACES_KEY') }}&libraries=places"></script>

    <!-- Styles & Scripts -->
    <script>
        window.isGuest = @json(auth()->guest());
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="font-sans antialiased min-h-screen flex flex-col bg-gray-50">

{{-- Navegación --}}
@include('layouts.navigation')

{{-- Contenido principal --}}
<main class="flex-1">
    {{ $slot }}
</main>

{{-- Aviso de Cookies --}}
<div x-data="{
            open: localStorage.getItem('cookies_accepted') === null
        }"
     x-show="open"
     x-cloak
     class="fixed bottom-8 right-8 max-w-xl bg-white border border-gray-200 rounded-xl shadow-2xl p-6 space-y-3 z-50">
    <p class="text-base text-gray-700">
        🍪 Usamos cookies propias y de terceros para mejorar tu experiencia.
        Si continúas navegando aceptas su uso.
    </p>
    <div class="flex justify-end flex-wrap gap-2">
        <button
            @click="localStorage.setItem('cookies_accepted','true'); open = false;"
            class="px-4 py-2 text-sm bg-primaryLight text-white rounded hover:bg-primary/90 transition">
            Aceptar
        </button>
        <button
            @click="localStorage.setItem('cookies_accepted','false'); open = false;"
            class="px-4 py-2 text-sm bg-secondary text-white rounded hover:bg-primary/90 transition">
            Rechazar
        </button>
        <a href="{{ route('cookies.policy') }}"
           class="px-4 py-2 text-sm text-gray-600 hover:underline">
            Más info
        </a>
    </div>
</div>
{{-- Footer fijo abajo --}}
<footer class="bg-white border-t">
    <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-secondary">
        © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
    </div>
</footer>
</body>
</html>
