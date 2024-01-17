<div class="max-w-md flex flex-col justify-end rounded dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
    <!-- image -->
    <div class="p-2">
        <x-image.photo :person="$person" />
    </div>

    <!-- lifetime -->
    <div class="px-2 text-center">
        {!! $person->lifetime ? $person->lifetime : '&nbsp' !!}
    </div>

    <!-- age -->
    <div class="px-2 text-center">
        {!! isset($person->age) ? $person->age . ' ' . trans_choice('person.years', $person->age) : '&nbsp' !!}
    </div>

    <!-- data -->
    <div class="p-2 pb-0">
        <p>
            <x-link wire:navigate href="/people/{{ $person->id }}" class="{{ $person->isDeceased() ? '!text-danger' : '' }}">
                <b>{{ $person->name }}</b>
            </x-link>
            <x-icon.tabler icon="{{ $person->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
        </p>
        <p>{{ __('person.birthname') }} : {{ $person->birthname ? $person->birthname : '' }}</p>
        <p>{{ __('person.nickname') }} : {{ $person->nickname ? $person->nickname : '' }}</p>
        <x-hr.narrow />
        <p class="py-1">{{ __('person.father') }} :
            @if ($person->father)
                <x-link wire:navigate href="/people/{{ $person->father->id }}" class="{{ $person->father->isDeceased() ? '!text-danger' : '' }}">
                    <b>{{ $person->father->name }}</b>
                </x-link>
                <x-icon.tabler icon="{{ $person->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
            @endif
        </p>
        <p class="py-1">{{ __('person.mother') }} :
            @if ($person->mother)
                <x-link wire:navigate href="/people/{{ $person->mother->id }}" class="{{ $person->mother->isDeceased() ? '!text-danger' : '' }}">
                    <b>{{ $person->mother->name }}</b>
                </x-link>
                <x-icon.tabler icon="{{ $person->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
            @endif
        </p>
        <x-hr.narrow />
    </div>

    <!-- buttons -->
    <div class="p-2 flex flex-shrink-0 flex-wrap">
        <div class="flex-grow min-w-max max-w-full flex-1">
            <a href="/people/{{ $person->id }}" title="{{ __('app.show_profile') }}">
                <x-button.primary class="mr-3">
                    <x-icon.tabler icon="id" class="mr-1" />
                    {{ __('person.profile') }}
                </x-button.primary>
            </a>
        </div>

        <div class="flex-grow min-w-max max-w-full flex-1 text-end">
            <a wire:navigate href="/people/{{ $person->id }}/chart">
                <x-button.secondary title="{{ __('app.show_family_chart') }}">
                    <x-icon.tabler icon="social" class="mr-1" />
                    {{ __('app.family_chart') }}
                </x-button.secondary>
            </a>
        </div>
    </div>
</div>
