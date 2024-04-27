<div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1 align-middle justify-center items-center">
                {{ __('person.family') }}
            </div>

            @if (auth()->user()->hasPermission('person:update'))
                <div class="flex-grow min-w-max max-w-min flex-1 text-end">
                    <x-ts-dropdown icon="menu-2" position="bottom-end">
                        <a href="/people/{{ $person->id }}/edit-family#form">
                            <x-ts-dropdown.items>
                                <x-ts-icon icon="edit" class="mr-2" />
                                {{ __('person.edit_family') }}
                            </x-ts-dropdown.items>
                        </a>
                    </x-ts-dropdown>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-6">
        <div class="col-span-2 pl-2 py-2 border-b">{{ __('person.father') }}</div>
        <div class="col-span-4 pr-2 py-2 border-b">
            @if ($person->father)
                <x-link href="/people/{{ $person->father->id }}" class="{{ $person->father->isDeceased() ? 'text-danger-600 dark:!text-danger-400' : '' }}">
                    {{ $person->father->name }}
                </x-link>
                <x-ts-icon icon="{{ $person->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="size-5 inline-block" />
            @endif
        </div>

        <div class="col-span-2 pl-2 py-2 border-b">{{ __('person.mother') }}</div>
        <div class="col-span-4 pr-2 py-2 border-b">
            @if ($person->mother)
                <x-link href="/people/{{ $person->mother->id }}" class="{{ $person->mother->isDeceased() ? 'text-danger-600 dark:!text-danger-400' : '' }}">
                    {{ $person->mother->name }}
                </x-link>
                <x-ts-icon icon="{{ $person->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="size-5 inline-block" />
            @endif
        </div>

        <div class="col-span-2 pl-2 py-2 border-b">{{ __('person.parents') }}</div>
        <div class="col-span-4 pr-2 py-2 border-b">
            @if ($person->parents)
                <x-link href="/people/{{ $person->parents->person_1->id }}" class="{{ $person->parents->person_1->isDeceased() ? 'text-danger-600 dark:!text-danger-400' : '' }}">
                    {{ $person->parents->person_1->name }}
                </x-link>
                <x-ts-icon icon="{{ $person->parents->person_1->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="size-5 inline-block" />
                <br />
                <x-link href="/people/{{ $person->parents->person_2->id }}" class="{{ $person->parents->person_2->isDeceased() ? 'text-danger-600 dark:!text-danger-400' : '' }}">
                    {{ $person->parents->person_2->name }}
                </x-link>
                <x-ts-icon icon="{{ $person->parents->person_2->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="size-5 inline-block" />
            @endif
        </div>

        <div class="col-span-2 pl-2 py-2">{{ __('person.partner') }}</div>
        <div class="col-span-4 pr-2 py-2">
            @if ($person->currentPartner())
                <x-link href="/people/{{ $person->currentPartner()->id }}" class="{{ $person->currentPartner()->isDeceased() ? 'text-danger-600 dark:!text-danger-400' : '' }}">
                    {{ $person->currentPartner()->name }}
                </x-link>
                <x-ts-icon icon="{{ $person->currentPartner()->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="size-5 inline-block" />
            @endif
        </div>
    </div>
</div>
