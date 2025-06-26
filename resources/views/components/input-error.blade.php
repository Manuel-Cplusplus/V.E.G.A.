@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-base font-bold text-red-500 underline drop-shadow-[0_1px_1px_rgba(0,0,0,0.9)] space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif

