<div class="min-w-80 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="flex-1 flex-grow max-w-full min-w-max">
                {{ __('person.children') }}
                @if (count($person->couples) > 0)
                    <x-ts-badge color="emerald" text="{{ count($children) }}" />
                @endif
            </div>

            @if (auth()->user()->hasPermission('person:create'))
                <div class="flex-1 flex-grow min-w-max max-w-min text-end">
                    <x-ts-dropdown icon="menu-2" position="bottom-end">
                        <a href="/people/{{ $person->id }}/add-child">
                            <x-ts-dropdown.items>
                                <x-ts-icon icon="user-plus" class="mr-2" />
                                {{ __('person.add_child') }}
                            </x-ts-dropdown.items>
                        </a>

                        @if (auth()->user()->hasPermission('person:update') and $person->children->count() > 0)
                            <hr />

                            @foreach ($children as $child)
                                @if (!isset($child->type))
                                    <x-ts-dropdown.items class="!text-danger-600 dark:!text-danger-400" wire:click="confirmDisconnect({{ $child->id }} , '{{ $child->name }}')"
                                        title="{{ __('person.delete_child') }}">
                                        <x-ts-icon icon="plug-connected-x" class="mr-2" /> {{ $child->name }}
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
                <div class="flex-1 flex-grow max-w-full min-w-max">
                    <x-link href="/people/{{ $child->id }}" @class(['text-danger-600 dark:text-danger-400' => $child->isDeceased()])>
                        {{ $child->name }}
                    </x-link>

                    <x-ts-icon icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    @if (isset($child->type))
                        <x-ts-icon icon="heart-plus" class="inline-block size-5 text-emerald-600" />
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
                <p>{{ __('app.disconnect_question', ['model' => __('app.disconnect_child')]) }}</p>
                <p class="text-lg font-medium text-gray-900">{{ $child_to_disconnect_name }}</p>
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
