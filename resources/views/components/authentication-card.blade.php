@props(['logo' => '', 'header' => ''])

<div class="w-full min-h-192 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 mb-5">
    <div>
        {{ $logo }}
    </div>

    <div class="dark:text-gray-200">
        {{ $header }}
    </div>

    <div class="w-full sm:max-w-2xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded">
        {{ $slot }}
    </div>
</div>
