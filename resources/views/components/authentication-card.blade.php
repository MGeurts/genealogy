@props(['logo' => '', 'header' => ''])

<div class="w-full min-h-192 flex flex-col sm:justify-center items-center pt-6 sm:pt-0 mb-5">
    <div>
        {{ $logo }}
    </div>

    <div class="flex w-full sm:max-w-4xl shadow-md overflow-hidden rounded">
        <div class="w-full md:w-1/3 hidden md:block">
            <img src="img/genealogy-research.webp" alt="Genealogy Research" class="object-cover w-full h-full rounded-l">
        </div>

        <div class="w-full md:w-2/3 p-8 mx-auto bg-white rounded-r">
            <h1 class="text-4xl font-bold mb-8">{{ $header }}</h1>

            {{ $slot }}
        </div>
    </div>
</div>
