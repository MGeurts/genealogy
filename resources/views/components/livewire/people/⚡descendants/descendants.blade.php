<div>
    @section('title')
        &vert; {{ __('person.descendants') }}
    @endsection

    <div class="md:max-w-sm md:min-w-max">
        <div class="overflow-x-auto">
            <div class="flex min-w-xs flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50 print:break-before-page">
                <div class="h-14 min-h-min rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
                    <div class="flex flex-wrap items-start justify-center gap-2">
                        <div class="max-w-full min-w-max flex-1 grow">
                            {{ __('person.descendants') }}

                            <div class="ml-2 inline-flex rounded-sm border" role="group">
                                @if ($count === $count_min)
                                    <x-ts-button
                                        square
                                        xs
                                        color="red"
                                        class="rounded-l border-0"
                                        wire:click="decrement"
                                        disabled
                                    >
                                        <x-ts-icon icon="tabler.minus" class="inline-block size-5" />
                                    </x-ts-button>
                                @else
                                    <x-ts-button
                                        square
                                        xs
                                        color="secondary"
                                        class="rounded-l border-0"
                                        wire:click="decrement"
                                    >
                                        <x-ts-icon icon="tabler.minus" class="inline-block size-5" />
                                    </x-ts-button>
                                @endif

                                <div class="w-16 text-center">{{ $count }}</div>

                                @if ($count === $count_max)
                                    <x-ts-button
                                        square
                                        xs
                                        color="red"
                                        class="rounded-r border-0"
                                        wire:click="increment"
                                        disabled
                                    >
                                        <x-ts-icon icon="tabler.plus" class="inline-block size-5" />
                                    </x-ts-button>
                                @else
                                    <x-ts-button
                                        square
                                        xs
                                        color="secondary"
                                        class="rounded-r border-0"
                                        wire:click="increment"
                                    >
                                        <x-ts-icon icon="tabler.plus" class="inline-block size-5" />
                                    </x-ts-button>
                                @endif
                            </div>
                        </div>

                        <div class="max-w-full min-w-max flex-1 grow text-end">
                            @if (count($descendants) > 1)
                                <x-ts-badge
                                    color="emerald"
                                    sm
                                    class="me-2"
                                    text="{{ $this->displayedDescendantsCount - 1 }} / {{ count($descendants) - 1 }}"
                                />
                            @endif
                            <x-ts-icon icon="tabler.binary-tree" class="inline-block size-5" />
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="tree-ltr">
                        <ul>
                            <x-tree-node.descendants
                                :person="$person"
                                :descendants="$descendants"
                                :level_max="$count"
                            />
                        </ul>
                    </div>
                </div>
            </div>

            @once
                @push('styles')
                    <link href="{{ asset('css/tree-ltr.css') }}" rel="stylesheet" />
                @endpush
            @endonce
        </div>
    </div>
</div>
