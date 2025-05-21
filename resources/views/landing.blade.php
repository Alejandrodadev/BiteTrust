{{-- resources/views/landing.blade.php --}}
<x-app-layout>
    <!-- Hero -->
    <section class="bg-gray-800 text-white py-20">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-4">
                Descubre los mejores restaurantes según reseñas reales
            </h2>
            <p class="text-lg mb-6 text-primary">
                Explora opiniones, calificaciones y fotos de otros comensales. ¿Ya fuiste a uno? ¡Deja tu reseña!
            </p>
            <a href="#restaurants"
               class="inline-block border border-gray-300 bg-white text-form px-4 py-2 rounded-md shadow-sm hover:bg-gray-300 transition mb-8">
                Ver Restaurantes
            </a>
        </div>
    </section>

    <!-- Filtros colapsables -->
    <section class="max-w-7xl mx-auto px-4 mt-4" x-data="{ openFilters: false }">
        <div class="flex justify-end">
            <button
                @click="openFilters = !openFilters"
                class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 mr-1 text-primary"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                <span class="text-sm font-medium text-primary">Filtrar</span>
                <svg :class="openFilters ? 'rotate-180' : ''"
                     xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 ml-1 text-primary transition-transform"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>

        <div
            x-show="openFilters"
            x-cloak
            x-transition
            class="mt-2 bg-white border border-gray-200 rounded-md shadow-sm p-4 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm"
        >
            {{-- Búsqueda --}}
            <div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Buscar..."
                       class="w-full px-3 py-1 border border-gray-300 rounded focus:ring-primary focus:border-primary text-sm" />
            </div>

            {{-- Ciudad --}}
            <div>
                <select name="city"
                        class="w-full px-3 py-1 border border-gray-300 rounded focus:ring-primary focus:border-primary text-sm">
                    <option value="">Todas las ciudades</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" @selected(request('city') === $city)>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Orden --}}
            <div>
                <select name="sort"
                        class="w-full px-3 py-1 border border-gray-300 rounded focus:ring-primary focus:border-primary text-sm">
                    <option value="popularidad" @selected($sort==='popularidad')>Más populares</option>
                    <option value="recientes"   @selected($sort==='recientes')>Recientes</option>
                    <option value="tendencias"  @selected($sort==='tendencias')>Tendencias</option>
                </select>
            </div>

            {{-- Botón aplicar (ocupa toda la fila en md+) --}}
            <div class="md:col-span-3 text-right">
                <button type="submit"
                        class="px-4 py-1 bg-primary text-white rounded text-sm hover:bg-primary/90 transition">
                    Aplicar
                </button>
            </div>
        </div>
    </section>

    <!-- Listado de Restaurantes -->
    <section id="restaurants" class="max-w-7xl mx-auto px-4 py-16">
        @if((request('search') || request('city')) && $restaurants->isEmpty())
            <p class="text-secondary mb-8">
                No se encontraron restaurantes que coincidan con esos filtros.
            </p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($restaurants as $restaurant)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-5 flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-primary mb-1">
                                {{ $restaurant->name }}
                            </h4>
                            <p class="text-sm text-secondary">
                                {{ number_format($restaurant->reviews_avg_rating,1) }}/5 ⭐
                                ({{ $restaurant->reviews_count }} reseñas)
                            </p>
                            <a href="{{ route('restaurants.show', $restaurant) }}"
                               class="text-primary hover:underline text-sm inline-block mt-2">
                                Ver reseñas
                            </a>
                        </div>

                        @if($restaurant->photos->count())
                            <div class="flex flex-wrap gap-1">
                                @foreach($restaurant->photos->take(2) as $photo)
                                    <img src="{{ asset($photo->photo_url) }}"
                                         alt="Foto restaurante"
                                         class="w-16 h-16 object-cover rounded-md border shadow-sm" />
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="mt-8">
            {{ $restaurants->links() }}
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t mt-16">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-secondary">
            &copy; {{ date('Y') }} BiteTrust. Todos los derechos reservados.
        </div>
    </footer>
</x-app-layout>
