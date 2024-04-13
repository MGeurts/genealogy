<div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                {{ __('person.children') }} <x-ts-badge color="emerald" text="{{ count($children) }}" />
            </div>

            @if (auth()->user()->hasPermission('person:create'))
                <div class="flex-grow min-w-max max-w-min flex-1 text-end">
                    <x-ts-dropdown icon="bars-4" position="bottom-end">
                        <a href="/people/{{ $person->id }}/add-child">
                            <x-ts-dropdown.items>
                                <x-icon.tabler icon="user-plus" class="mr-2 size-6" />
                                {{ __('person.add_child') }}
                            </x-ts-dropdown.items>
                        </a>

                        @if (auth()->user()->hasPermission('person:update'))
                            <hr />

                            @foreach ($children as $child)
                                @if (!$child->type)
                                    <x-ts-dropdown.items class="!text-danger-500" wire:click="confirmDisconnect({{ $child->id }} , '{{ $child->name }}')" title="{{ __('person.delete_child') }}">
                                        <x-icon.tabler icon="trash" class="mr-2 size-6" /> {{ $child->name }}
                                    </x-ts-dropdown.items>
                                @endif
                            @endforeach
                        @endif
                    </x-ts-dropdown>
                </div>
            @endif
        </div>
    </div>

    @if (count($children) > 0)
        @foreach ($children as $child)
            <div class="p-2 flex flex-wrap gap-2 justify-center items-start @if (!$loop->last) border-b @endif">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    <x-link href="/people/{{ $child->id }}" class="{{ $child->isDeceased() ? 'text-danger-600 dark:!text-danger-400' : '' }}">
                        {{ $child->name }}
                    </x-link>

                    <x-icon.tabler icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @if ($child->type)
                        <x-icon.tabler icon="heart-plus" class="text-emerald-600" />
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <p class="p-2">{{ __('app.nothing_recorded') }}</p>
    @endif

    @if (count($children) > 0)
        {{-- delete modal --}}
        <x-confirmation-modal wire:model.live="disconnectConfirmed">
            <x-slot name="title">
                {{ __('app.disconnect') }}
            </x-slot>

            <x-slot name="content">
                <h1>{{ __('app.disconnect_question', ['model' => __('app.disconnect_child')]) }}</h1>
                <br />
                <h3 class="text-lg font-medium text-gray-900">{{ $child_to_disconnect_name }}</h3>
            </x-slot>

            <x-slot name="footer">
                <x-ts-button color="secondary" wire:click="$toggle('disconnectConfirmed')" wire:loading.attr="disabled">
                    {{ __('app.abort_no') }}
                </x-ts-button>

                <x-ts-button color="danger" class="ml-3" wire:click="disconnectChild()" wire:loading.attr="disabled">
                    {{ __('app.disconnect_yes') }}
                </x-ts-button>
            </x-slot>
        </x-confirmation-modal>
    @endif
</div>
