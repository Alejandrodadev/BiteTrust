{{-- resources/views/restaurants/show.blade.php --}}
@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-10 text-primary">
        {{-- Nombre y dirección del restaurante --}}
        <h1 class="text-3xl font-bold mb-2">{{ $restaurant->name }}</h1>
        <p class="text-secondary mb-8">
            {{ $restaurant->address }} — {{ $restaurant->city }}, {{ $restaurant->country }}</p>

        {{-- Mensajes flash --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded bg-success/10 text-success border border-success/40">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 rounded bg-error/10 text-error border border-error/40">
                {{ session('error') }}
            </div>
        @endif

        {{-- Reseñas paginadas --}}
        @foreach($reviews as $review)
            <div class="bg-white rounded-xl shadow p-4 mb-4">
                <div class="flex justify-between items-center mb-2">
                    {{-- Avatar + Nombre --}}
                    <div class="flex items-center space-x-2">
                        @if ($review->user->avatar)
                            <img src="{{ asset('storage/'.$review->user->avatar) }}"
                                 class="w-8 h-8 rounded-full object-cover" />
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                <x-heroicon-o-user-circle class="w-5 h-5 text-gray-400" />
                            </div>
                        @endif
                        <h4 class="text-primary font-semibold text-sm">{{ $review->user->name }}</h4>
                    </div>
                    {{-- Puntuación --}}
                    <span class="text-yellow-500 font-bold text-sm">{{ $review->rating }} ⭐</span>
                </div>

                {{-- Comentario con “Ver más” --}}
                <div x-data="{ open: false }" class="text-secondary text-sm mb-2">
                    <template x-if="!open">
                        <p>{{ Str::limit($review->comment, 250) }}
                            @if(strlen($review->comment) > 250)
                                <button @click="open = true" class="text-primary text-xs hover:underline">
                                    Ver más
                                </button>
                            @endif
                        </p>
                    </template>
                    <template x-if="open">
                        <p>{{ $review->comment }}
                            <button @click="open = false" class="text-primary text-xs hover:underline">
                                Ver menos
                            </button>
                        </p>
                    </template>
                </div>

                {{-- Fecha --}}
                <p class="text-xs text-secondary mb-2"> {{ $review->created_at->format('d/m/Y H:i') }}</p>

                {{-- Fotos con JavaScript puro --}}
                @if($review->photos->count())
                    {{-- Contenedor de miniaturas --}}
                    <div class="flex flex-wrap gap-2 mb-2 review-photos"
                        data-photos='@json($review->photos->map(fn($p) => asset($p->photo_url)))'>
                        @foreach($review->photos as $photo)
                            <img src="{{ asset($photo->thumbnail_url) }}"
                                data-index="{{ $loop->index }}"
                                class="w-16 h-16 object-cover rounded border shadow-sm cursor-pointer transform transition-transform duration-200 ease-in-out hover:scale-110"
                                alt="Miniatura {{ $loop->index + 1 }}"/>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        {{-- Paginación --}}
        <div class="mt-6">
            {!! $reviews->links() !!}
        </div>

        {{-- Formulario para dejar nueva reseña --}}
        @auth
            <div class="mt-10 bg-white dark:bg-gray-800 rounded-xl shadow p-6">
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
                        <button
                            type="button"
                            @click="open = !open"
                            @click.away="open = false"
                            class="w-full text-left px-4 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:!ring-primary focus:!border-primary"><span
                            x-text="options.find(o => o.value === selected)?.label || 'Selecciona la calificación'"
                            :class="selected ? 'text-primary' : 'text-secondary'"></span>
                            <svg class="w-4 h-4 float-right mt-1 text-secondary"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"/></svg>
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
                        <textarea name="comment"
                                  id="comment"
                                  rows="4"
                                  class="mt-1 w-full rounded-md bg-white border-gray-300 shadow-sm focus:ring-primary focus:border-primary text-form"
                                  placeholder="Escribe tu experiencia...">{{ old('comment') }}</textarea>
                        @error('comment')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Fotos --}}
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
                                      hover:file:bg-primary/30">
                        @error('photos')<p class="text-sm text-error mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="w-full justify-center px-4 py-2 rounded-md font-semibold text-form bg-white hover:bg-primary/80 transition">
                        Enviar reseña
                    </button>
                </form>
            </div>
        @else
            <div class="mt-10 text-center">
                <p class="text-sm text-primary">
                    ¿Quieres dejar una reseña?
                    <a href="{{ route('access') }}" class="underline hover:opacity-80">Inicia sesión</a>.
                </p>
            </div>
        @endauth
    </div>

    {{-- Modal global --}}
    <div
        id="image-modal"
        class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50">
        <button id="modal-close" class="absolute top-4 right-4 text-white text-3xl">&times;</button>
        <button id="modal-prev"  class="absolute left-4  text-white text-3xl p-2">&lsaquo;</button>
        <img id="modal-img" src="" class="object-contain max-h-[80vh] max-w-[80vw] rounded"/>
        <button id="modal-next"  class="absolute right-4 text-white text-3xl p-2">&rsaquo;</button>
    </div>

    {{-- Script del lightbox --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal       = document.getElementById('image-modal');
            const modalImg    = document.getElementById('modal-img');
            const btnClose    = document.getElementById('modal-close');
            const btnPrev     = document.getElementById('modal-prev');
            const btnNext     = document.getElementById('modal-next');
            let currentPhotos = [];
            let currentIndex  = 0;

            document.querySelectorAll('.review-photos').forEach(container => {
                const photos = JSON.parse(container.dataset.photos);
                container.querySelectorAll('img[data-index]').forEach(img => {
                    img.addEventListener('click', () => {
                        currentPhotos = photos;
                        currentIndex  = parseInt(img.dataset.index, 10);
                        modalImg.src  = currentPhotos[currentIndex];
                        modal.classList.remove('hidden');
                    });
                });
            });

            btnClose.addEventListener('click', () => modal.classList.add('hidden'));
            btnPrev.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + currentPhotos.length) % currentPhotos.length;
                modalImg.src = currentPhotos[currentIndex];
            });
            btnNext.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % currentPhotos.length;
                modalImg.src = currentPhotos[currentIndex];
            });

            modal.addEventListener('click', e => {
                if (e.target === modal) modal.classList.add('hidden');
            });
            // --- Navegación por teclado ---
            document.addEventListener('keydown', e => {
                if (modal.classList.contains('hidden')) return;

                switch (e.key) {
                    case 'ArrowLeft':
                        btnPrev.click();
                        break;
                    case 'ArrowRight':
                        btnNext.click();
                        break;
                    case 'Escape':
                        btnClose.click();
                        break;
                }
            });
        });
    </script>
</x-app-layout>
