<div class="p-3 pb-0 flex flex-col justify-end rounded dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
    <div class="flex flex-wrap">
        <div class="flex-grow max-w-full flex-1 text-lg font-medium">
            {{ $person->name }}
        </div>

        <div class="flex-grow max-w-full flex-1 text-end">
            <a wire:navigate href="/people/{{ $person->id }}" title="{{ __('app.show_profile') }}">
                <x-button.primary class="mb-3 mr-3 {{ request()->routeIs('people.show') ? 'bg-warning hover:bg-warning-700' : '' }}">
                    <x-icon.tabler icon="id" class="mr-1" />
                    {{ __('person.profile') }}
                </x-button.primary>
            </a>

            <a wire:navigate href="/people/{{ $person->id }}/ancestors">
                <x-button.secondary class="mb-3 mr-3 {{ request()->routeIs('people.ancestors') ? 'bg-warning hover:bg-warning-700' : '' }}" title="{{ __('person.ancestors') }}">
                    <x-icon.tabler icon="binary-tree" class="mr-1 rotate-180" />
                    {{ __('person.ancestors') }}
                </x-button.secondary>
            </a>

            <a wire:navigate href="/people/{{ $person->id }}/descendants">
                <x-button.secondary class="mb-3 mr-3 {{ request()->routeIs('people.descendants') ? 'bg-warning hover:bg-warning-700' : '' }}" title="{{ __('person.descendants') }}">
                    <x-icon.tabler icon="binary-tree" class="mr-1" />
                    {{ __('person.descendants') }}
                </x-button.secondary>
            </a>

            <a wire:navigate href="/people/{{ $person->id }}/chart">
                <x-button.secondary class="mb-3 mr-3 {{ request()->routeIs('people.chart') ? 'bg-warning hover:bg-warning-700' : '' }}" title="{{ __('app.show_family_chart') }}">
                    <x-icon.tabler icon="social" class="mr-1" />
                    {{ __('app.family_chart') }}
                </x-button.secondary>
            </a>
        </div>
    </div>
</div>
