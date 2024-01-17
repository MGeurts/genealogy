<div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                {{ __('person.children') }} ({{ $children->count() }})
            </div>

            <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                @if (auth()->user()->hasPermission('person:create'))
                    <a wire:navigate href="/people/{{ $person->id }}/add-child">
                        <x-button.success class="!p-2" title="{{ __('person.add_child') }}">
                            <x-icon.tabler icon="user-plus" class="!size-4" />
                        </x-button.success>
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if ($children->count() > 0)
        @foreach ($children as $child)
            <div class="p-2 flex flex-wrap gap-2 justify-center items-start @if (!$loop->last) border-b @endif">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    <x-link wire:navigate href="/people/{{ $child->id }}" class="{{ $child->isDeceased() ? '!text-danger' : '' }}">
                        <b>{{ $child->name }}</b>
                    </x-link>

                    <x-icon.tabler icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @if ($child->type)
                        <x-icon.tabler icon="heart-plus" class="text-success" />
                    @endif
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                    @if (!$child->type)
                        @if (auth()->user()->hasPermission('person:update'))
                            <x-button.danger class="!p-2" title="{{ __('app.disconnect') }} {{ __('app.disconnect_child') }}"
                                wire:click="confirmDisconnect({{ $child->id }} , '{{ $child->name }}')">
                                <x-icon.tabler icon="user-off" class="!size-4" />
                            </x-button.danger>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <p class="p-2">{{ __('app.nothing_recorded') }}</p>
    @endif

    @if ($children->count() > 0)
        <!-- Delete modal -->
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
                <x-button.secondary wire:click="$toggle('disconnectConfirmed')" wire:loading.attr="disabled">
                    {{ __('app.abort_no') }}
                </x-button.secondary>

                <x-button.danger class="ml-3" wire:click="disconnectChild()" wire:loading.attr="disabled">
                    {{ __('app.disconnect_yes') }}
                </x-button.danger>
            </x-slot>
        </x-confirmation-modal>
    @endif
</div>
