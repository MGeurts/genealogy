@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'text-warning-400 dark:text-warning-200 underline decoration-transparent transition duration-300 ease-in-out hover:decoration-inherit'
            : 'text-blue-600 dark:text-blue-200 underline decoration-transparent transition duration-300 ease-in-out hover:decoration-inherit';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
