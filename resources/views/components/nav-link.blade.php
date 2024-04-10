@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-1 text-warning-400 dark:text-warning-200'
            : 'inline-flex items-center px-1 text-gray-600 dark:text-gray-200 hover:text-warning-400 dark:hover:text-warning-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
