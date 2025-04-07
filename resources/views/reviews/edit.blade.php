<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Editar Reseña</title>
    </head>
    <body>
    <h1>Editar Reseña</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
   @endif

        <form action="{{ route('reviews.update', $review->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="rating">Calificación:</label>
            <select name="rating" id="rating">
                <option value="1" {{ old('rating', $review->rating) == 1 ? 'selected' : '' }}>1</option>
                <option value="2" {{ old('rating', $review->rating) == 2 ? 'selected' : '' }}>2</option>
                <option value="3" {{ old('rating', $review->rating) == 3 ? 'selected' : '' }}>3</option>
                <option value="4" {{ old('rating', $review->rating) == 4 ? 'selected' : '' }}>4</option>
                <option value="5" {{ old('rating', $review->rating) == 5 ? 'selected' : '' }}>5</option>
            </select>
        </div>

        <div>
            <label for="comment">Comentario:</label>
                <textarea name="comment" id="comment" rows="4" cols="50">{{ old('comment', $review->comment) }}</textarea>
                </div>

                @error('comment')
                <p style="color: red;">{{ $message }}</p>
                @enderror
                @error('rating')
                <p style="color: red;">{{ $message }}</p>
                @enderror

                <button type="submit">Actualizar Reseña</button>
            </form>
    </body>
</html>
