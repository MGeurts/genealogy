<div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="flex-1 flex-grow max-w-full min-w-max">
                {{ __('person.people_log') }}
                @if (count($logs) > 0)
                    <x-ts-badge color="emerald" text="{{ count($logs) }}" />
                @endif
            </div>

            <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                <x-ts-icon icon="history" class="inline-block" />
            </div>
        </div>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 rounded-b border-neutral-100 dark:border-neutral-600 bg-neutral-200">
            @if (count($logs) > 0)
                <div class="grid grid-cols-1 gap-2">
                    @foreach ($logs as $log)
                        <x-ts-card>
                            <x-slot:header>
                                @if ($log['subject_type'] == 'Person' and $log['event'] != 'DELETED')
                                    {{ strtoupper($log['event']) }} :
                                    <x-ts-link href="{{ url('people/' . $log['subject_id']) }}">{{ $log['subject_type'] }}
                                        {{ $log['subject_id'] }}
                                    </x-ts-link>
                                @else
                                    {{ $log['event'] }} : {{ $log['subject_type'] }} {{ $log['subject_id'] }}
                                @endif
                            </x-slot:header>

                            <div class="grid grid-cols-2 gap-2">
                                {{-- old values --}}
                                @php
                                    $headers = [['index' => 'key', 'label' => 'Key'], ['index' => 'value', 'label' => __('app.old')]];

                                    $rows = [];

                                    foreach ($log['properties_old'] as $key => $value) {
                                        array_push($rows, [
                                            'key' => $key,
                                            'value' => $value,
                                        ]);
                                    }
                                @endphp

                                <x-ts-table :$headers :$rows />

                                {{-- new values --}}
                                @php
                                    $headers = [['index' => 'key', 'label' => 'Key'], ['index' => 'value', 'label' => __('app.new')]];

                                    $rows = [];

                                    foreach ($log['properties_new'] as $key => $value) {
                                        array_push($rows, [
                                            'key' => $key,
                                            'value' => $value,
                                        ]);
                                    }
                                @endphp

                                <x-ts-table :$headers :$rows />
                            </div>

                            <x-slot:footer>
                                {{ $log['event'] }} {{ $log['created_at'] }} by {{ $log['causer'] }}
                            </x-slot:footer>
                        </x-ts-card>
                    @endforeach
                </div>
            @else
                <div>
                    <x-ts-alert title="{{ __('person.files') }}" text="{{ __('app.nothing_recorded') }}" color="cyan" />
                </div>
            @endif
    </div>
</div>
