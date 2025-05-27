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
        </div>
    </section>

    <!-- Filtros desplegables -->
    <section class="max-w-7xl mx-auto px-4 mt-4" x-data="{ openFilters: false }">
        <div class="flex justify-end items-center space-x-2">
            <!-- Botón de filtros -->
            <button @click="openFilters = !openFilters"
                    type="button"
                    class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition">
                <svg class="w-4 h-4 mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
                </svg>
                <span class="text-sm font-medium text-primary">Filtrar</span>
                <svg :class="openFilters ? 'rotate-180' : ''" class="w-4 h-4 ml-1 text-primary transition-transform"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <!-- Cerca de mí -->
            <button type="button"
                    @click="
                        if (!navigator.geolocation) {
                            alert('Tu navegador no soporta geolocalización');
                        } else {
                            navigator.geolocation.getCurrentPosition(pos => {
                                const lat = pos.coords.latitude.toFixed(7);
                                const lng = pos.coords.longitude.toFixed(7);
                                const params = new URLSearchParams(window.location.search);
                                params.set('lat', lat);
                                params.set('lng', lng);
                                window.location.search = params.toString();
                            }, () => {
                                alert('Necesitamos permiso para obtener tu ubicación.');
                            });
                        }
                    "
                    class="inline-flex items-center px-3 py-1.5 border border-gray-200 bg-white text-primary rounded-md shadow-sm hover:bg-gray-100 transition">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 11c0 .66-.03 1.31-.08 1.95M7.05 12a4.95 4.95 0 019.9 0M12 4a8 8 0 018 8m0 0H4"/>
                </svg>
                <span class="text-sm">Cerca de mí</span>
            </button>
        </div>

        <!-- Formulario de filtros -->
        <form method="GET"
              action="{{ route('landing') }}"
              x-show="openFilters"
              x-cloak
              x-transition
              class="mt-2 bg-white border border-gray-200 rounded-md shadow-sm p-4 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
            @if(request('lat') && request('lng'))
                <input type="hidden" name="lat" value="{{ request('lat') }}">
                <input type="hidden" name="lng" value="{{ request('lng') }}">
            @endif

            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..."
                       class="w-full px-3 py-1 border border-gray-300 rounded focus:ring-primary focus:border-primary text-sm"/>
            </div>

            <div>
                <select name="city"
                        class="w-full px-3 py-1 border border-gray-300 rounded focus:ring-primary focus:border-primary text-sm">
                    <option value="">Todas las ciudades</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="sort"
                        class="w-full px-3 py-1 border border-gray-300 rounded focus:ring-primary focus:border-primary text-sm">
                    <option value="popularidad" @selected($sort==='popularidad')>Más populares</option>
                    <option value="recientes"   @selected($sort==='recientes')>Recientes</option>
                    <option value="tendencias"  @selected($sort==='tendencias')>Tendencias</option>
                </select>
            </div>

            <div class="md:col-span-3 text-right">
                <button type="submit"
                        class="px-4 py-1 bg-primary text-white rounded text-sm hover:bg-primary/90 transition">
                    Aplicar
                </button>
            </div>
        </form>
    </section>

    <!-- Formulario registro restaurante + mensajes -->
    <section class="max-w-7xl mx-auto px-4 mt-2 mb-2">

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-secondary px-3 py-2 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="mb-4 bg-blue-100 border border-blue-400 text-secondary px-3 py-2 rounded shadow-sm">
                {{ session('info') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-secondary px-3 py-2 rounded shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('restaurants.register') }}" id="registerPlaceForm">
            @csrf
            <label class="block text-sm text-secondary font-sans mb-1">¿Qué se te antoja hoy? Busca un restaurante y guardalo</label>
            <input
                type="text"
                id="placeInput"
                class="w-full px-4 py-2 bg-white text-secondary border border-gray-300 rounded-md shadow-sm placeholder-gray-400
                focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                placeholder="Ej: Los 33, Madrid"
                autocomplete="off"/>

            <input type="hidden" name="place_id" id="place_id">

            <div class="mt-1">
                <button type="submit"
                        class="inline-flex items-center px-2 py-1 text-primary bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 transition">
                    Guardar
                </button>
            </div>
        </form>
    </section>

    <script>
        const input = document.getElementById('placeInput');
        const placeIdField = document.getElementById('place_id');
        const autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace();
            if (place.place_id) {
                placeIdField.value = place.place_id;
            }
        });
    </script>

    <!-- Listado de Restaurantes -->
    <section id="restaurants" class="max-w-7xl mx-auto px-4 py-8">
        @if((request('search') || request('city') || request('lat')) && $restaurants->isEmpty())
            <p class="text-secondary mb-8">No se encontraron restaurantes que coincidan con esos filtros.</p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($restaurants as $restaurant)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition"
                     x-data="{ showSchedule: false }">
                    <div class="p-5 flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-primary mb-1">
                                {{ $restaurant->name }}
                            </h4>
                            @if($restaurant->types)
                            @php
                            $foodTypes = ['restaurant', 'food', 'bakery', 'meal_takeaway', 'meal_delivery', 'bar', 'cafe'];
                            @endphp
                            <div class="flex flex-wrap gap-2 mt-1 mb-1">
                                @foreach($restaurant->types ?? [] as $type)
                                @if(in_array($type, $foodTypes))
                                <span class="px-2 py-0.5 text-xs text-secondary bg-gray-100 rounded-full capitalize">
                                    {{ str_replace('_', ' ', $type) }}
                                </span>
                                @endif
                                @endforeach
                            </div>
                            @endif

                            <div class="text-sm text-secondary space-y-1 leading-tight">
                                {{-- BiteTrust Rating --}}
                                <div class="flex items-center gap-2 text-xs text-secondary">
                                    <img src="{{ asset('img/logo2.png') }}" alt="BiteTrust Logo" class="w-4 h-4 align-middle">
                                    <span class="leading-tight">
                                        {{ number_format($restaurant->reviews_avg_rating,1) }}/5 ⭐ {{ $restaurant->reviews_count }} reseñas
                                    </span>
                                </div>

                                {{-- Google Rating --}}
                                @if($restaurant->ratingGoogle)
                                    <div class="flex items-center gap-2 text-xs text-secondary">
                                        <img src="{{ asset('img/logo-google.png') }}" alt="Google Logo" class="w-3 h-3 align-middle">
                                        <span class="leading-tight">{{ number_format($restaurant->ratingGoogle, 1) }}/5
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('restaurants.show', $restaurant) }}"
                               class="text-primary hover:underline text-sm inline-block mt-2">
                                Ver reseñas
                            </a>

                            @if($restaurant->schedule)
                                <button @click="showSchedule = !showSchedule"
                                        class="flex items-center mt-2 text-xs text-primary hover:underline focus:outline-none">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-primary/10 text-primary rounded-full mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                                        </svg>
                                    </span>
                                    <span x-text="showSchedule ? '' : ''"></span>
                                </button>

                                <ul x-show="showSchedule" x-transition
                                    class="mt-2 text-xs text-secondary space-y-0.5" x-cloak>
                                    @foreach($restaurant->schedule as $line)
                                    <li>{{ $line }}</li>
                                    @endforeach
                                </ul>
                            @endif
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
    </section>

    <!-- Paginación -->
        <div class="mt-8">
            {{ $restaurants->links() }}
        </div>
    </section>
    <!-- Footer -->
    <footer class="bg-white border-t mt-16">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-secondary">
            © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
        </div>
    </footer>
</x-app-layout>
