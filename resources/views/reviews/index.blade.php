@extends('layouts.app')

@section('title', 'Reviews')

@section ('content')

    @foreach($reviews as $item)
        <li>
            <a href="{{ route('reviews.show', $item->id) }}">
                {{ $item->comment }}
            </a>
        </li>
    @endforeach

@endsection
