<header class="sticky top-0 z-30 w-full shadow-md print:hidden">
    {{-- menu --}}
    <livewire:navigation-menu />

    {{-- heading --}}
    @if (isset($heading))
        <div class="flex bg-neutral-200 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-200">
            <div class="flex-1 p-2 mx-auto" aria-label="Page Heading">
                <x-ts-icon icon="tabler.arrow-bar-right" class="inline-block size-5" alt="Arrow Icon" />
                {{ $heading }}
            </div>

            <div class="flex-1 p-2 mx-auto text-end" aria-label="Current Date">
                <time datetime="{{ Carbon\Carbon::today()->toDateString() }}">
                    {{ Carbon\Carbon::today()->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') }}
                </time>
            </div>
        </div>
    @endif
</header>
