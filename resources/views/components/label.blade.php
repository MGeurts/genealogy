@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-gray-700 dark:text-gray-500']) }}>
    {{ $value ?? $slot }}
</label>
