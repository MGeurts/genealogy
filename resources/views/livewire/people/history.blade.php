<div class="max-w-5xl grow dark:text-neutral-200">
    <div
        class="print:break-before-page flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        {{-- card header --}}
        <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="flex-1 grow max-w-full min-w-max">
                    {{ __('app.history') }}
                    @if (count($activities) > 0)
                        <x-ts-badge color="emerald" text="{{ count($activities) }}" />
                    @endif
                </div>

                <div class="flex-1 grow max-w-full min-w-max text-end">
                    <x-ts-icon icon="tabler.history" class="inline-block" />
                </div>
            </div>
        </div>

        {{-- card body --}}
        <div class="grid grid-cols-1 gap-5 p-2">
            @foreach ($activities as $activity)
                <div class="block rounded-sm bg-neutral-200 dark:bg-neutral-600 p-3 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
                    <h5 class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
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
                                        'old' => $activity['old'][$key] ?? null,
                                        'new' => $value,
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
