@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-1 text-yellow-500 dark:text-yellow-200'
            : 'inline-flex items-center px-1 text-gray-600 dark:text-gray-200 hover:text-yellow-500 dark:hover:text-yellow-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
