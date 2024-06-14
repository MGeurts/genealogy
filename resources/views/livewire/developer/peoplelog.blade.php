<div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                {{ __('person.people_log') }}
                @if (count($logs) > 0)
                    <x-ts-badge color="emerald" text="{{ count($logs) }}" />
                @endif
            </div>

            <div class="flex-grow min-w-max max-w-full flex-1 text-end"> <x-ts-icon icon="files" class="inline-block" /></div>
        </div>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 border-neutral-100 dark:border-neutral-600 rounded-b bg-neutral-200">
        <div class="grid grid-cols-1 gap-2">
            @if (count($logs) > 0)
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
                                $headers = [['index' => 'key', 'label' => 'Key'], ['index' => 'value', 'label' => 'Old value']];

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
                                $headers = [['index' => 'key', 'label' => 'Key'], ['index' => 'value', 'label' => 'New value']];

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
            @else
                <x-ts-alert title="{{ __('person.files') }}" text="{{ __('app.nothing_recorded') }}" color="secondary" />
            @endif
        </div>
    </div>
</div>
