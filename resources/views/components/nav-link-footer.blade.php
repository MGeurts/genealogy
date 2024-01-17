@props(['active'])

@php
    $classes = $active ?? false ? 'text-warning dark:text-warning-300 underline decoration-transparent transition duration-300 ease-in-out hover:decoration-inherit' : 'text-primary dark:text-primary-300 underline decoration-transparent transition duration-300 ease-in-out hover:decoration-inherit';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
