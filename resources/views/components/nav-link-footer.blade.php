@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'text-yellow-500 dark:text-yellow-200 underline decoration-transparent transition duration-300 ease-in-out hover:decoration-inherit'
            : 'text-blue-600 dark:text-blue-200 underline decoration-transparent transition duration-300 ease-in-out hover:decoration-inherit';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
