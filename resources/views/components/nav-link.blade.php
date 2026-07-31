@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-teal-500 to-teal-400 shadow transition duration-150 ease-in-out'
            : 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold text-teal-800 bg-teal-50 hover:bg-teal-100 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
