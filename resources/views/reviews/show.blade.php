<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Show Review</title>

</head>
    <body>
        <p>{{ $review->comment }}</p>
        <p>Calificación: {{ $review->rating }} ⭐</p>
        <p>{{ $review->created_at }}</p>
        <a href="{{ route('reviews.index') }}">Back to Reviews</a>
        <a href="{{ route('reviews.edit', $review->id) }}">Edit Review</a>
        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Delete Review</button>
        </form>

    </body>
</html>
