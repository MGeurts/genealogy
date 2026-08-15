<header class="sticky top-0 z-30 w-full shadow-md print:hidden">
    {{-- menu --}}
    <livewire:livewire::navigation-menu />

    {{-- breadcrumbs & date --}}
    <div class="flex bg-neutral-200 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-200">
        <div class="mx-auto flex-1 p-2" aria-label="Page Heading">
            <x-ts-breadcrumbs lg separator="|" />
        </div>

        <div class="mx-auto flex-1 p-2 text-end" aria-label="Current Date">
            <time datetime="{{ Carbon\Carbon::today()->toDateString() }}">
                {{ Carbon\Carbon::today()->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') }}
            </time>
        </div>
    </div>
</header>
