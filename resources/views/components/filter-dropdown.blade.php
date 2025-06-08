{{-- resources/views/components/filter-dropdown.blade.php --}}
<div x-data="landingFilters()" class="max-w-7xl mx-auto px-4 mt-4">
    <div class="flex justify-end items-center space-x-2">
        {{-- Botón de filtros --}}
        <button
            @click="openFilters = !openFilters"
            type="button"
            class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition"
        >
            <!-- Icono de filtro -->
            <svg class="w-4 h-4 mr-1 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L15 13.414V19a1 1 0 01-1.447.894l-4-2A1 1 0 019 17v-3.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            <span class="text-sm font-medium text-primary">Filtrar</span>
            <!-- Chevron -->
            <svg
                :class="openFilters ? 'rotate-180' : ''"
                class="w-4 h-4 ml-1 text-primary transition-transform"
                fill="none" viewBox="0 0 24 24" stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        {{-- Botón “Cerca de mí” --}}
        <button
            type="button"
            @click="geolocate()"
            class="inline-flex items-center px-3 py-1.5 border border-gray-200 bg-white text-primary rounded-md shadow-sm hover:bg-gray-100 transition"
        >
            <!-- Icono de ubicación -->
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 11c0 .66-.03 1.31-.08 1.95M7.05 12a4.95 4.95 0 019.9 0M12 4a8 8 0 018 8m0 0H4"/>
            </svg>
            <span class="text-sm">Cerca de mí</span>
        </button>
    </div>

    <form
        method="GET"
        action="{{ route('landing') }}"
        x-show="openFilters"
        x-cloak
        x-transition
        class="mt-2 bg-white border border-gray-200 rounded-md shadow-sm p-4 grid grid-cols-1 md:grid-cols-3 gap-3 text-sm"
    >
        @if(request('lat') && request('lng'))
            <input type="hidden" name="lat" value="{{ request('lat') }}">
            <input type="hidden" name="lng" value="{{ request('lng') }}">
        @endif

        <div>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Buscar..."
                class="w-full px-3 py-1 border border-gray-300 rounded focus:ring-primary focus:border-primary text-sm"
            />
        </div>

        <div>
            <select
                name="city"
                class="w-full px-3 py-1 border border-gray-300 rounded focus:ring-primary focus:border-primary text-sm"
            >
                <option value="">Todas las ciudades</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" @selected(request('city') === $city)>
                        {{ $city }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <select
                name="sort"
                class="w-full px-3 py-1 border border-gray-300 rounded focus:ring-primary focus:border-primary text-sm"
            >
                <option value="popularidad" @selected($sort==='popularidad')>Más populares</option>
                <option value="recientes"   @selected($sort==='recientes')>Recientes</option>
                <option value="tendencias"  @selected($sort==='tendencias')>Tendencias</option>
            </select>
        </div>

        <div class="md:col-span-3 text-right">
            <button
                type="submit"
                class="px-4 py-1 bg-primary text-white rounded text-sm hover:bg-primary/90 transition"
            >
                Aplicar
            </button>
        </div>
    </form>
</div>
