<div>
    <div
        class="min-w-80 print:break-before-page flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="flex-1 flex-grow max-w-full min-w-max">
                    <span class="mr-1">{{ __('person.descendants') }}</span>
                    <div class="inline-flex border rounded" role="group">
                        @if ($count === $count_min)
                            <x-ts-button square xs color="danger" class="border-0 rounded-l" wire:click="decrement" disabled>
                                <x-ts-icon icon="minus" class="size-5" />
                            </x-ts-button>
                        @else
                            <x-ts-button square xs color="secondary" class="border-0 rounded-l" wire:click="decrement">
                                <x-ts-icon icon="minus" class="size-5" />
                            </x-ts-button>
                        @endif

                        <div class="w-16 text-center">{{ $count }}</div>

                        @if ($count === $count_max)
                            <x-ts-button square xs color="danger" class="border-0 rounded-r" wire:click="increment" disabled>
                                <x-ts-icon icon="plus" class="size-5" />
                            </x-ts-button>
                        @else
                            <x-ts-button square xs color="secondary" class="border-0 rounded-r" wire:click="increment">
                                <x-ts-icon icon="plus" class="size-5" />
                            </x-ts-button>
                        @endif
                    </div>
                </div>

                <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                    <x-ts-icon icon="binary-tree" class="inline-block" />
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
