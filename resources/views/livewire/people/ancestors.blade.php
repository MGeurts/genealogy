<div>
    <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
        <div class="h-14 min-h-min p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    <span class="mr-2">{{ __('person.ancestors') }}</span>
                    <div class="inline-flex border rounded" role="group">
                        <button type="button" wire:click="decrement" data-te-ripple-init data-te-ripple-color="light" @if ($count === $count_min) disabled @endif
                            class="inline-block rounded-l {{ $count === $count_min ? 'bg-danger hover:bg-danger-600 focus:bg-danger-600 active:bg-danger-700' : 'bg-secondary hover:bg-secondary-600 focus:bg-secondary-600 active:bg-secondary-700' }} px-1 text-xs transition duration-150 ease-in-out focus:outline-none focus:ring-0">
                            <x-icon.tabler icon="minus" />
                        </button>

                        <div class="w-8 text-center">{{ $count }}</div>

                        <button type="button" wire:click="increment" data-te-ripple-init data-te-ripple-color="light" @if ($count === $count_max) disabled @endif
                            class="inline-block rounded-r {{ $count === $count_max ? 'bg-danger hover:bg-danger-600 focus:bg-danger-600 active:bg-danger-700' : 'bg-secondary hover:bg-secondary-600 focus:bg-secondary-600 active:bg-secondary-700' }} px-1 text-xs transition duration-150 ease-in-out focus:outline-none focus:ring-0">
                            <x-icon.tabler icon="plus" />
                        </button>
                    </div>
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                    <x-icon.tabler icon="binary-tree" class="me-2 rotate-180" />
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <div class="tree-rtl @if ($count === 1) pb-2 @endif">
                <ul>
                    <x-tree-node.ancestors :person="$person" :ancestors="$ancestors" :level_max="$count" />
                </ul>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="{{ asset('css/tree-rtl.css') }}" rel="stylesheet">
    @endpush
</div>
