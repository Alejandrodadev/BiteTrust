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

    <!-- Filtros -->
    <section class="max-w-7xl mx-auto px-4 pt-8">
        <form method="GET" action="{{ route('landing') }}" class="flex flex-wrap gap-4 items-end">
            {{-- Búsqueda --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-secondary mb-1">Buscar</label>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Nombre del restaurante..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
            </div>

            {{-- Ciudad --}}
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium text-secondary mb-1">Ciudad</label>
                <select name="city"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                    <option value="">Todas</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" @selected(request('city') === $city)>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Orden --}}
            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium text-secondary mb-1">Ordenar por</label>
                <select name="sort"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                    <option value="popularidad" @selected($sort==='popularidad')>Más populares</option>
                    <option value="recientes"   @selected($sort==='recientes')>Recientes</option>
                    <option value="tendencias"  @selected($sort==='tendencias')>Tendencias</option>
                </select>
            </div>

            <div>
                <button type="submit"
                        class="px-6 py-2 bg-primary text-form rounded-md shadow hover:bg-primary/90 transition">
                    Filtrar
                </button>
            </div>
        </form>
    </section>

    <!-- Listado -->
    <section id="restaurants" class="max-w-7xl mx-auto px-4 py-16">
        @if(request(['search','city']) && $restaurants->isEmpty())
            <p class="text-secondary mb-8">
                No se encontraron restaurantes que coincidan con esos filtros.
            </p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($restaurants as $restaurant)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-5 flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-primary mb-1">{{ $restaurant->name }}</h4>
                            <p class="text-sm text-secondary">
                                {{ number_format($restaurant->reviews_avg_rating,1) }}/5 ⭐ ({{ $restaurant->reviews_count }} reseñas)
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
