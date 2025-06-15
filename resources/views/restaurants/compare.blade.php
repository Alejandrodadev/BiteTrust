<x-app-layout>
    {{-- Sesión y validación --}}
    <section class="max-w-7xl mx-auto px-4 mt-2 mb-2">
        <x-alert type="success" :message="session('success')" />
        <x-alert type="info"    :message="session('info')" />
        <x-alert type="error"   :message="session('error')" />
        @if($errors->has('ids'))
            <x-alert type="error" :message="$errors->first('ids')" />
        @endif
    </section>

    <div class="max-w-4xl mx-auto px-2 py-2">
    <h1 class="text-2xl font-bold text-primary mb-6">
        ¿Aún no te decides? ¡Compara tus opciones!
    </h1>

    {{-- Buscador y selección --}}
        <form x-data="compareSelector(@json($selected->pluck('only',['id','name'])))"
            @click.away="suggestions = []"
            method="GET"
            action="{{ route('restaurants.compare') }}"
            class="mb-8">
            <div class="flex gap-2 items-center">
                <input
                    x-model="query"
                    @input.debounce.300ms="search()"
                    type="text"
                    placeholder="🔍 Selecciona 2 o 3 restaurantes para comparar"
                    class="flex-1 px-3 py-2 border rounded focus:ring-primary focus:border-primary"/>
                <button
                    type="submit"
                    :disabled="selected.length < 2"
                    class="px-4 py-2 bg-primary text-white rounded disabled:opacity-50">
                    Comparar
                </button>

                @if($selected->count())
                    <a href="{{ route('restaurants.compare') }}"
                        class="px-4 py-2 bg-primary text-white rounded hover:bg-secondary-dark disabled:opacity-50 transition"
                        title="Nueva comparación">

                        {{-- Icono de recargar --}}
                        <x-heroicon-o-arrow-path class="w-6 h-6" />
                    </a>
                @endif
            </div>

        {{-- Sugerencias --}}
        <ul
            x-show="suggestions.length > 0"
            x-cloak
            class="border rounded mt-1 bg-white shadow-sm max-h-48 overflow-auto mb-4">
            <template x-for="r in suggestions" :key="r.id">
                <li
                    class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                    @click="add(r)"
                ><span x-text="r.name"></span></li>
            </template>
        </ul>

        {{-- Seleccionados --}}
        <div class="flex flex-wrap gap-2">
            <template x-for="(r,i) in selected" :key="r.id">
                    <span class="flex items-center gap-2 bg-primary/20 text-primary px-3 py-1 rounded-full">
                        <span x-text="r.name"></span>
                        <button type="button" @click="remove(i)" class="hover:text-red-600">&times;</button>
                        <input type="hidden" name="ids[]" :value="r.id" />
                    </span>
            </template>
        </div>
    </form>
    </div>



    <div class="max-w-5xl mx-auto px-6 py-2 "
         x-data="{ showHorario: false,
                showIA: false,
                showDistance:false,
                iaData: {},
                loadingIA: false,
                userCoords: null,
                analizarTodos(ids) {
                  this.loadingIA = true;
                  Promise.all(ids.map(id =>
                    fetch(`/restaurants/${id}/analysis`)
                      .then(r => r.json())
                      .then(js => this.iaData[id] = js)
                  )).finally(() => this.loadingIA = false);
                },
                getLocation() {
                  if (!navigator.geolocation) {
                    return alert('Geolocalización no soportada.');
                  }
                  navigator.geolocation.getCurrentPosition(pos => {
                    this.userCoords = {
                      lat: pos.coords.latitude,
                      lng: pos.coords.longitude
                    };
                  }, () => {
                    alert('Permiso denegado o error al obtener ubicación.');
                  });
                },
                distanceTo(lat2, lon2) {
                  const toRad = x => x * Math.PI/180;
                  const R = 6371; // Radio Tierra en km
                  const dLat = toRad(lat2 - this.userCoords.lat);
                  const dLon = toRad(lon2 - this.userCoords.lng);
                  const a = Math.sin(dLat/2)**2
                          + Math.cos(toRad(this.userCoords.lat))
                          * Math.cos(toRad(lat2))
                          * Math.sin(dLon/2)**2;
                  const c = 2*Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
                  const km = R * c;
                  if (km < 1) {
                    const m = Math.round(km*1000/10)*10;
                    return `${m} m`;
                  }
                  return `${km.toFixed(1)} km`;
                }}">

        @if($selected->count())
            <div class="overflow-x-auto mb-8 rounded-lg bg-gray-50 shadow-lg">
                <table class="min-w-full bg-gray-100 border-separate border-spacing-0">
                    <thead class="bg-primary/10 capitalize">
                    <tr>
                        <th class="px-6 py-3"></th>
                        @foreach($selected as $i => $rest)
                            <th class="px-6 py-3 text-left font-semibold text-primary"
                                x-data="{
                                  editing:     false,
                                  query:       '',
                                  suggestions: [],
                                  rid:         {{ $rest->id }},
                                  name:        '{{ addslashes($rest->name) }}',
                                  select(r) {
                                    let newIds = @js($selected->pluck('id'));
                                    newIds[{{ $i }}] = r.id;
                                    window.location = `{{ route('restaurants.compare') }}?` +
                                      newIds.map(id => `ids[]=${id}`).join('&');
                                  },
                                  search() {
                                    if (this.query.length < 2) { this.suggestions = []; return; }
                                    fetch(`{{ route('restaurants.search') }}?q=${encodeURIComponent(this.query)}`)
                                      .then(r=>r.json()).then(js=> this.suggestions = js);
                                  }}">
                                <template x-if="!editing">
                                    <div class="flex items-center gap-1">
                                        <span x-text="name"></span>
                                        <button @click="editing = true; query = ''; suggestions = []"
                                                class="text-xs text-gray-400 hover:text-secondary">✏️</button>
                                    </div>
                                </template>
                                <template x-if="editing">
                                    <div class="relative flex items-center gap-1">
                                        <input
                                            x-model="query"
                                            @input.debounce.300ms="search()"
                                            @keydown.escape="editing = false; suggestions = []"
                                            type="text"
                                            class="w-48 sm:w-64 px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition"
                                            placeholder="Buscar..."/>
                                        <!-- Botón cancelar -->
                                        <button
                                            @click="editing = false; suggestions = []; query = ''"
                                            class="px-2 py-1 text-sm text-secondary hover:text-primary focus:outline-none"
                                            title="Cancelar"
                                        >X</button>

                                        <ul
                                            x-show="suggestions.length"
                                            x-cloak
                                            class="absolute z-10 left-0 right-0 bg-white border rounded shadow mt-1 max-h-44 overflow-auto text-sm">
                                            <template x-for="r in suggestions" :key="r.id">
                                                <li
                                                    @click="select(r)"
                                                    class="px-3 py-1 hover:bg-gray-100 cursor-pointer"
                                                ><span x-text="r.name"></span></li>
                                            </template>
                                        </ul>
                                    </div>
                                </template>
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-x divide-gray-200">

                    {{-- Calificación --}}
                    <tr>
                        <td class="px-6 py-4 border border-gray-200 font-medium text-gray-700">Calificación</td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border border-gray-200 text-secondary">
                                <div class="flex items-center space-x-2">
                                    <img src="{{ asset('img/logo2.png') }}" class="w-4 h-4" alt="BT">
                                    <span>{{ number_format($rest->reviews_avg_rating,1) }}/5</span>
                                    @if($rest->ratingGoogle)
                                        <img src="{{ asset('img/logo-google.png') }}" class="w-4 h-4" alt="G">
                                        <span>{{ number_format($rest->ratingGoogle,1) }}/5 ⭐</span>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    </tr>

                    {{-- Ubicación --}}
                    <tr>
                        <td class="px-6 py-4 border border-gray-200 font-medium text-gray-700">Ubicación</td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border border-gray-200 text-secondary">{{ $rest->address }}</td>
                        @endforeach
                    </tr>

                    {{-- Website --}}
                    <tr>
                        <td class="px-6 py-4 border border-gray-200 font-medium text-gray-700">Website</td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border border-gray-200 text-secondary">
                                @if($rest->website)
                                    <a href="{{ $rest->website }}" target="_blank"
                                       class="text-primary hover:underline">
                                        {{ parse_url($rest->website, PHP_URL_HOST) ?? $rest->website }}
                                    </a>
                                @else
                                    <span class="italic text-gray-400">No disponible</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    {{-- Precio --}}
                    <tr>
                        <td class="px-6 py-4 border border-gray-200 font-medium text-gray-700">Precio</td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border border-gray-200 text-secondary">
                                @if(!is_null($rest->price_level))
                                    @for($i=0; $i<$rest->price_level; $i++)€@endfor
                                @else
                                    <span class="italic text-gray-400">No disponible</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    {{-- Horario --}}
                    <tr>
                        <td class="px-6 py-4 border border-gray-200 font-medium text-gray-700 align-top">Horario</td>
                        <td colspan="{{ $selected->count() }}"
                            class="px-6 py-4 border border-gray-200 align-top text-primary hover:underline cursor-pointer"
                            @click="showHorario = !showHorario">
                            <span x-text="!showHorario?'Ver':'Ocultar'"></span>
                        </td>
                    </tr>
                    <tr x-show="showHorario" x-cloak>
                        <td class="px-6 py-4 border border-gray-200 align-top"></td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border border-gray-200 align-top text-xs text-secondary"
                                style="vertical-align: top;">
                                @if($rest->schedule)
                                    <ul class="ml-5 space-y-1 m-0 p-0">
                                        @foreach($rest->schedule as $line)
                                            <li>{{ $line }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="italic text-gray-400">No disponible</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    {{-- Distancia --}}
                    <tr>
                        <td class="px-6 py-4 border border-gray-200 font-medium text-gray-700">
                            Distancia
                        </td>
                        <td colspan="{{ $selected->count() }}"
                            class="px-6 py-4 border border-gray-200 text-primary hover:underline cursor-pointer"
                            @click="if (!userCoords) getLocation();
                                  showDistance = !showDistance;
                                ">
                            <span x-text="showDistance ? 'Ocultar' : 'Ver'"></span>
                        </td>
                    </tr>
                    <tr x-show="showDistance" x-cloak>
                        <td class="px-6 py-4 border border-gray-200"></td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border border-gray-200 align-top text-secondary"
                                style="vertical-align: top;">
                                <template x-if="userCoords">
                                    <span x-text="distanceTo({{ $rest->latitude }}, {{ $rest->longitude }})"></span>
                                </template>
                                <template x-if="!userCoords">
                                    <span class="italic text-gray-400">—</span>
                                </template>
                            </td>
                        @endforeach
                    </tr>

                    {{-- Análisis IA --}}
                    <tr>
                        <td class="px-6 py-4 border border-gray-200 font-medium text-gray-700">Análisis IA</td>
                        <td colspan="{{ $selected->count() }}"
                            class="px-6 py-4 border border-gray-200 align-top text-primary hover:underline cursor-pointer"
                            @click="
                              showIA = !showIA;
                              if (showIA && Object.keys(iaData).length === 0)
                                analizarTodos(@json($selected->pluck('id')));
                            ">
                            <span x-show="!loadingIA" x-text="showIA ? 'Ocultar' : 'Ver'"></span>
                            <span x-show="loadingIA" class="italic text-gray-500">Analizando…</span>
                        </td>
                    </tr>
                    <tr x-show="showIA" x-cloak>
                        <td class="px-6 py-4 border border-gray-200 align-top"></td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border border-gray-200 align-top text-secondary"
                                x-data="{ rid: {{ $rest->id }} }"
                                style="vertical-align: top;">
                                <div class="space-y-2">
                                    <template x-if="iaData[rid]?.analisis">
                                        <div>
                                            <h3 class="m-0 text-sm font-semibold text-primary">Opinión general</h3>
                                            <p class="m-0 text-xs" x-text="iaData[rid].analisis"></p>
                                        </div>
                                    </template>
                                    <template x-if="iaData[rid]?.platos?.length">
                                        <ul class="ml-5 space-y-1 mt-2 text-xs">
                                            <template x-for="plato in iaData[rid].platos" :key="plato.plato">
                                                <li><strong class="text-primary block" x-text="plato.plato"></strong>
                                                    <span x-text="(plato.frases_destacadas||[]).join(' ')"></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </template>
                                    <template x-if="iaData[rid] && !iaData[rid].analisis && !iaData[rid].platos?.length">
                                        <p class="italic text-gray-400 text-xs">Sin información suficiente.</p>
                                    </template>
                                </div>
                            </td>
                        @endforeach
                    </tr>

                    </tbody>
                </table>
            </div>
        @endif

    </div>
</x-app-layout>
