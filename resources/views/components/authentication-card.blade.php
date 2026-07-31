@props(['logo' => '', 'header' => ''])

<div class="mb-5 flex min-h-192 w-full flex-col items-center pt-6 sm:justify-center sm:pt-0">
    <div>{{ $logo }}</div>

    <div class="flex w-full overflow-hidden rounded-sm shadow-md sm:max-w-4xl">
        <div class="hidden w-full md:block md:w-1/3">
            <img
                src="img/genealogy-research.webp"
                alt="Genealogy Research"
                class="h-full w-full rounded-l object-cover"
            />
        </div>

        <div class="mx-auto w-full rounded-r bg-white p-8 md:w-2/3">
            <h1 class="mb-8 text-4xl font-bold">{{ $header }}</h1>

            {{ $slot }}
        </div>
    </div>
</div>
