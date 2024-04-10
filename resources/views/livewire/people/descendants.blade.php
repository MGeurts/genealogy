<div>
    <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="h-14 min-h-min p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    <span class="mr-1">{{ __('person.descendants') }}</span>
                    <div class="inline-flex border rounded" role="group">
                        @if ($count === $count_min)
                            <x-ts-button square xs color="danger" class="rounded-l border-0" wire:click="decrement" disabled>
                                <x-icon.tabler icon="minus" />
                            </x-ts-button>
                        @else
                            <x-ts-button square xs color="secondary" class="rounded-l border-0" wire:click="decrement">
                                <x-icon.tabler icon="minus" />
                            </x-ts-button>
                        @endif

                        <div class="w-8 text-center">{{ $count }}</div>

                        @if ($count === $count_max)
                            <x-ts-button square xs color="danger" class="rounded-r border-0" wire:click="increment" disabled>
                                <x-icon.tabler icon="plus" />
                            </x-ts-button>
                        @else
                            <x-ts-button square xs color="secondary" class="rounded-r border-0" wire:click="increment">
                                <x-icon.tabler icon="plus" />
                            </x-ts-button>
                        @endif
                    </div>
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                    <x-icon.tabler icon="binary-tree" class="me-2" />
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <div class="tree-ltr">
                <ul>
                    <x-tree-node.descendants :person="$person" :descendants="$descendants" :level_max="$count" />
                </ul>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="{{ asset('css/tree-ltr.css') }}" rel="stylesheet">
    @endpush
</div>
