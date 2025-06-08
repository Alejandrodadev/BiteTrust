<x-app-layout>
    <div x-data="{ showPhotos: false }" class="mt-6 max-w-4xl mx-auto px-4">

        {{-- Card de info del restaurante --}}
        <section class="max-w-md ml-0 mb-2">
            <div class="bg-white rounded-md shadow-lg overflow-hidden">
                <div class="p-6 flex flex-col md:flex-row md:items-start md:gap-6">

                    {{-- Información principal --}}
                    <div class="flex-1 text-secondary text-xs">
                        <h1 class="text-2xl font-bold text-primary mb-2">{{ $restaurant->name }}</h1>

                        {{-- Etiquetas tipo --}}
                        @if($restaurant->types)
                            @php
                                $foodTypes = ['restaurant','food','bakery','meal_takeaway','meal_delivery','bar','cafe'];
                            @endphp
                            <div class="flex flex-wrap gap-2 mb-2">
                                @foreach($restaurant->types as $type)
                                    @if(in_array($type, $foodTypes))
                                        <span class="px-2 py-0.5 bg-gray-100 text-xs text-secondary rounded-full capitalize">{{ str_replace('_',' ',$type) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        {{-- Ratings --}}
                        <div class="flex items-center gap-4 text-sm mb-2">
                            <div class="flex items-center text-xs text-secondary gap-1">
                                <img src="{{ asset('img/logo2.png') }}" alt="BT" class="w-5 h-5">
                                <span>{{ number_format($restaurant->reviews_avg_rating,1) }}/5</span>
                                <span class="text-yellow-500">⭐</span>
                                <span>({{ $restaurant->reviews_count }} reseñas)</span>
                            </div>
                            @if($restaurant->ratingGoogle)
                                <div class="flex items-center gap-1 text-secondary">
                                    <img src="{{ asset('img/logo-google.png') }}" alt="G" class="w-5 h-5">
                                    <span>{{ number_format($restaurant->ratingGoogle,1) }}/5</span>
                                </div>
                            @endif
                        </div>

                        {{-- Dirección + Web --}}
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs text-secondary">{{ $restaurant->address }}📍</span>
                            @if($restaurant->website)
                                <a href="{{ $restaurant->website }}" target="_blank" rel="noopener noreferrer"
                                   class="hover:opacity-90 transition" title="Visitar sitio web">
                                    <img src="{{ asset('img/iconWeb.png') }}" alt="Web" class="w-5 h-5" />
                                </a>
                            @endif
                        </div>

                        {{-- Horario --}}
                        @if($restaurant->schedule)
                            <div x-data="{ showSchedule: false }" class="mt-1">
                                <button @click="showSchedule = !showSchedule"
                                        class="mt-1 text-xs text-primary hover:underline flex items-center gap-1">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-primary/10 text-primary rounded-full mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                                        </svg>
                                    </span>
                                    <span x-text="showSchedule ? 'Ocultar' : 'Horario'"></span>
                                </button>

                                <ul x-show="showSchedule" x-transition
                                    class="mt-1 text-xs text-secondary space-y-0.5" x-cloak>
                                    @foreach($restaurant->schedule as $line)
                                        <li>{{ $line }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- Botón y resultados del análisis --}}
        <div x-data="iaAnalysis('{{ route('restaurants.analysis', $restaurant->id) }}', {{ auth()->guest() ? 'true' : 'false' }})" class="mt-2 mb-2 max-w-md ml-0">
            <button
                @click="analizar"
                class="w-full px-3 py-1.5 border border-gray-300 text-primary text-xs bg-white rounded-md shadow-sm hover:bg-gray-100 transition">

                <template x-if="!loadingIA">
                    <span>🔍 Análisis IA</span>
                </template>
                <template x-if="loadingIA">
                    <span class="italic text-secondary">Leyendo reseñas...</span>
                </template>
            </button>

            @guest
                <div x-show="showInfo" x-transition class="mt-2 text-xs text-secondary bg-blue-200 border border-primaryLight rounded p-2">
                    La IA ya leyó cientos de reseñas por ti y sabe qué vas a pedir…
                    <a href="{{ route('access') }}" class=" hover:opacity-80 text-primary ">Inicia sesión</a> para descubrirlo 🤖
                </div>
            @endguest

            {{-- Card unificado de análisis --}}
            <template x-if="ia && (ia.analisis || (ia.platos && ia.platos.length > 0))">
                <div class="mt-4 p-4 bg-white border rounded shadow space-y-4 text-sm text-gray-800">

                    {{-- Análisis general --}}
                    <div>
                        <h3 class="text-base font-semibold text-primary mb-1">Opinion general</h3>
                        <p x-text="ia.analisis"></p>
                    </div>

                    {{-- Platos destacados --}}
                    <div x-show="ia.platos && ia.platos.length > 0" class="space-y-2">
                        <template x-for="plato in ia.platos" :key="plato.plato">
                            <div>
                                <h4 class="font-semibold text-primary">
                                    <span x-text="plato.plato"></span>
                                    <span class="text-xs text-secondary"> ( <span x-text="plato.menciones"></span> menciones )</span>
                                </h4>
                                <p class="text-secondary italic" x-text="(plato.frases_destacadas || []).join(' ')"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            {{-- Mensaje si no hay nada --}}
            <p class="text-sm text-gray-500 mt-4" x-show="ia && !ia.analisis && (!ia.platos || ia.platos.length === 0)">
                No se encontro suficiente informacion.
            </p>
        </div>


        {{-- Sección galería + modal ampliado --}}
        <section class="max-w-4xl mx-auto px-4 mt-4 mb-2"
            x-data='{ showPhotos: false, showModal: false, photos: @json($placePhotos), modalIndex: 0}'>
            @if(!empty($placePhotos))

                {{-- Botón Ver fotos --}}
                <button @click="showPhotos = !showPhotos"
                    class="inline-flex items-center gap-1 text-primary text-sm hover:underline mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 5h18M3 19h18M4 7h16M4 17h16"/>
                    </svg>
                    <span x-text="showPhotos ? 'Ocultar' : 'Ver fotos'"></span>
                </button>

                {{-- Galería en pequeño --}}
                <template x-if="showPhotos">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4">
                        <template x-for="(url, i) in photos" :key="i">
                            <img :src="url" class="w-full h-24 object-cover rounded-md shadow-sm cursor-pointer" @click="modalIndex = i; showModal = true"/>
                        </template>
                    </div>
                </template>

                {{-- Lightbox --}}
                <div x-show="showModal"
                    x-cloak
                    x-transition.opacity
                    @keydown.window.arrow-left.prevent="modalIndex = (modalIndex - 1 + photos.length) % photos.length"
                    @keydown.window.arrow-right.prevent="modalIndex = (modalIndex + 1) % photos.length"
                    @click.self="showModal = false"
                    class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
                    <div class="relative">
                        {{-- Cerrar --}}
                        <button @click="showModal = false"
                                class="absolute top-2 right-2 text-white text-3xl">&times;</button>

                        {{-- Anterior --}}
                        <button @click="modalIndex = (modalIndex - 1 + photos.length) % photos.length"
                                class="absolute left-2 top-1/2 transform -translate-y-1/2 text-white text-3xl p-2">&lsaquo;</button>

                        {{-- Imagen ampliada --}}
                        <img :src="photos[modalIndex]" class="max-w-full max-h-[80vh] rounded-md shadow-lg"/>

                        {{-- Siguiente --}}
                        <button @click="modalIndex = (modalIndex + 1) % photos.length"
                                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-white text-3xl p-2">&rsaquo;</button>
                    </div>
                </div>
            @endif
        </section>

        {{-- Reseñas propias --}}
        @foreach($reviews as $review)
            <div class="bg-white rounded-xl shadow p-4 mb-4">
                <div class="flex justify-between items-center mb-2">
                    <div class="flex items-center space-x-2">
                        @if ($review->user->avatar)
                            <img src="{{ asset('storage/'.$review->user->avatar) }}"
                                 class="w-8 h-8 rounded-full object-cover" />
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                <x-heroicon-o-user-circle class="w-5 h-5 text-gray-400" />
                            </div>
                        @endif
                        <span class="text-sm font-semibold text-primary">
                    {{ $review->user->name }}
                </span>
                    </div>
                    <span class="text-yellow-500 font-bold text-sm">
                {{ $review->rating }} ⭐
            </span>
                </div>

                <div x-data="toggleReview(@js($review->comment))" class="text-secondary text-sm mb-2">
                    <template x-if="!open">
                        <p x-text="short"></p>
                    </template>
                    <template x-if="open">
                        <p x-text="full"></p>
                    </template>

                    <template x-if="hasMore">
                        <button @click="toggle" class="text-primary text-xs hover:underline mt-1">
                            <span x-text="open ? 'Ver menos' : 'Ver más'"></span>
                        </button>
                    </template>
                </div>


                <p class="text-xs text-secondary mb-2">{{ $review->created_at->format('d/m/Y H:i') }}</p>

                @if($review->photos->count())
                    <div class="flex flex-wrap gap-2 mb-2 review-photos"
                         data-photos='@json($review->photos->map(fn($p) => asset($p->photo_url)))'>
                        @foreach($review->photos as $photo)
                            <img src="{{ asset($photo->thumbnail_url) }}"
                                 data-index="{{ $loop->index }}"
                                 class="w-16 h-16 object-cover rounded border shadow-sm cursor-pointer hover:scale-110 transition-transform"
                                 alt="Miniatura {{ $loop->index + 1 }}" />
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        {{-- Reseñas externas --}}
        @foreach($externalReviews as $gReview)
            <div class="bg-white rounded-xl shadow p-4 mb-4" x-data="{ openComment: false }">
                <div class="flex justify-between items-center mb-2">

                    {{-- Avatar + Nombre --}}
                    <div class="flex items-center space-x-2">
                        @if($gReview->profile_photo_url)
                            <img src="{{ $gReview->profile_photo_url }}"
                                 alt="{{ $gReview->author_name }}"
                                 class="w-8 h-8 rounded-full object-cover" />
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                <x-heroicon-o-user-circle class="w-5 h-5 text-gray-400" />
                            </div>
                        @endif
                        <span class="text-sm font-semibold text-gray-800">
                    {{ $gReview->author_name }}
                </span>
                    </div>
                    <span class="text-yellow-500 font-bold text-sm">
                {{ $gReview->rating }} ⭐
            </span>
                </div>

                {{-- From Google --}}
                <div class="flex items-center text-xs text-gray-500 mb-2">
                    <img src="{{ asset('img/logo-google.png') }}"
                         alt="Google Logo"
                         class="w-4 h-4 ml-1" />
                </div>

                {{-- Comentario resumido / completo --}}
                <div x-data="toggleReview(@js($gReview->text))" class="text-secondary text-sm mb-2">
                    <template x-if="!open">
                        <p x-text="short"></p>
                    </template>
                    <template x-if="open">
                        <p x-text="full"></p>
                    </template>

                    <template x-if="hasMore">
                        <button @click="toggle" class="text-primary text-xs hover:underline mt-1">
                            <span x-text="open ? 'Ver menos' : 'Ver más'"></span>
                        </button>
                    </template>
                </div>

                {{-- Fecha --}}
                <p class="text-xs text-gray-500">{{ $gReview->relative_time_description }}</p>
            </div>
        @endforeach

        {{-- Paginación --}}
        <div class="mt-6">
            {!! $reviews->links() !!}
        </div>

        {{-- Formulario para dejar nueva reseña --}}
        @auth
            <div id="review-form" class="mt-6 mb-4">

                <x-alert type="error" :message="session('error')" />
                <x-alert type="success" :message="session('success')" />

                <x-review.form :restaurant="$restaurant" />
            </div>
        @else
            <div class="mt-6 mb-4 text-center">
                <p class="text-sm text-primary">
                    ¿Visitaste este restaurante? Tu opinión ayuda a otros.
                    <a href="{{ route('access') }}" class="underline hover:opacity-80">Inicia sesión</a> para dejar tu reseña.
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
    @push('scripts')
        <script src="{{ asset('js/lightbox.js') }}"></script>
    @endpush

    <!-- Footer -->
    <footer class="bg-white border-t mt-8">
        <div class="max-w-7xl mx-auto px-4 py-6 text-center text-sm text-secondary">
            © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
        </div>
    </footer>
</x-app-layout>
