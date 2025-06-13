<div class="p-2 pb-0 flex flex-col justify-end rounded-sm dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
    <div class="flex flex-wrap">
        <div class="flex-1 grow max-w-full text-lg font-medium">
            <div>{{ $person->name }}</div>

            @if (auth()->user()->is_developer)
                <div class="text-cyan-500">{{ $person->team->name }}</div>
            @endif
        </div>

        <div class="flex-1 grow max-w-full print:hidden text-end">
            <x-ts-button href="/people/{{ $person->id }}" color="{{ request()->routeIs('people.show') ? 'yellow' : 'primary' }}" class="mb-3 mr-2 text-sm">
                <x-ts-icon icon="tabler.id" class="size-5" />
                {{ __('person.profile') }}
            </x-ts-button>

            <x-ts-button href="/people/{{ $person->id }}/ancestors" color="{{ request()->routeIs('people.ancestors') ? 'yellow' : 'secondary' }}" class="mb-3 mr-2 text-sm">
                <x-ts-icon icon="tabler.binary-tree" class="rotate-180 size-5" />
                {{ __('person.ancestors') }}
            </x-ts-button>

            <x-ts-button href="/people/{{ $person->id }}/descendants" color="{{ request()->routeIs('people.descendants') ? 'yellow' : 'secondary' }}" class="mb-3 mr-2 text-sm">
                <x-ts-icon icon="tabler.binary-tree" class="size-5" />
                {{ __('person.descendants') }}
            </x-ts-button>

            <x-ts-button href="/people/{{ $person->id }}/chart" color="{{ request()->routeIs('people.chart') ? 'yellow' : 'secondary' }}" class="mb-3 mr-2 text-sm">
                <x-ts-icon icon="tabler.social" class="size-5" />
                {{ __('app.family_chart') }}
            </x-ts-button>

            <x-ts-button href="/people/{{ $person->id }}/history" color="{{ request()->routeIs('people.history') ? 'yellow' : 'cyan' }}" class="mb-3 mr-2 text-sm">
                <x-ts-icon icon="tabler.history" class="size-5" />
                {{ __('app.history') }}
            </x-ts-button>

            <x-ts-button href="/people/{{ $person->id }}/datasheet" color="{{ request()->routeIs('people.datasheet') ? 'yellow' : 'secondary' }}" class="mb-3 mr-2 text-sm">
                <x-ts-icon icon="tabler.pdf" class="size-5" />
                {{ __('app.datasheet') }}
            </x-ts-button>
        </div>
    </div>
</div>
