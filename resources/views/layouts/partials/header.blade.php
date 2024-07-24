<header class="sticky top-0 z-10 w-full shadow-md print:hidden">
    {{-- menu --}}
    <livewire:navigation-menu />

    {{-- heading --}}
    @if (isset($heading))
        <div class="flex">
            <div class="flex-1 p-2 mx-auto shadow bg-neutral-200 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-200">
                <x-ts-icon icon="arrow-bar-right" class="inline-block size-5" />
                {{ $heading }} 
            </div>

            <div class="flex-1 p-2 mx-auto shadow text-end bg-neutral-200 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-200">
                {{ Carbon\Carbon::parse(now())->isoFormat('LL')  }}
            </div>
        </div>
    @endif
</header>
