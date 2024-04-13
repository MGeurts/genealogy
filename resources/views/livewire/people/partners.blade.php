<div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                {{ __('person.partners') }} <x-ts-badge color="emerald" text="{{ count($person->couples) }}" />
            </div>

            @if (auth()->user()->hasPermission('couple:create'))
                <div class="flex-grow min-w-max max-w-min flex-1 text-end">
                    <x-ts-dropdown icon="bars-4" position="bottom-end">
                        <a href="/people/{{ $person->id }}/add-partner">
                            <x-ts-dropdown.items>
                                <x-icon.tabler icon="user-plus" class="mr-2 size-6" />
                                {{ __('person.add_relationship') }}
                            </x-ts-dropdown.items>
                        </a>

                        @if (auth()->user()->hasPermission('couple:update'))
                            <hr />

                            @foreach ($person->couples->sortBy('date_start') as $couple)
                                <a href="/people/{{ $couple->id }}/{{ $person->id }}/edit-partner">
                                    <x-ts-dropdown.items title="{{ __('person.edit_relationship') }}">
                                        <x-icon.tabler icon="user-edit" class="mr-2 size-6" />
                                        <div>
                                            {{ $couple->person2_id === $person->id ? $couple->person_1->name : $couple->person_2->name }}<br />
                                            {{ $couple->date_start ? $couple->date_start->isoFormat('LL') : '??' }}
                                        </div>
                                    </x-ts-dropdown.items>
                                </a>
                            @endforeach
                        @endif

                        @if (auth()->user()->hasPermission('couple:delete'))
                            <hr />

                            @foreach ($person->couples->sortBy('date_start') as $couple)
                                <x-ts-dropdown.items class="!text-danger-500" wire:click="confirmDeletion({{ $couple->id }} , '{{ $couple->name }}')"
                                    title="{{ __('person.delete_relationship') }}">
                                    <x-icon.tabler icon="user-off" class="mr-2 size-6" />
                                    <div>
                                        {{ $couple->person2_id === $person->id ? $couple->person_1->name : $couple->person_2->name }}<br />
                                        {{ $couple->date_start ? $couple->date_start->isoFormat('LL') : '??' }}
                                    </div>
                                </x-ts-dropdown.items>
                            @endforeach
                        @endif
                    </x-ts-dropdown>
                </div>
            @endif
        </div>
    </div>

    @if (count($person->couples) > 0)
        @foreach ($person->couples->sortBy('date_start') as $couple)
            <div class="p-2 flex flex-wrap gap-2 justify-center items-start @if (!$loop->last) border-b @endif">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    @if ($couple->person2_id === $person->id)
                        <x-link href="/people/{{ $couple->person_1->id }}" class="{{ $couple->person_1->isDeceased() ? 'text-danger-600 dark:!text-danger-400' : '' }}">
                            {{ $couple->person_1->name }}
                        </x-link>

                        <x-icon.tabler icon="{{ $couple->person_1->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @else
                        <x-link href="/people/{{ $couple->person_2->id }}" class="{{ $couple->person_2->isDeceased() ? 'text-danger-600 dark:!text-danger-400' : '' }}">
                            {{ $couple->person_2->name }}
                        </x-link>

                        <x-icon.tabler icon="{{ $couple->person_2->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @endif

                    @if ($couple->is_married)
                        <x-icon.tabler icon="circles-relation" class="text-yellow-500" />
                    @endif
                    <br />

                    <p>
                        <x-icon.tabler icon="hearts" class="text-emerald-600" />
                        {{ $couple->date_start ? $couple->date_start->isoFormat('LL') : '??' }}

                        @if ($couple->date_end or $couple->has_ended)
                            <br />
                            <x-icon.tabler icon="hearts-off" class="text-danger-500" />
                            {{ $couple->date_end ? $couple->date_end->isoFormat('LL') : '??' }}
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    @else
        <p class="p-2">{{ __('app.nothing_recorded') }}</p>
    @endif

    @if (count($person->couples) > 0)
        {{-- delete modal --}}
        <x-confirmation-modal wire:model.live="deleteConfirmed">
            <x-slot name="title">
                {{ __('app.delete') }}
            </x-slot>

            <x-slot name="content">
                <h1>{{ __('app.delete_question', ['model' => __('app.delete_relationship')]) }}</h1>
                <br />
                <h3 class="text-lg font-medium text-gray-900">{{ $couple_to_delete_name }}</h3>
            </x-slot>

            <x-slot name="footer">
                <x-ts-button color="secondary" wire:click="$toggle('deleteConfirmed')" wire:loading.attr="disabled">
                    {{ __('app.abort_no') }}
                </x-ts-button>

                <x-ts-button color="danger" class="ml-3" wire:click="deleteCouple()" wire:loading.attr="disabled">
                    {{ __('app.delete_yes') }}
                </x-ts-button>
            </x-slot>
        </x-confirmation-modal>
    @endif
</div>
