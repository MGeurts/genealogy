@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => $disabled
        ? 'block w-full min-h-[auto] rounded-sm border-gray-300 bg-gray-100 text-gray-500 shadow-xs px-3 py-[0.32rem] cursor-not-allowed peer'
        : 'block w-full min-h-[auto] rounded-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-xs px-3 py-[0.32rem] peer',
]) !!}>
    {{ $slot }}
</textarea>
