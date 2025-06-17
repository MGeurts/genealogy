<div class="min-w-80 flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="items-center justify-center flex-1 grow max-w-full align-middle min-w-max">
                {{ __('person.family') }}
            </div>

            @if (auth()->user()->hasPermission('person:update'))
                <div class="flex-1 grow min-w-max max-w-min text-end">
                    <x-ts-dropdown icon="tabler.menu-2" position="bottom-end">
                        @if ((!isset($person->father_id) or !isset($person->mother_id)) and !isset($person->parents_id))
                            @if (!isset($person->father_id))
                                <a href="/people/{{ $person->id }}/add-father">
                                    <x-ts-dropdown.items>
                                        <x-ts-icon icon="tabler.user-plus" class="inline-block size-5 mr-2" />
                                        {{ __('person.add_father') }}
                                    </x-ts-dropdown.items>
                                </a>
                            @endif

                            @if (!isset($person->mother_id))
                                <a href="/people/{{ $person->id }}/add-mother">
                                    <x-ts-dropdown.items>
                                        <x-ts-icon icon="tabler.user-plus" class="inline-block size-5 mr-2" />
                                        {{ __('person.add_mother') }}
                                    </x-ts-dropdown.items>
                                </a>
                            @endif

                            <hr />
                        @endif

                        <a href="/people/{{ $person->id }}/edit-family">
                            <x-ts-dropdown.items>
                                <x-ts-icon icon="tabler.edit" class="inline-block size-5 mr-2" />
                                {{ __('person.edit_family') }}
                            </x-ts-dropdown.items>
                        </a>
                    </x-ts-dropdown>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-6">
        <div class="col-span-2 py-2 pl-2 border-b">{{ __('person.father') }}</div>
        <div class="col-span-4 p-2 border-b">
            @if ($person->father)
                <x-link href="/people/{{ $person->father->id }}" @class(['text-red-600 dark:text-red-400' => $person->father->isDeceased()])>
                    {{ $person->father->name }}
                </x-link>
                <x-ts-icon icon="tabler.{{ $person->father->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
            @endif
        </div>

        <div class="col-span-2 py-2 pl-2 border-b">{{ __('person.mother') }}</div>
        <div class="col-span-4 p-2 border-b">
            @if ($person->mother)
                <x-link href="/people/{{ $person->mother->id }}" @class(['text-red-600 dark:text-red-400' => $person->mother->isDeceased()])>
                    {{ $person->mother->name }}
                </x-link>
                <x-ts-icon icon="tabler.{{ $person->mother->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
            @endif
        </div>

        <div class="col-span-2 py-2 pl-2 border-b">{{ __('person.parents') }}</div>
        <div class="col-span-4 p-2 border-b">
            @if ($person->parents)
                <x-link href="/people/{{ $person->parents->person_1->id }}" @class(['text-red-600 dark:text-red-400' => $person->parents->person_1->isDeceased()])>
                    {{ $person->parents->person_1->name }}
                </x-link>
                <x-ts-icon icon="tabler.{{ $person->parents->person_1->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                <br />
                <x-link href="/people/{{ $person->parents->person_2->id }}" @class(['text-red-600 dark:text-red-400' => $person->parents->person_2->isDeceased()])>
                    {{ $person->parents->person_2->name }}
                </x-link>
                <x-ts-icon icon="tabler.{{ $person->parents->person_2->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
            @endif
        </div>

        <div class="col-span-2 py-2 pl-2">{{ __('person.partner') }}</div>
        <div class="col-span-4 p-2">
            @if ($person->currentPartner())
                <x-link href="/people/{{ $person->currentPartner()->id }}" @class(['text-red-600 dark:text-red-400' => $person->currentPartner()->isDeceased()])>
                    {{ $person->currentPartner()->name }}
                </x-link>
                <x-ts-icon icon="tabler.{{ $person->currentPartner()->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
            @endif
        </div>
    </div>
</div>
