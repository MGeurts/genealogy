<div>
    <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] md:w-3xl dark:bg-neutral-700 dark:text-neutral-50">
        <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="max-w-full min-w-max flex-1 grow">{{ __('personevents.events') }}</div>

                <x-ts-button wire:click="$set('showModal', true)" color="emerald" class="text-sm">
                    <x-ts-icon icon="tabler.timeline-event-plus" class="inline-block size-5" />
                    {{ __('personevents.add_event') }}
                </x-ts-button>

                <div class="max-w-full min-w-max flex-1 grow text-end">
                    <x-ts-icon icon="tabler.timeline-event" class="inline-block size-5" />
                </div>
            </div>
        </div>

        <div class="bg-neutral-200 p-2">
            <!-- Events List -->
            @if ($this->events->isEmpty())
                <div class="rounded-lg border-2 border-dashed border-gray-300 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-800">
                    <x-ts-icon icon="tabler.timeline-event" class="inline-block size-10" />

                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ __('personevents.no_events') }}
                    </h3>
                    <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('personevents.add_events') }}</p>
                </div>
            @else
                <div class="overflow-hidden bg-white shadow sm:rounded-md dark:bg-gray-800">
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($this->events as $event)
                            <li>
                                <div class="px-4 py-4 hover:bg-gray-50 sm:px-6 dark:hover:bg-gray-700/50">
                                    <div class="flex items-center justify-between">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center space-x-3">
                                                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-400 ring-8 ring-white dark:ring-gray-900">
                                                    <x-ts-icon icon="tabler.calendar" class="inline-block size-5" />
                                                </span>

                                                <div class="min-w-0 flex-1">
                                                    <p class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $event->type_label }}
                                                    </p>

                                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $event->date_formatted }}
                                                    </p>

                                                    @if ($event->address)
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $event->address }}
                                                        </p>
                                                    @elseif ($event->place)
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $event->place }}
                                                        </p>
                                                    @endif

                                                    @if ($event->description)
                                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                                            {{ Str::limit($event->description, 100) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ml-4 flex flex-shrink-0 space-x-2">
                                            <x-ts-button
                                                wire:click="openModal({{ $event->id }})"
                                                color="gray"
                                                title="{{ __('personevents.edit_event') }}"
                                            >
                                                <x-ts-icon icon="tabler.edit" class="inline-block size-5" />
                                            </x-ts-button>

                                            <x-ts-button
                                                wire:click="confirm('{{ $event->id }}')"
                                                color="red"
                                                title="{{ __('personevents.delete_event') }}"
                                            >
                                                <x-ts-icon icon="tabler.trash" class="inline-block size-5" />
                                            </x-ts-button>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- TallStackUI Modal -->
    <x-ts-modal wire="showModal" size="2xl">
        <x-slot:title>
            {{ $editingEventId ? __('personevents.edit_event') : __('personevents.add_event') }}
        </x-slot:title>

        <form wire:submit.prevent="save" class="space-y-6">
            <x-ts-errors class="mb-2" close />

            <!-- Event Type -->
            <div>
                <x-ts-select.styled
                    wire:model="type"
                    id="type"
                    label="{{ __('personevents.event') }} :"
                    :options="$this->eventTypes()"
                    select="label:name|value:id"
                    placeholder="{{ __('app.select') }} ..."
                    required
                />
            </div>

            <!-- Date and Year -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <x-ts-date
                        wire:model="date"
                        id="date"
                        label="{{ __('app.date') }} :"
                        format="YYYY-MM-DD"
                        :max-date="now()"
                        placeholder="{{ __('app.select') }} ..."
                    />
                </div>

                <div>
                    <x-ts-input
                        type="number"
                        wire:model="year"
                        id="year"
                        :max="date('Y')"
                        label="{{ __('app.year') }} {{ __('personevents.date_unknown') }} :"
                    />
                </div>
            </div>

            <!-- Place -->
            <div>
                <x-ts-input
                    wire:model="place"
                    id="place"
                    :placeholder="__('personevents.place_example')"
                    label="{{ __('app.place') }} :"
                />
            </div>

            <!-- Description -->
            <div>
                <x-ts-textarea
                    wire:model="description"
                    id="description"
                    rows="3"
                    :placeholder="__('personevents.additional_details')"
                    label="{{ __('team.description') }} :"
                />
            </div>

            <!-- Detailed Address (Collapsible) -->
            <div x-data="{ showAddress: false }" class="border-t border-gray-200 pt-4 dark:border-gray-700">
                <button
                    type="button"
                    @click="showAddress = ! showAddress"
                    class="flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                >
                    <svg
                        x-show="! showAddress"
                        class="mr-1 h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>

                    <svg
                        x-show="showAddress"
                        class="mr-1 h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        style="display: none"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                    </svg>
                    {{ __('personevents.add_detailed_address') }}
                </button>

                <div x-show="showAddress" x-collapse class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="sm:col-span-2">
                            <x-ts-input wire:model="street" id="street" label="{{ __('person.street') }} :" />
                        </div>

                        <div>
                            <x-ts-input wire:model="number" id="number" label="{{ __('person.number') }} :" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-ts-input
                                wire:model="postal_code"
                                id="postal_code"
                                label="{{ __('person.postal_code') }} :"
                            />
                        </div>

                        <div>
                            <x-ts-input wire:model="city" id="city" label="{{ __('person.city') }} :" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <x-ts-input wire:model="province" id="province" label="{{ __('person.province') }} :" />
                        </div>

                        <div>
                            <x-ts-input wire:model="state" id="state" label="{{ __('person.state') }} :" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1">
                        <x-ts-select.styled
                            wire:model="country"
                            id="country"
                            label="{{ __('person.country') }} :"
                            :options="$this->countries()"
                            select="label:name|value:id"
                            placeholder="{{ __('app.select') }} ..."
                            searchable
                        />
                    </div>
                </div>
            </div>
        </form>

        <x-slot:footer>
            <x-ts-button wire:click="closeModal" color="secondary"> {{ __('app.cancel') }} </x-ts-button>

            <x-ts-button wire:click="save" color="emerald"> {{ __('app.save') }} </x-ts-button>
        </x-slot:footer>
    </x-ts-modal>
</div>
