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

    <div class="max-w-4xl mx-auto px-4 py-8"
         x-data="{
         showHorario: false,
         showIA: false,
         iaData: {},
         loadingIA: false,
         analizarTodos(ids) {
           this.loadingIA = true;
           Promise.all(ids.map(id =>
             fetch(`/restaurants/${id}/analysis`)
               .then(r => r.json())
               .then(js => this.iaData[id] = js)
           )).finally(() => this.loadingIA = false);
         }
       }">

        <h1 class="text-2xl font-bold text-primary mb-6">
            ¿Aún no te decides? ¡Compara tus opciones!
        </h1>

        {{-- Buscador --}}
        <form
            x-data="compareSelector(@json($selected->pluck('only',['id','name'])))"
            @click.away="suggestions = []"
            method="GET"
            action="{{ route('restaurants.compare') }}"
            class="mb-8">
            <div class="flex gap-2">
                <input
                    x-model="query"
                    @input.debounce.300ms="search()"
                    type="text"
                    placeholder="🔍 Ej: La Tartería"
                    class="flex-1 pl-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"/>
                <button
                    type="submit"
                    :disabled="selected.length < 2"
                    class="px-5 py-2 bg-primary text-white font-medium rounded-lg disabled:opacity-50">
                    Comparar
                </button>
            </div>

            {{-- Sugerencias --}}
            <ul
                x-show="suggestions.length > 0"
                x-cloak
                class="mt-2 border border-gray-200 rounded-lg shadow-sm bg-white overflow-auto max-h-48">
                <template x-for="r in suggestions" :key="r.id">
                    <li
                        class="px-4 py-2 hover:bg-gray-50 cursor-pointer"
                        @click="add(r)"
                    ><span x-text="r.name"></span></li>
                </template>
            </ul>

            {{-- Seleccionados --}}
            <div class="mt-4 flex flex-wrap gap-2">
                <template x-for="(r,i) in selected" :key="r.id">
          <span class="flex items-center gap-2 bg-primary/20 text-primary px-3 py-1 rounded-full">
            <span x-text="r.name"></span>
            <button type="button" @click="remove(i)" class="hover:text-red-600">&times;</button>
            <input type="hidden" name="ids[]" :value="r.id" />
          </span>
                </template>
            </div>
        </form>

        @if($selected->count())
            <div class="overflow-x-auto text-sm rounded-lg shadow-sm mb-8">
                <table class="table-auto w-full">
                    <thead class="bg-primary/10">
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200"></th>
                        @foreach($selected as $rest)
                            <th class="px-6 py-3 capitalize border-b border-gray-200 text-left text-base font-bold text-primary">
                                {{ $rest->name }}
                            </th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    {{-- Calificación --}}
                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200 font-medium text-gray-700">Calificación</td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border-b border-gray-200 text-secondary">
                                <div class="flex items-center space-x-2">
                                    <img src="{{ asset('img/logo2.png') }}" class="w-4 h-4 flex-shrink-0" alt="BT">
                                    <span>{{ number_format($rest->reviews_avg_rating,1) }}/5</span>
                                    @if($rest->ratingGoogle)
                                        <img src="{{ asset('img/logo-google.png') }}" class="w-4 h-4 flex-shrink-0" alt="Google">
                                        <span>{{ number_format($rest->ratingGoogle,1) }}/5 ⭐</span>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    </tr>

                    {{-- Ubicación --}}
                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200 font-medium text-gray-700">Ubicación</td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border-b border-gray-200 text-secondary">{{ $rest->address }}</td>
                        @endforeach
                    </tr>

                    {{-- Website --}}
                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200 font-medium text-form">Website</td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border-b border-gray-200 text-sm">
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

                    {{-- Horario --}}
                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200 font-medium text-gray-700">Horario</td>
                        <td colspan="{{ $selected->count() }}"
                            class="px-6 py-4 border-b border-gray-200 text-primary hover:underline cursor-pointer"
                            @click="showHorario = !showHorario">
                            <span x-text="showHorario ? 'Ocultar' : 'Ver'"></span>
                        </td>
                    </tr>
                    <tr x-show="showHorario" x-cloak>
                        <td class="px-6 py-4 border-b border-gray-200"></td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border-b border-gray-200 text-xs text-secondary align-top">
                                @if($rest->schedule)
                                    <ul class="ml-5 space-y-1">
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

                    {{-- Precio --}}
                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200 font-medium text-form">Precio</td>
                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border-b border-gray-200 text-secondary">
                                @if(!is_null($rest->price_level))
                                    @for($i=0; $i<$rest->price_level; $i++)€@endfor
                                @else
                                    <span class="italic text-gray-400">No disponible</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>

                    {{-- Análisis IA --}}
                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200 font-medium text-form">Análisis IA</td>
                        <td
                            colspan="{{ $selected->count() }}"
                            class="px-6 py-4 border-b border-gray-200 text-primary hover:underline cursor-pointer"
                            @click="showIA = !showIA;
                            if (showIA && Object.keys(iaData).length === 0)
                            analizarTodos(@json($selected->pluck('id')));">

                            <span x-show="!loadingIA" x-text="showIA ? 'Ocultar' : 'Ver'"></span>
                            <span x-show="loadingIA" class="italic text-gray-500">Analizando…</span>
                        </td>
                    </tr>
                    <tr x-show="showIA" x-cloak>
                        <td class="px-6 py-4 border-b border-gray-200 align-top"></td>

                        @foreach($selected as $rest)
                            <td class="px-6 py-4 border-b border-gray-200 align-top text-xs text-secondary"
                                x-data="{ rid: {{ $rest->id }} }"
                                style="vertical-align: top;">
                                <div class="space-y-3">
                                    <template x-if="iaData[rid]?.analisis">
                                        <div>
                                            <h3 class="text-sm font-semibold text-primary mb-2">Opinión general</h3>
                                            <p class="text-xs leading-relaxed" x-text="iaData[rid].analisis"></p>
                                        </div>
                                    </template>
                                    <template x-if="iaData[rid]?.platos?.length">
                                        <div>
                                            <h4 class="text-sm font-semibold text-primary mb-2">Platos destacados</h4>
                                            <ul class="space-y-2 text-xs">
                                                <template x-for="plato in iaData[rid].platos" :key="plato.plato">
                                                    <li class="leading-relaxed">
                                                        <strong class="text-primary block" x-text="plato.plato"></strong>
                                                        <span class="text-gray-600" x-text="(plato.frases_destacadas||[]).join(' ')"></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
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
</x-app-layout>
