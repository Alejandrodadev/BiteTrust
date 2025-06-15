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
    <x-filter-dropdown :cities="$cities" :sort="$sort"/>

    <!-- Formulario registro restaurante + mensajes -->
    <section class="max-w-7xl mx-auto px-4 mt-2 mb-2">
        <x-alert type="success" :message="session('success')"/>
        <x-alert type="info" :message="session('info')"/>
        <x-alert type="error" :message="session('error')"/>

        <form method="POST" action="{{ route('restaurants.register') }}" id="registerPlaceForm">
            @csrf
            <label class="block text-sm text-secondary font-sans mb-1">
                ¿Qué se te antoja hoy? Busca un restaurante y guárdalo
            </label>

            <input type="text"
                   id="placeInput"
                   class="w-full px-4 py-2 bg-white text-secondary border border-gray-300 rounded-md shadow-sm placeholder-gray-400
                       focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary"
                   placeholder="🔎 Los 33, Madrid"
                   autocomplete="off"/>

            <input type="hidden" name="place_id" id="place_id">

            <div class="mt-1">
                @auth
                    <button
                        type="submit"
                        class="inline-flex items-center px-2 py-1 text-primary bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 transition">
                        Guardar
                    </button>
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-2 py-1 text-primary bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-100 transition">
                        Inicia sesión para guardar
                    </a>
                @endauth
            </div>
        </form>
    </section>

    <!-- listado de Restaurantes -->
    <section id="restaurants" class="max-w-7xl mx-auto px-4 py-8">
        @if((request('search') || request('city') || request('lat')) && $restaurants->isEmpty())
            <p class="text-secondary mb-8">
                No se encontraron restaurantes que coincidan con esos filtros.
            </p>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($restaurants as $restaurant)
                <div
                    class="bg-gray-100 border border-primaryLight rounded-xl shadow-2xl overflow-hidden hover:border-primary hover:shadow-lg transition"
                    x-data="{ showSchedule: false }">
                    <div class="p-5 flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-primary mb-1">
                                {{ $restaurant->name }}
                            </h4>

                            <div class="text-sm text-secondary space-y-1 leading-tight">
                                <div class="flex items-center gap-2 text-xs text-secondary">
                                    <img src="{{ asset('img/logo2.png') }}" alt="BiteTrust Logo"
                                         class="w-4 h-4 align-middle">
                                    <span class="leading-tight">
                                        {{ number_format($restaurant->reviews_avg_rating,1) }}/5 ⭐ {{ $restaurant->reviews_count }} reseñas
                                    </span>
                                </div>

                                @if($restaurant->ratingGoogle)
                                    <div class="flex items-center gap-2 text-xs text-secondary">
                                        <img src="{{ asset('img/logo-google.png') }}" alt="Google Logo"
                                             class="w-3 h-3 align-middle">
                                        <span class="leading-tight">
                                            {{ number_format($restaurant->ratingGoogle,1) }}/5
                                            @if(! is_null($restaurant->price_level))
                                                @for($i=0; $i < $restaurant->price_level; $i++)
                                                    €
                                                @endfor
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Ciudad --}}
                            @if($restaurant->city)
                                <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary"
                                         viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                              d="M10 2a6 6 0 00-6 6c0 4.627 6 10 6 10s6-5.373 6-10a6 6 0 00-6-6zm0 8a2 2 0 110-4 2 2 0 010 4z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    <span>{{ $restaurant->city }}</span>
                                </div>
                            @endif

                            {{-- Muestra distancia si existe --}}
                            @isset($restaurant->distance)
                                @php
                                    $dist = $restaurant->distance; // en km
                                    if ($dist < 1) {
                                        $meters= $dist * 1000;
                                        // redondear metros
                                        $roundedMeters= round($meters / 10) * 10;
                                        $display= $roundedMeters . ' m';
                                    } else {
                                        $display= number_format($dist, 1) . ' km';
                                    }
                                @endphp

                                <div class="flex items-center gap-1 text-xs text-gray-500 mt-1">
                                    @if($dist > 1000)
                                        <img src="{{ asset('img/distanciaAvion.svg') }}" alt="En avion" class="w-5 h-5"/>

                                    @elseif($dist > 2)
                                        <img src="{{ asset('img/distanciaCoche.svg') }}" alt="En coche" class="w-5 h-5"/>

                                    @else
                                        <img
                                            src="{{ asset('img/distanciaAndando.svg') }}"
                                            alt="A pie"
                                            class="w-5 h-5"/>
                                    @endif

                                    <span>{{ $display }}</span>
                                </div>
                            @endisset

                            <a href="{{ route('restaurants.show', $restaurant) }}"
                               class="text-primary hover:underline text-sm inline-block mt-2">
                                Ver más
                            </a>

                            @if($restaurant->schedule)
                                <button
                                    @click="showSchedule = ! showSchedule"
                                    class="flex items-center mt-2 text-xs text-primary hover:underline focus:outline-none">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 bg-primary/10 text-primary rounded-full mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 6v6l4 2"/>
                                        </svg>
                                    </span>
                                    Horario
                                </button>

                                <ul x-show="showSchedule"
                                    x-transition
                                    x-cloak
                                    class="mt-2 text-xs text-secondary space-y-0.5">
                                    @foreach($restaurant->schedule as $line)
                                        <li>{{ $line }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        @if($restaurant->photos->count())
                            <div class="flex flex-wrap gap-1">
                                @foreach($restaurant->photos->take(2) as $photo)
                                    <img
                                        src="{{ asset($photo->photo_url) }}"
                                        alt="Foto restaurante"
                                        class="w-16 h-16 object-cover rounded-md border shadow-sm"/>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="mt-6 px-4 text-xs">
            {{ $restaurants->links() }}
        </div>
    </section>

</x-app-layout>
