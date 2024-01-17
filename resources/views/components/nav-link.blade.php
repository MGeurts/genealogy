@props(['active'])

@php
    $classes = $active ?? false ? 'inline-flex items-center px-1 text-warning dark:text-warning-300 hover:text-warning-700' : 'inline-flex items-center px-1 text-gray dark:text-gray-300 hover:text-warning-500 dark:hover:text-warning-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
