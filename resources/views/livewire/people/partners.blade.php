<div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                {{ __('person.partners') }} ({{ $person->couples->count() }})
            </div>

            <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                @if (auth()->user()->hasPermission('couple:create'))
                    <a wire:navigate href="/people/{{ $person->id }}/add-partner">
                        <x-button.success class="!p-2" title="{{ __('person.add_relationship') }}">
                            <x-icon.tabler icon="user-plus" class="!size-4" />
                        </x-button.success>
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if ($person->couples->count() > 0)
        @foreach ($person->couples->sortBy('date_start') as $couple)
            <div class="p-2 flex flex-wrap gap-2 justify-center items-start @if (!$loop->last) border-b @endif">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    @if ($couple->person2_id === $person->id)
                        <x-link wire:navigate href="/people/{{ $couple->person_1->id }}" class="{{ $couple->person_1->isDeceased() ? '!text-danger' : '' }}">
                            <b>{{ $couple->person_1->name }}</b>
                        </x-link>

                        <x-icon.tabler icon="{{ $couple->person_1->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @else
                        <x-link wire:navigate href="/people/{{ $couple->person_2->id }}" class="{{ $couple->person_2->isDeceased() ? '!text-danger' : '' }}">
                            <b>{{ $couple->person_2->name }}</b>
                        </x-link>

                        <x-icon.tabler icon="{{ $couple->person_2->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @endif

                    @if ($couple->is_married)
                        <x-icon.tabler icon="circles-relation" class="text-yellow-500" />
                    @endif
                    <br />

                    <p>
                        <x-icon.tabler icon="hearts" class="text-success" />
                        {{ $couple->date_start ? $couple->date_start->isoFormat('LL') : '??' }}

                        @if ($couple->date_end or $couple->has_ended)
                            <br />
                            <x-icon.tabler icon="hearts-off" class="text-danger" />
                            {{ $couple->date_end ? $couple->date_end->isoFormat('LL') : '??' }}
                        @endif
                    </p>
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                    @if (auth()->user()->hasPermission('couple:update'))
                        <a wire:navigate href="/people/{{ $couple->id }}/{{ $person->id }}/edit-partner">
                            <x-button.primary class="!p-2" title="{{ __('person.edit_relationship') }}">
                                <x-icon.tabler icon="edit" class="!size-4" />
                            </x-button.primary>
                        </a>
                    @endif

                    @if (auth()->user()->hasPermission('couple:delete'))
                        <x-button.danger class="!p-2" title="{{ __('app.delete') }} {{ __('app.delete_relationship') }}"
                            wire:click="confirmDeletion({{ $couple->id }} , '{{ $couple->name }}')">
                            <x-icon.tabler icon="trash-filled" class="!size-4" />
                        </x-button.danger>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <p class="p-2">{{ __('app.nothing_recorded') }}</p>
    @endif

    @if ($person->couples->count() > 0)
        <!-- Delete modal -->
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
                <x-button.secondary wire:click="$toggle('deleteConfirmed')" wire:loading.attr="disabled">
                    {{ __('app.abort_no') }}
                </x-button.secondary>

                <x-button.danger class="ml-3" wire:click="deleteCouple()" wire:loading.attr="disabled">
                    {{ __('app.delete_yes') }}
                </x-button.danger>
            </x-slot>
        </x-confirmation-modal>
    @endif
</div>
