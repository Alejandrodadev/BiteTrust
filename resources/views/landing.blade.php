<x-app-layout>
    {{-- Si quieres cambiar el título de la cabecera, edítalo aquí; si no, déjalo vacío --}}
    <x-slot name="header">
        {{-- <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inicio') }}
        </h2> --}}
    </x-slot>

    <!-- Hero -->
    <section class="bg-gray-800 text-white py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">
                Descubre los mejores restaurantes según reseñas reales
            </h2>
            <p class="text-lg mb-6">
                Explora opiniones, calificaciones y fotos de otros comensales. ¿Ya fuiste a uno? ¡Deja tu reseña!
            </p>
            <a href="#restaurants"
               class="inline-block bg-white text-indigo-600 font-semibold px-6 py-3 rounded-full shadow hover:bg-gray-100 transition">
                Ver Restaurantes
            </a>
        </div>
    </section>

    <!-- Listado de Restaurantes -->
    <section id="restaurants" class="max-w-7xl mx-auto px-4 py-16">
        <h3 class="text-2xl font-bold mb-6">Restaurantes Populares</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($restaurants as $restaurant)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-5">
                        <h4 class="text-lg font-semibold text-indigo-600">
                            {{ $restaurant->name }}
                        </h4>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ number_format($restaurant->reviews_avg_rating, 1) }}/5 ⭐
                            ({{ $restaurant->reviews_count }} reseñas)
                        </p>
                        <a href="{{ route('restaurants.show', $restaurant) }}"
                           class="text-indigo-500 hover:underline text-sm mt-4 inline-block">
                            Ver reseñas
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t mt-16">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} BiteTrust. Todos los derechos reservados.
        </div>
    </footer>
</x-app-layout>
