<div>
    @section('title')
        &vert; {{ __('app.people_logbook') }}
    @endsection

    <div class="w-full p-2">
        <div class="flex max-w-max flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="max-w-full min-w-max flex-1 grow">
                        {{ __('app.people_logbook') }}
                        @if ($this->activities->count() > 0)
                            <x-ts-badge color="emerald" sm text="{{ $this->activities->total() }}" />
                        @endif
                    </div>

                    <div class="max-w-full min-w-max flex-1 grow text-end">
                        <x-ts-icon icon="tabler.history" class="inline-block size-5" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="rounded-b border-t-2 border-neutral-100 bg-neutral-200 p-2 text-sm dark:border-neutral-600">
                {{-- Filter Section --}}
                <div class="mb-4 rounded bg-white p-3 dark:bg-neutral-800">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        {{-- Subject Type Filter (Left) --}}
                        <div class="max-w-36 min-w-max flex-1 grow">
                            <div>
                                <x-ts-select.styled
                                    label="{{ __('app.filter') }}"
                                    wire:model.live="subjectTypeFilter"
                                    :options="[
                ['label' => __('app.all'), 'value' => 'all'],
                ...collect($this->subjectTypes)->map(fn($type): array => [
                    'label' => $type,
                    'value' => 'App\Models\\' . $type
                ])->toArray()
            ]"
                                    select="label:label|value:value"
                                    required
                                />
                            </div>
                        </div>

                        {{-- Per Page Selector (Right) --}}
                        <div class="max-w-36 min-w-max flex-1 grow text-end">
                            <div>
                                <x-ts-select.styled
                                    label="{{ __('pagination.per_page') }}"
                                    wire:model.live="perPage"
                                    :options="[
                ['label' => '5', 'value' => 5],
                ['label' => '10', 'value' => 10],
                ['label' => '25', 'value' => 25],
                ['label' => '50', 'value' => 50],
                ['label' => '100', 'value' => 100],
            ]"
                                    select="label:label|value:value"
                                    required
                                />
                            </div>
                        </div>
                    </div>
                </div>

                @if ($this->activities->count() > 0)
                    {{-- Pagination controls at top --}}
                    <div class="mb-4">{{ $this->activities->links() }}</div>

                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($this->activities as $log)
                            <x-ts-card>
                                <x-slot:header>
                                    <div class="p-4">
                                        @if (($log['subject_type'] === 'Person' or $log['subject_type'] === 'PersonMetadata') and $log['event'] !== 'DELETED')
                                            {{ $log['description'] }} :
                                            <x-ts-link href="{{ url('people/' . $log['subject_id']) }}">
                                                {{ __('person.person') }}
                                            </x-ts-link>
                                        @else
                                            {{ $log['description'] }}
                                        @endif
                                    </div>
                                </x-slot:header>

                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                    {{-- old values --}}
                                    @php
                                        $headers = [['index' => 'key', 'label' => 'Key'], ['index' => 'value', 'label' => __('app.old')]];

                                        $rows = collect($log['properties_old'])
                                            ->map(function ($value, $key) {
                                                return [
                                                    'key'   => $key,
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
                                                    'key'   => $key,
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
                    <div class="mt-4">{{ $this->activities->links() }}</div>
                @else
                    <div class="w-3xl">
                        <div class="flex justify-center" title="{{ __('app.nothing_recorded') }}">
                            <x-svg.empty-state alt="{{ __('app.nothing_recorded') }}" />
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
