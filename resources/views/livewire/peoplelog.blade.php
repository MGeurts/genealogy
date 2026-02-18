<div class="max-w-max flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="flex-1 grow max-w-full min-w-max">
                {{ __('app.people_logbook') }}
                @if ($activities->count() > 0)
                    <x-ts-badge color="emerald" sm text="{{ $activities->total() }}" />
                @endif
            </div>

            <div class="flex-1 grow max-w-full min-w-max text-end">
                <x-ts-icon icon="tabler.history" class="inline-block size-5" />
            </div>
        </div>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 rounded-b border-neutral-100 dark:border-neutral-600 bg-neutral-200">
        {{-- Filter Section --}}
        <div class="mb-4 p-3 bg-white dark:bg-neutral-800 rounded">
            <div class="flex flex-wrap items-center justify-between gap-2">
                {{-- Subject Type Filter (Left) --}}
                <div class="flex-1 grow min-w-max max-w-36">
                    <div>
                        <x-ts-select.styled label="{{ __('app.filter') }}"  wire:model.live="subjectTypeFilter" :options="[
                            ['label' => __('app.all'), 'value' => 'all'],
                            ...collect($subjectTypes)->map(fn($type): array => [
                                'label' => $type,
                                'value' => 'App\Models\\' . $type
                            ])->toArray()
                        ]" select="label:label|value:value" required />
                    </div>
                </div>

                {{-- Per Page Selector (Right) --}}
                <div class="flex-1 grow min-w-max max-w-36 text-end">
                    <div>
                        <x-ts-select.styled label="{{ __('pagination.per_page') }}" wire:model.live="perPage" :options="[
                            ['label' => '5', 'value' => 5],
                            ['label' => '10', 'value' => 10],
                            ['label' => '25', 'value' => 25],
                            ['label' => '50', 'value' => 50],
                            ['label' => '100', 'value' => 100],
                        ]" select="label:label|value:value" required />
                    </div>
                </div>
            </div>
        </div>

        @if ($activities->count() > 0)
            {{-- Pagination controls at top --}}
            <div class="mb-4">
                {{ $activities->links() }}
            </div>

            <div class="grid grid-cols-1 gap-2">
                @foreach ($activities as $log)
                    <x-ts-card>
                        <x-slot:header>
                            <div class="p-4">
                                @if (($log['subject_type'] === 'Person' or $log['subject_type'] === 'PersonMetadata') and $log['event'] != 'DELETED')

                                    {{ $log['description'] }} :
                                    <x-ts-link href="{{ url('people/' . $log['subject_id']) }}">
                                        {{ __('person.person') }}
                                    </x-ts-link>
                                @else
                                    {{ $log['description'] }}
                                @endif
                            </div>
                        </x-slot:header>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            {{-- old values --}}
                            @php
                                $headers = [['index' => 'key', 'label' => 'Key'], ['index' => 'value', 'label' => __('app.old')]];

                                $rows = collect($log['properties_old'])
                                    ->map(function ($value, $key) {
                                        return [
                                            'key' => $key,
                                            'value' => $value,
                                        ];
                                    })
                                    ->toArray();
                            @endphp

                            <x-ts-table :$headers :$rows striped />

                            {{-- new values --}}
                            @php
                                $headers = [['index' => 'key', 'label' => 'Key'], ['index' => 'value', 'label' => __('app.new')]];

                                $rows = collect($log['properties_new'])
                                    ->map(function ($value, $key) {
                                        return [
                                            'key' => $key,
                                            'value' => $value,
                                        ];
                                    })
                                    ->toArray();
                            @endphp

                            <x-ts-table :$headers :$rows striped />
                        </div>

                        <x-slot:footer>
                            {{ $log['event'] }} {{ $log['updated_at'] }}
                            @if ($log['causer'])
                                by {{ $log['causer'] }}
                            @endif
                        </x-slot:footer>
                    </x-ts-card>
                @endforeach
            </div>

            {{-- Pagination controls at bottom --}}
            <div class="mt-4">
                {{ $activities->links() }}
            </div>
        @else
            <div class="w-3xl">
                <div class="flex justify-center" title="{{ __('app.nothing_recorded') }}">
                    <x-svg.empty-state alt="{{ __('app.nothing_recorded') }}" />
                </div>
            </div>
        @endif
    </div>
</div>
