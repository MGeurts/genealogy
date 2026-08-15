<div class="max-w-5xl grow dark:text-neutral-200">
    <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50 print:break-before-page">
        {{-- card header --}}
        <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="max-w-full min-w-max flex-1 grow">
                    {{ __('app.history') }}
                    @if (count($activities) > 0)
                        <x-ts-badge color="emerald" sm text="{{ count($activities) }}" />
                    @endif
                </div>

                <div class="max-w-full min-w-max flex-1 grow text-end">
                    <x-ts-icon icon="tabler.history" class="inline-block size-5" />
                </div>
            </div>
        </div>

        {{-- card body --}}
        <div class="grid grid-cols-1 gap-5 p-2">
            @foreach ($activities as $activity)
                <div class="block rounded-sm bg-neutral-200 p-3 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-600">
                    <h5 class="mb-2 text-xl leading-tight font-medium text-neutral-800 dark:text-neutral-50">
                        {{ $activity['created_at'] . ' : ' . $activity['event'] }}<br />
                        {{ $activity['causer'] ? $activity['causer'] : '' }}
                    </h5>

                    <p class="mb-2 text-base text-neutral-600 dark:text-neutral-200">
                        @php
                            $headers = [['index' => 'attribute', 'label' => __('app.attribute')], ['index' => 'old', 'label' => __('app.old')], ['index' => 'new', 'label' => __('app.new')]];

                            $rows = collect($activity['new'])
                                ->map(function ($value, $key) use ($activity) {
                                    return [
                                        'attribute' => $key,
                                        'old'       => $activity['old'][$key] ?? null,
                                        'new'       => $value,
                                    ];
                                })
                                ->toArray();
                        @endphp

                        <x-ts-table :$headers :$rows striped />
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>
