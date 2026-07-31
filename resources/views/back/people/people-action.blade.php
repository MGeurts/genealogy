@section('title')
    &vert; {{ $title }}
@endsection

<x-app-layout>
    <div class="sticky top-[6.5rem] z-20 bg-gray-100 p-2 pb-5 dark:bg-gray-900">
        <livewire:people::heading :person="$person" />
    </div>

    <div class="w-full space-y-5 overflow-x-auto p-2">
        <div class="flex flex-wrap gap-5">
            <div class="flex min-w-[25rem] grow flex-col gap-5 md:max-w-max">
                <livewire:people::profile :person="$person" />
            </div>

            <div class="flex min-w-[25rem] grow flex-col gap-5 md:max-w-max">
                <livewire:people::family :person="$person" />
                <livewire:people::partners :person="$person" />
                <livewire:people::children :person="$person" />
                <livewire:people::siblings :person="$person" />
                <livewire:people::files :person="$person" />
            </div>

            <div class="flex min-w-[25rem] grow flex-col gap-5 overflow-x-auto md:max-w-max">
                <livewire:dynamic-component :is="$component" v-bind="$componentProps" />
            </div>
        </div>
    </div>
</x-app-layout>
