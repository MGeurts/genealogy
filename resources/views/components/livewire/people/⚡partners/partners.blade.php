<div class="flex min-w-xs flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50">
    <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="max-w-full min-w-max flex-1 grow">
                {{ __('person.partners') }}
                @if (count($person->couples) > 0)
                    <x-ts-badge color="emerald" sm text="{{ count($person->couples) }}" />
                @endif
            </div>

            @if (auth()->user()->hasPermission('couple:create'))
                <div class="max-w-min min-w-max flex-1 grow text-end">
                    <x-ts-dropdown icon="tabler.menu-2" position="bottom-end">
                        <a href="/people/{{ $person->id }}/add-partner">
                            <x-ts-dropdown.items>
                                <x-ts-icon icon="tabler.user-plus" class="mr-2 inline-block size-5" />
                                {{ __('person.add_relationship') }}
                            </x-ts-dropdown.items>
                        </a>

                        @if (auth()->user()->hasPermission('couple:update') and $person->couples->count() > 0)
                            <hr />

                            @foreach ($person->couples->sortBy('date_start') as $couple)
                                <a href="/people/{{ $person->id }}/{{ $couple->id }}/edit-partner">
                                    <x-ts-dropdown.items title="{{ __('person.edit_relationship') }}">
                                        <x-ts-icon icon="tabler.user-edit" class="mr-2 inline-block size-5" />
                                        <div>
                                            {{ $couple->person2_id === $person->id ? $couple->person1->name : $couple->person2->name }}<br />
                                            {{ $couple->date_start ? $couple->date_start->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') : '??' }}
                                        </div>
                                    </x-ts-dropdown.items>
                                </a>
                            @endforeach
                        @endif

                        @if (auth()->user()->hasPermission('couple:delete') and $person->couples->count() > 0)
                            <hr />

                            @foreach ($person->couples->sortBy('date_start') as $couple)
                                <x-ts-dropdown.items
                                    class="text-red-600! dark:text-red-400!"
                                    wire:click="confirm({{ $couple->id }} , '{{ $couple->name }}')"
                                    title="{{ __('person.delete_relationship') }}"
                                >
                                    <x-ts-icon icon="tabler.trash" class="mr-2 inline-block size-5" />
                                    <div>
                                        {{ $couple->person2_id === $person->id ? $couple->person1->name : $couple->person2->name }}<br />
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
            <div class="p-2 flex flex-wrap gap-2 justify-center items-start @if (! $loop->last) border-b @endif">
                <div class="max-w-full min-w-max flex-1 grow">
                    @if ($couple->person2_id === $person->id)
                        <x-link
                            href="/people/{{ $couple->person1->id }}"
                            @class(['text-red-600 dark:text-red-400' => $couple->person1->isDeceased()])
                        >
                            {{ $couple->person1->name }}
                        </x-link>

                        <x-ts-icon
                            icon="tabler.{{ $couple->person1->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                            class="inline-block size-5"
                        />
                    @else
                        <x-link
                            href="/people/{{ $couple->person2->id }}"
                            @class(['text-red-600 dark:text-red-400' => $couple->person2->isDeceased()])
                        >
                            {{ $couple->person2->name }}
                        </x-link>

                        <x-ts-icon
                            icon="tabler.{{ $couple->person2->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                            class="inline-block size-5"
                        />
                    @endif

                    @if ($couple->is_married)
                        <x-ts-icon icon="tabler.circles-relation" class="inline-block size-5 text-yellow-500" />
                    @endif
                    <br />

                    <p>
                        <x-ts-icon icon="tabler.hearts" class="inline-block size-5 text-emerald-600" />
                        {{ $couple->date_start ? $couple->date_start->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') : '??' }}

                        @if ($couple->date_end or $couple->has_ended)
                            <br />
                            <x-ts-icon
                                icon="tabler.hearts-off"
                                class="inline-block size-5 text-red-600 dark:text-red-400"
                            />
                            {{ $couple->date_end ? $couple->date_end->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') : '??' }}
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    @else
        <p class="p-2">{{ __('app.nothing_recorded') }}</p>
    @endif
</div>
