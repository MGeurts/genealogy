@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'peer block min-h-[auto] w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm px-3 py-[0.32rem]']) !!}>
    {{ $slot }}
</textarea>
