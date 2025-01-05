<div class="flex flex-col justify-end rounded dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
    {{-- image --}}
    <div class="p-2">
        <x-image.photo :person="$person" />
    </div>

    {{-- lifetime & age --}}
    <div class="flex px-2">
        <div class="flex-grow">
            {!! isset($person->lifetime) ? $person->lifetime : '&nbsp;' !!}
        </div>

        <div class="flex-grow text-end">
            {!! isset($person->age) ? $person->age . ' ' . trans_choice('person.years', $person->age) : '&nbsp;' !!}
        </div>
    </div>

    {{-- data --}}
    <div class="p-2 pb-0">
        <x-hr.narrow />

        <p>
            <x-link href="/people/{{ $person->id }}" @class([
                'text-danger-600 dark:text-danger-400' => $person->isDeceased(),
            ])>
                {{ $person->name }}
            </x-link>
            <x-ts-icon icon="{{ $person->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
        </p>
        <p>{{ __('person.birthname') }} : {{ $person->birthname ? $person->birthname : '' }}</p>
        <p>{{ __('person.nickname') }} : {{ $person->nickname ? $person->nickname : '' }}</p>

        <x-hr.narrow />

        <p>{{ __('person.father') }} :
            @if ($person->father)
                <x-link href="/people/{{ $person->father->id }}" @class([
                    'text-danger-600 dark:text-danger-400' => $person->father->isDeceased(),
                ])>
                    {{ $person->father->name }}
                </x-link>
                <x-ts-icon icon="{{ $person->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
            @endif
        </p>

        <p>{{ __('person.mother') }} :
            @if ($person->mother)
                <x-link href="/people/{{ $person->mother->id }}" @class([
                    'text-danger-600 dark:text-danger-400' => $person->mother->isDeceased(),
                ])>
                    {{ $person->mother->name }}
                </x-link>
                <x-ts-icon icon="{{ $person->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
            @endif
        </p>

        <x-hr.narrow />
    </div>

    {{-- buttons --}}
    <div class="flex flex-wrap flex-shrink-0 p-2 print:hidden">
        <div class="flex-1 flex-grow max-w-full min-w-max">
            <a href="/people/{{ $person->id }}" title="{{ __('app.show_profile') }}">
                <x-ts-button color="primary" class="text-sm">
                    <x-ts-icon icon="id" class="size-5" />
                    {{ __('person.profile') }}
                </x-ts-button>
            </a>
        </div>

        <div class="flex-1 flex-grow max-w-full min-w-max text-end">
            <a href="/people/{{ $person->id }}/chart">
                <x-ts-button color="secondary" class="text-sm">
                    <x-ts-icon icon="social" class="size-5" />
                    {{ __('app.family_chart') }}
                </x-ts-button>
            </a>
        </div>
    </div>
</div>
