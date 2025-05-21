<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold text-text_primary mb-4">{{ $restaurant->name }}</h1>
        <p class="text-gray-600 mb-8">{{ $restaurant->address }} - {{ $restaurant->city }}, {{ $restaurant->country }}</p>

        <h2 class="text-xl font-semibold mb-4">Reseñas:</h2>

        @forelse ($reviews as $review)
            <div class="mb-6 p-4 bg-white shadow rounded">
                <div class="flex justify-between mb-2">
                    <span class="text-text_primary font-semibold">{{ $review->user->name ?? 'Anónimo' }}</span>
                    <span class="text-yellow-500">{{ $review->rating }} ⭐</span>
                </div>
                <p class="text-gray-700">{{ $review->comment }}</p>
                <p class="text-sm text-gray-400 mt-2">{{ $review->created_at->format('d/m/Y H:i') }}</p>
            </div>
        @empty
            <p class="text-gray-500">Aún no hay reseñas para este restaurante.</p>
        @endforelse
    </div>
</x-app-layout>
