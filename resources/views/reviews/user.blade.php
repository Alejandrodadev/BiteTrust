<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <h2 class="text-2xl font-bold text-primary mb-6">Mis Reseñas</h2>

        @if (session('success'))
            <div class="mb-4 p-3 bg-success/10 border border-success text-success rounded">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($reviews as $review)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-md p-5 mb-5 flex flex-col md:flex-row md:items-start md:justify-between">
                {{-- IZQUIERDA: Texto --}}
                <div class="md:flex-1 mb-4 md:mb-0 pr-4">
                    <h3 class="font-semibold text-lg text-primary mb-1">
                        {{ $review->restaurant->name }}
                    </h3>
                    <div class="flex flex-wrap items-center text-sm text-secondary mb-2 space-x-4">
                        <span>{{ $review->rating }} ⭐</span>
                        <span>{{ $review->created_at->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-sm text-secondary">
                        {{ $review->comment }}
                    </p>
                </div>

                {{-- DERECHA: Hasta 3 miniaturas --}}
                @if ($review->photos->count())
                    <div class="flex-shrink-0 grid grid-cols-3 gap-2">
                        @foreach ($review->photos->take(3) as $photo)
                            <img src="{{ asset($photo->thumbnail_url) }}"
                                alt="Foto reseña"
                                class="w-20 h-20 object-cover rounded border shadow-sm">
                        @endforeach
                    </div>
                @endif

                {{-- ACCIONES debajo en móvil, a la derecha en escritorio --}}
                <div class="mt-4 md:mt-2 md:ml-6 flex items-center gap-4">
                    <a href="{{ route('reviews.edit', $review) }}"
                       class="text-sm text-primary font-medium hover:underline">
                        Editar
                    </a>
                    <form action="{{ route('reviews.destroy', $review) }}"
                          method="POST"
                          onsubmit="return confirm('¿Seguro que deseas eliminar esta reseña?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm text-error font-medium hover:underline">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-secondary">No has publicado ninguna reseña aún.</p>
        @endforelse

        {{-- paginación --}}
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>
</x-app-layout>
