@props(['type' => 'info', 'message' => null])

@php
    $styles = [
        'success' => 'bg-green-100 border border-green-400 text-green-500',
        'error'   => 'bg-red-100 border border-red-400 text-red-500',
        'info'    => 'bg-blue-100 border border-blue-400 text-blue-500',
        'warning' => 'bg-yellow-100 border border-yellow-400 text-yellow-500',
    ];
@endphp

@if($message)
    <div class="{{ $styles[$type] ?? $styles['info'] }} px-4 py-2 rounded mb-4 text-sm">
        {{ $message }}
    </div>
@endif

@if($type === 'error' && $errors->any())
    <div class="{{ $styles['error'] }} px-4 py-2 rounded mb-4 text-sm">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
