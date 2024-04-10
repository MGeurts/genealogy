@php
    $classes = 'text-blue-600 dark:text-blue-200 underline decoration-transparent transition duration-300 ease-in-out hover:decoration-inherit';
@endphp

<a {{ $attributes->merge(['href' => '#', 'class' => $classes]) }}>
    {{ $slot }}
</a>
