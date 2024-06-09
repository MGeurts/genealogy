<header class="print:hidden sticky top-0 w-full shadow-md z-10">
    {{-- menu --}}
    <livewire:navigation-menu />

    {{-- heading --}}
    @if (isset($heading))
        <div class="mx-auto py-2 px-2 md:px-5 bg-neutral-200 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-200 shadow">
            {{ $heading }}
        </div>
    @endif
</header>
