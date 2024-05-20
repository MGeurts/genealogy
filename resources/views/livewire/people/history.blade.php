<div class="grow max-w-5xl dark:text-neutral-200">
    <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        {{-- card header --}}
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('app.history') }}
                    @if (count($activities) > 0)
                        <x-ts-badge color="emerald" text="{{ count($activities) }}" />
                    @endif
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                    <x-ts-icon icon="history" class="inline-block" />
                </div>
            </div>
        </div>

        {{-- card body --}}
        <div class="p-2 grid grid-cols-1 gap-5">
            @foreach ($activities as $activity)
                <div class="block rounded bg-neutral-200 dark:bg-neutral-600 p-3 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
                    <h5 class="mb-2 text-xl font-medium leading-tight text-neutral-800 dark:text-neutral-50">
                        {{ $activity['created_at'] . ' : ' . $activity['event'] }}<br />
                        {{ $activity['causer'] ? $activity['causer'] : '' }}
                    </h5>

                    <p class="mb-2 text-base text-neutral-600 dark:text-neutral-200">
                        @php
                            $headers = [['index' => 'attribute', 'label' => __('app.attribute')], ['index' => 'old', 'label' => __('app.old')], ['index' => 'new', 'label' => __('app.new')]];

                            $rows = [];

                            foreach ($activity['new'] as $key => $value) {
                                array_push($rows, [
                                    'attribute' => $key,
                                    'old' => $activity['old'] ? $activity['old'][$key] : null,
                                    'new' => $value,
                                ]);
                            }
                        @endphp

                        <x-ts-table :$headers :$rows />
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</div>
