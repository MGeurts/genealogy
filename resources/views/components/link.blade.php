@php
    $classes = 'text-primary dark:text-primary-300 underline decoration-transparent transition duration-300 ease-in-out hover:decoration-inherit';
@endphp

<a {{ $attributes->merge(['href' => '#', 'class' => $classes]) }}>
    {{ $slot }}
</a>
