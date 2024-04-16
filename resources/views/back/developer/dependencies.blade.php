@section('title')
    &vert; {{ __('app.dependencies') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.dependencies') }}
        </h2>
    </x-slot>

    <div class="grow max-w-5xl overflow-x-auto py-5 dark:text-neutral-200">
        <x-ts-tab selected="Laravel">
            {{-- laravel --}}
            <x-ts-tab.items tab="Laravel">
                <ul class="w-full ml-4">
                    <li class="py-2">
                        <x-link href="https://laravel.com/" target="_blank">Laravel</x-link> 11
                    </li>
                    <li class="py-2">
                        <x-link href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream</x-link>5 (featuring <x-link href="https://jetstream.laravel.com/features/teams.html"
                            target="_blank">Teams</x-link>)
                    </li>
                    <li class="py-2">
                        <x-link href="https://livewire.laravel.com/" target="_blank">Laravel Livewire</x-link> 3
                    </li>
                    <li class="py-2">
                        <x-link href="https://filamentphp.com/" target="_blank">Laravel Filament</x-link>3 (only Table Builder)
                    </li>
                </ul>
            </x-ts-tab.items>

            {{-- tailwind --}}
            <x-ts-tab.items tab="Tailwind">
                <ul class="w-full ml-4">
                    <li class="py-2">
                        <x-link href="https://tailwindcss.com/docs/" target="_blank">Tailwind CSS</x-link>
                    </li>
                    <li class="py-2">
                        <x-link href="https://tallstackui.com/" target="_blank">TallStackUI</x-link> (featuring <x-link href="https://tabler.io/icons" target="_blank">Tabler Icons</x-link>)
                    </li>
                </ul>
            </x-ts-tab.items>

            {{-- github --}}
            <x-ts-tab.items tab="Github">
                <ul class="w-full ml-4">
                    <li class="py-2">
                        <x-link href="https://github.com/csstools/postcss-plugins/" target="_blank">csstools/postcss-plugins</x-link> (needed by Filament Table Builder)
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/Intervention/image/" target="_blank">intervention/image</x-link>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/kevinkhill/lavacharts/" target="_blank">kevinkhill/lavacharts</x-link>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/korridor/laravel-has-many-merged/" target="_blank">korridor/laravel-has-many-merged</x-link>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/opcodesio/log-viewer/" target="_blank">opcodesio/log-viewer</x-link>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/spatie/laravel-backup/" target="_blank">spatie/laravel-backup</x-link>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/stevebauman/location/" target="_blank">stevebauman/location</x-link>
                    </li>

                    <x-hr.narrow class="w-full h-1 max-md:mx-auto my-4 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                    <li class="py-2">
                        <x-link href="https://github.com/stefangabos/world_countries/" target="_blank">stefangabos/world_countries</x-link> (candidate)
                    </li>
                </ul>
            </x-ts-tab.items>

            {{-- javascript --}}
            <x-ts-tab.items tab="Javascript">
                <ul class="w-full ml-4">
                    <li class="py-2">
                        <x-link href="https://www.chartjs.org/" target="_blank">Chart.js</x-link>
                    </li>
                </ul>
            </x-ts-tab.items>
        </x-ts-tab>
    </div>
</x-app-layout>
