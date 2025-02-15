<div class="min-w-80 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="flex-1 flex-grow max-w-full min-w-max">
                {{ __('person.partners') }}
                @if (count($person->couples) > 0)
                    <x-ts-badge color="emerald" text="{{ count($person->couples) }}" />
                @endif
            </div>

            @if (auth()->user()->hasPermission('couple:create'))
                <div class="flex-1 flex-grow min-w-max max-w-min text-end">
                    <x-ts-dropdown icon="tabler.menu-2" position="bottom-end">
                        <a href="/people/{{ $person->id }}/add-partner">
                            <x-ts-dropdown.items>
                                <x-ts-icon icon="tabler.user-plus" class="mr-2" />
                                {{ __('person.add_relationship') }}
                            </x-ts-dropdown.items>
                        </a>

                        @if (auth()->user()->hasPermission('couple:update') and $person->couples->count() > 0)
                            <hr />

                            @foreach ($person->couples->sortBy('date_start') as $couple)
                                <a href="/people/{{ $person->id }}/{{ $couple->id }}/edit-partner">
                                    <x-ts-dropdown.items title="{{ __('person.edit_relationship') }}">
                                        <x-ts-icon icon="tabler.user-edit" class="mr-2" />
                                        <div>
                                            {{ $couple->person2_id === $person->id ? $couple->person_1->name : $couple->person_2->name }}<br />
                                            {{ $couple->date_start ? $couple->date_start->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') : '??' }}
                                        </div>
                                    </x-ts-dropdown.items>
                                </a>
                            @endforeach
                        @endif

                        @if (auth()->user()->hasPermission('couple:delete') and $person->couples->count() > 0)
                            <hr />

                            @foreach ($person->couples->sortBy('date_start') as $couple)
                                <x-ts-dropdown.items class="!text-danger-600 dark:!text-danger-400" wire:click="confirmDeletion({{ $couple->id }} , '{{ $couple->name }}')"
                                    title="{{ __('person.delete_relationship') }}">
                                    <x-ts-icon icon="tabler.trash" class="mr-2" />
                                    <div>
                                        {{ $couple->person2_id === $person->id ? $couple->person_1->name : $couple->person_2->name }}<br />
                                        {{ $couple->date_start ? $couple->date_start->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') : '??' }}
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
                <div class="flex-1 flex-grow max-w-full min-w-max">
                    @if ($couple->person2_id === $person->id)
                        <x-link href="/people/{{ $couple->person_1->id }}" @class(['text-danger-600 dark:text-danger-400' => $couple->person_1->isDeceased()])>
                            {{ $couple->person_1->name }}
                        </x-link>

                        <x-ts-icon icon="tabler.{{ $couple->person_1->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    @else
                        <x-link href="/people/{{ $couple->person_2->id }}" @class(['text-danger-600 dark:text-danger-400' => $couple->person_2->isDeceased()])>
                            {{ $couple->person_2->name }}
                        </x-link>

                        <x-ts-icon icon="tabler.{{ $couple->person_2->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    @endif

                    @if ($couple->is_married)
                        <x-ts-icon icon="tabler.circles-relation" class="inline-block text-yellow-500 size-5" />
                    @endif
                    <br />

                    <p>
                        <x-ts-icon icon="tabler.hearts" class="inline-block size-5 text-emerald-600" />
                        {{ $couple->date_start ? $couple->date_start->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') : '??' }}

                        @if ($couple->date_end or $couple->has_ended)
                            <br />
                            <x-ts-icon icon="tabler.hearts-off" class="inline-block size-5 text-danger-600 dark:text-danger-400" />
                            {{ $couple->date_end ? $couple->date_end->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') : '??' }}
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
                <p>{{ __('app.delete_question', ['model' => __('app.delete_relationship')]) }}</p>
                <p class="text-lg font-medium text-gray-900">{{ $couple_to_delete_name }}</p>
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
