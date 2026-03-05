<div class="w-full min-w-max max-w-3xl grow dark:text-neutral-200">
    @if ($timeline->isEmpty())
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-8 text-center">
            <x-ts-icon icon="tabler.calendar-week" class="inline-block size-10" />

            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('personevents.no_events') }}</h3>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('personevents.add_events') }}</p>
        </div>
    @else
        <div class="flow-root max-w-3xl">
            <ul role="list">
                @foreach ($timeline as $index => $event)
                    <li class="mb-5">
                        <div class="relative pb-8">
                            @if (!$loop->last)
                                <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                            @endif

                            <div class="relative flex gap-5">
                                {{-- Icon --}}
                                <div>
                                    <span class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-900 bg-{{ $event['color'] }}-500">
                                        <x-ts-icon icon="tabler.{{ $event['icon'] }}" class="inline-block size-5" />
                                    </span>
                                </div>

                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    {{-- Event --}}
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $event['type_label'] }}
                                        </p>

                                        @if(!empty($event['partner']))
                                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $event['partner'] }}
                                            </p>
                                        @endif

                                        @if(!empty($event['child']))
                                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $event['child'] }}
                                            </p>
                                        @endif

                                        @if(!empty($event['place']))
                                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                                <x-ts-icon icon="tabler.map-pin" class="inline-block size-5" />
                                                {{ $event['place'] }}
                                            </p>
                                        @endif

                                        @if(!empty($event['description']))
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                                {{ $event['description'] }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Date --}}
                                    <div class="whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                        <time datetime="{{ $event['sort_date'] }}">
                                            {{ $event['date_formatted'] }}
                                        </time>

                                        @if(isset($event['year']) && $event['date'] === null)
                                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ __('app.circa') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
