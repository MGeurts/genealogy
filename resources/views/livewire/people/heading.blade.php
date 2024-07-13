<div class="p-2 pb-0 flex flex-col justify-end rounded dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
    <div class="flex flex-wrap">
        <div class="flex-1 flex-grow max-w-full text-lg font-medium">
            <div>{{ $person->name }}</div>

            @if (auth()->user()->is_developer)
                <div class="text-info-500">{{ $person->team->name }}</div>
            @endif
        </div>

        <div class="flex-1 flex-grow max-w-full print:hidden text-end">
            <x-ts-button href="/people/{{ $person->id }}" color="{{ request()->routeIs('people.show') ? 'warning' : 'primary' }}" class="mb-3 mr-2 text-sm text-white">
                <x-ts-icon icon="id" class="size-5" />
                {{ __('person.profile') }}
            </x-ts-button>

            <x-ts-button href="/people/{{ $person->id }}/ancestors" color="{{ request()->routeIs('people.ancestors') ? 'warning' : 'secondary' }}" class="mb-3 mr-2 text-sm text-white">
                <x-ts-icon icon="binary-tree" class="rotate-180 size-5" />
                {{ __('person.ancestors') }}
            </x-ts-button>

            <x-ts-button href="/people/{{ $person->id }}/descendants" color="{{ request()->routeIs('people.descendants') ? 'warning' : 'secondary' }}" class="mb-3 mr-2 text-sm text-white">
                <x-ts-icon icon="binary-tree" class="size-5" />
                {{ __('person.descendants') }}
            </x-ts-button>

            <x-ts-button href="/people/{{ $person->id }}/chart" color="{{ request()->routeIs('people.chart') ? 'warning' : 'secondary' }}" class="mb-3 mr-2 text-sm text-white">
                <x-ts-icon icon="social" class="size-5" />
                {{ __('app.family_chart') }}
            </x-ts-button>

            <x-ts-button href="/people/{{ $person->id }}/files" color="{{ request()->routeIs('people.files') ? 'warning' : 'secondary' }}" class="mb-3 mr-2 text-sm text-white">
                <x-ts-icon icon="files" class="size-5" />
                {{ __('person.files') }}
                @if (count($person->getMedia('files')) > 0)
                    <x-ts-badge color="emerald" text="{{ count($person->getMedia('files')) }}" />
                @endif
            </x-ts-button>

            <x-ts-button href="/people/{{ $person->id }}/history" color="{{ request()->routeIs('people.history') ? 'warning' : 'info' }}" class="mb-3 mr-2 text-sm text-white">
                <x-ts-icon icon="history" class="size-5" />
                {{ __('app.history') }}
            </x-ts-button>
        </div>
    </div>
</div>
