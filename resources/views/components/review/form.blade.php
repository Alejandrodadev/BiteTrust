@props(['restaurant'])

<div class="mt-6 mb-3 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
    <h3 class="text-lg font-semibold mb-4 text-primary">Deja tu reseña</h3>
    <form action="{{ route('reviews.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">

        {{-- Calificación --}}
        <div x-data="{ open: false, selected: '{{ old('rating') }}', options: [
                  { value: '', label: 'Selecciona la calificación' },
                  { value: '5', label: '5 ⭐' },
                  { value: '4', label: '4 ⭐' },
                  { value: '3', label: '3 ⭐' },
                  { value: '2', label: '2 ⭐' },
                  { value: '1', label: '1 ⭐' }
                ] }" class="relative">
            <label for="rating" class="block text-sm font-medium text-white mb-1">Calificación</label>
            <button type="button"
                    @click="open = !open"
                    @click.away="open = false"
                    class="w-full text-left px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:!ring-primary focus:!border-primary">
                <span x-text="options.find(o => o.value === selected)?.label || 'Selecciona la calificación'"
                      :class="selected ? 'text-primary' : 'text-secondary'"></span>
                <svg class="w-4 h-4 float-right mt-1 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <ul x-show="open" x-transition
                class="absolute z-10 mt-1 w-full bg-white border border-primary rounded-md shadow-md max-h-60 overflow-auto">
                <template x-for="o in options" :key="o.value">
                    <li @click="selected = o.value; open = false"
                        :class="{ 'bg-gray-100': selected === o.value }"
                        class="px-4 py-2 text-form hover:bg-primaryLight cursor-pointer">
                        <span x-text="o.label"></span>
                    </li>
                </template>
            </ul>
            <input type="hidden" name="rating" :value="selected">
            @error('rating')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Comentario --}}
        <div>
            <label for="comment" class="block text-sm font-medium text-white">Comentario</label>
            <textarea name="comment" id="comment" rows="4"
                      class="mt-1 w-full rounded-md bg-white border-gray-300 shadow-sm focus:ring-primary focus:border-primary text-form"
                      placeholder="Escribe tu experiencia...">{{ old('comment') }}</textarea>
            @error('comment')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Fotos --}}
        <div>
            <label class="block text-sm font-medium text-white">Fotos</label>
            <input type="file" name="photos[]" multiple accept="image/*"
                   class="mt-1 w-full text-sm text-secondary file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0 file:text-sm file:font-semibold
                          file:bg-primary/20 file:text-primary hover:file:bg-primary/30">
            @error('photos')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit"
                class="w-full justify-center px-4 py-2 rounded-md font-semibold text-form bg-white hover:bg-primary/80 transition">
            Enviar reseña
        </button>
    </form>
</div>
