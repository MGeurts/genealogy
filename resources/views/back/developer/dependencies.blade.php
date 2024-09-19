@section('title')
    &vert; {{ __('app.dependencies') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.dependencies') }}
    </x-slot>

    <div class="max-w-5xl py-5 overflow-x-auto grow dark:text-neutral-200">
        <x-ts-tab selected="TallStack">
            {{-- tallstack --}}
            <x-ts-tab.items tab="TallStack">
                <div class="grid gap-4 md:grid-cols-2">
                    <ul class="w-full ml-4">
                        <li class="py-2">
                            <x-link href="https://laravel.com/" target="_blank">Laravel</x-link> 11
                            <x-ts-button xs href="https://laravel.com/docs/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                        </li>
                        <li class="py-2">
                            <x-link href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream</x-link>5 (featuring <x-link href="https://jetstream.laravel.com/features/teams.html"
                                target="_blank">Teams</x-link>)
                            </li>
                        <li class="py-2">
                            <x-link href="https://livewire.laravel.com/" target="_blank">Laravel Livewire</x-link> 3
                            <x-ts-button xs href="https://livewire.laravel.com/docs/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                        </li>
                        <li class="py-2">
                            <x-link href="https://alpinejs.dev/" target="_blank">Alpine.js</x-link> 3
                            <x-ts-button xs href="https://alpinejs.dev/start-here/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                        </li>
                        <li class="py-2">
                            <x-link href="https://filamentphp.com/" target="_blank">Laravel Filament</x-link>3 (only Table Builder)
                            <x-ts-button xs href="https://filamentphp.com/docs/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                        </li>

                        <li class="py-2">
                            <x-link href="https://tailwindcss.com/" target="_blank">Tailwind CSS</x-link>
                            <x-ts-button xs href="https://tailwindcss.com/docs/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                        </li>
                        <li class="py-2">
                            <x-link href="https://tallstackui.com/" target="_blank">TallStackUI</x-link> (featuring <x-link href="https://tabler.io/icons" target="_blank">Tabler Icons</x-link>)
                            <x-ts-button xs href="https://tallstackui.com/docs/get-started/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                        </li>
                    </ul>

                    <div class="grid grid-cols-4 gap-4 mt-4 ml-4 max-w-96 justify-items-center">
                        <div class="content-center max-w-24">
                            <img src="{{ url('img/logo/laravel.webp') }}" class="rounded" alt="laravel" title="Laravel" />
                        </div>
                        <div class="content-center max-w-24">
                            <img src="{{ url('img/logo/livewire.webp') }}" class="rounded" alt="livewire" title="Livewire" />
                        </div>
                        <div class="content-center max-w-24">
                            <img src="{{ url('img/logo/alpinejs.webp') }}" class="rounded" alt="alpinejs" title="alpine.js" />
                        </div>
                        <div class="content-center max-w-24">
                            <img src="{{ url('img/logo/tailwindcss.webp') }}" class="rounded" alt="tailwindcss" title="Tailwind CSS" />
                        </div>
                        <div class="content-center col-span-2 max-w-24">
                            <img src="{{ url('img/logo/tallstackui.webp') }}" class="rounded" alt="talstackui" title="TallStackUI" />
                        </div>
                        <div class="content-center col-span-2 max-w-24">
                            <img src="{{ url('img/logo/filament.webp') }}" class="rounded" alt="filament" title="Filament" />
                        </div>
                    </div>
                </div>
            </x-ts-tab.items>

            {{-- github --}}
            <x-ts-tab.items tab="Github">
                <ul class="w-full ml-4">
                    <li class="py-2">
                        <x-link href="https://github.com/barryvdh/laravel-ide-helper/" target="_blank">barryvdh/laravel-ide-helper</x-link>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/csstools/postcss-plugins/" target="_blank">csstools/postcss-plugins</x-link> (needed by Filament Table Builder)
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/Intervention/image/" target="_blank">intervention/image</x-link>
                        <x-ts-button xs href="https://image.intervention.io/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/kevinkhill/lavacharts/" target="_blank">kevinkhill/lavacharts</x-link>
                        <x-ts-button xs href="https://lavacharts.com/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/korridor/laravel-has-many-merged/" target="_blank">korridor/laravel-has-many-merged</x-link>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/opcodesio/log-viewer/" target="_blank">opcodesio/log-viewer</x-link>
                        <x-ts-button xs href="https://log-viewer.opcodes.io/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/spatie/laravel-activitylog/" target="_blank">spatie/activity-log</x-link>
                        <x-ts-button xs href="https://spatie.be/docs/laravel-activitylog/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/spatie/laravel-backup/" target="_blank">spatie/laravel-backup</x-link>
                        <x-ts-button xs href="https://spatie.be/docs/laravel-backup/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/spatie/laravel-medialibrary/" target="_blank">spatie/laravel-medialibrary</x-link>
                        <x-ts-button xs href="https://spatie.be/docs/laravel-medialibrary/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/stefangabos/world_countries/" target="_blank">stefangabos/world_countries</x-link>
                        <x-ts-button xs href="https://stefangabos.github.io/world_countries/" target="_blank" class="ms-5">{{ __('app.documentation') }}</x-ts-button>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/stevebauman/location/" target="_blank">stevebauman/location</x-link>
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

            {{-- 3rd Party --}}
            <x-ts-tab.items tab="3rd-Party">
                <ul class="w-full ml-4">
                    <li class="py-2">
                        <x-link href="https://tabler.io/icons/" target="_blank">tabler.io</x-link> - Icons
                    </li>
                </ul>
                <ul class="w-full ml-4">
                    <li class="py-2">
                        <x-link href="https://www.svgrepo.com/" target="_blank">svgrepo.com</x-link> - SVG Repository
                    </li>
                </ul>
            </x-ts-tab.items>

            {{-- gedcom --}}
            <x-ts-tab.items tab="GEDCOM">
                <ul class="w-full ml-4">
                    <li class="py-2">
                        <x-link href="https://gedcom.io/" target="_blank">FamilySearch GEDCOM</x-link>
                    </li>
                    <li class="py-2">
                        <x-link href="https://gedcom.io/specs/" target="_blank">FamilySearch GEDCOM - Specifications</x-link>
                    </li>
                    <li class="py-2">
                        <x-link href="https://gedcom.io/tools/" target="_blank">FamilySearch GEDCOM - Tools</x-link>
                    </li>

                    <br />
                    <hr />
                    <br />

                    <li class="py-2">
                        <x-link href="https://github.com/liberu-genealogy/php-gedcom" target="_blank">liberu-genealogy/php-gedcom</x-link>
                        <x-ts-badge xs color="secondary" class="ms-5">Possible candidate</x-ts-badge>
                    </li>
                    <li class="py-2">
                        <x-link href="https://github.com/liberu-genealogy/laravel-gedcom" target="_blank">liberu-genealogy/laravel-gedcom</x-link>
                        <x-ts-badge xs color="secondary" class="ms-5">Possible candidate</x-ts-badge>
                    </li>
                </ul>
            </x-ts-tab.items>
        </x-ts-tab>
    </div>
</x-app-layout>
