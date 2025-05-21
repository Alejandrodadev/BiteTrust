<x-app-layout>
    <div class="max-w-2xl mx-auto mt-10 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold mb-4 text-primary">Edita tu reseña</h3>

        @if(session('success'))
            <div class="mb-6 p-4 rounded bg-success/10 text-success border border-success/40">
                {{ session('success') }}
            </div>
        @endif

        {{-- Fotos actuales --}}
        @if ($review->photos->count())
            <div class="mb-6">
                <label class="block text-sm font-medium text-white mb-2">Fotos actuales</label>
                <div class="flex flex-wrap gap-3">
                    @foreach ($review->photos as $photo)
                        <div class="relative inline-block">
                            <a href="{{ asset($photo->photo_url) }}" target="_blank">
                                <img src="{{ asset($photo->thumbnail_url) }}"
                                     alt="Miniatura"
                                     class="w-20 h-20 object-cover rounded border shadow-sm hover:scale-105 transition" />
                            </a>
                            <form action="{{ route('photos.destroy', ['review' => $review->id, 'photo' => $photo->id]) }}"
                                  method="POST"
                                  class="absolute top-0 right-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-xs text-white bg-red-500 rounded-full p-1 hover:bg-red-600 transition">
                                    &times;
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Formulario de edición con mismo estilo --}}
        <form action="{{ route('reviews.update', $review->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Calificación (dropdown Alpine idéntico) --}}
            <div x-data="{
                    open: false,
                    selected: '{{ old('rating', $review->rating) }}',
                    options: [
                      { value: '', label: 'Selecciona la calificación' },
                      { value: '5', label: '5 ⭐' },
                      { value: '4', label: '4 ⭐' },
                      { value: '3', label: '3 ⭐' },
                      { value: '2', label: '2 ⭐' },
                      { value: '1', label: '1 ⭐' }
                    ],
                    get selectedLabel() {
                      const match = this.options.find(o => o.value === this.selected);
                      return match ? match.label : 'Selecciona la calificación';
                    }
                  }"
                 class="relative">
                <label for="rating" class="block text-sm font-medium text-white mb-1">Calificación</label>
                <button type="button"
                        @click="open = !open"
                        @click.away="open = false"
                        class="w-full text-left px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm text-form focus:!ring-primary focus:!border-primary">
                    <span x-text="selectedLabel"></span>
                    <svg class="w-4 h-4 float-right mt-1 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <ul x-show="open" x-transition
                    class="absolute z-10 mt-1 w-full bg-white border border-primary rounded-md shadow-md max-h-60 overflow-auto">
                    <template x-for="option in options" :key="option.value">
                        <li @click="selected = option.value; open = false"
                            :class="{ 'bg-gray-100': selected === option.value }"
                            class="px-4 py-2 text-form hover:bg-primaryLight hover:shadow-md cursor-pointer transition"
                            x-text="option.label">
                        </li>
                    </template>
                </ul>
                <input type="hidden" name="rating" :value="selected">
                @error('rating')
                <p class="text-sm text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Comentario --}}
            <div>
                <label for="comment" class="block text-sm font-medium text-white">Comentario</label>
                <textarea name="comment"
                          id="comment"
                          rows="4"
                          class="mt-1 w-full rounded-md bg-white border-gray-300 shadow-sm focus:ring-primary focus:border-primary text-form"
                          placeholder="Actualiza tu experiencia...">{{ old('comment', $review->comment) }}</textarea>
                @error('comment')
                <p class="text-sm text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Añadir nuevas fotos --}}
            <div>
                <label class="block text-sm font-medium text-white">Fotos</label>
                <input type="file"
                       name="photos[]"
                       multiple
                       accept="image/*"
                       class="mt-1 w-full text-sm text-secondary
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-primary/20 file:text-primary
                              hover:file:bg-primary/30
                              dark:file:bg-primary/30 dark:file:text-white dark:hover:file:bg-primary/50">
                @error('photos')
                <p class="text-sm text-error mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botón igual al de crear --}}
            <button type="submit"
                    class="w-full justify-center px-4 py-2 rounded-md font-semibold text-form bg-white hover:bg-primary/80 transition">
                Actualizar reseña
            </button>
        </form>
    </div>
</x-app-layout>
