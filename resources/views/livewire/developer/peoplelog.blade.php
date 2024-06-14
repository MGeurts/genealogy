<div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                {{ __('person.files') }}
                @if (count($logs) > 0)
                    <x-ts-badge color="emerald" text="{{ count($logs) }}" />
                @endif
            </div>

            <div class="flex-grow min-w-max max-w-full flex-1 text-end"> <x-ts-icon icon="files" class="inline-block" /></div>
        </div>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 border-neutral-100 dark:border-neutral-600 rounded-b bg-neutral-200">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            @if (count($logs) > 0)
                @foreach ($logs as $log)
                    <x-ts-card>
                        <x-slot:header>
                            @if ($log->subject_type == 'App\Models\Person')
                                {{ strtoupper($log->event) }} :
                                <x-ts-link href="{{ url('people/' . $log->subject_id) }}">{{ substr($log->subject_type, strrpos($log->subject_type, '\\') + 1) }}
                                    {{ $log->subject_id }}
                                </x-ts-link>
                            @else
                                {{ strtoupper($log->event) }} : {{ substr($log->subject_type, strrpos($log->subject_type, '\\') + 1) }} {{ $log->subject_id }}
                            @endif
                        </x-slot:header>

                        @php
                            $data = collect(json_decode($log->properties));

                            $headers = [['index' => 'key', 'label' => 'Key'], ['index' => 'value', 'label' => 'Value']];

                            $rows = [];

                            foreach ($data['attributes'] as $key => $value) {
                                array_push($rows, [
                                    'key' => $key,
                                    'value' => $value,
                                ]);
                            }
                        @endphp

                        <x-ts-table :$headers :$rows />

                        <x-slot:footer>
                            {{ strtoupper($log->event) }} {{ date('Y-m-d h:i', strtotime($log->created_at)) }} by {{ implode(' ', array_filter([$log->firstname, $log->surname])) }}
                        </x-slot:footer>
                    </x-ts-card>
                @endforeach
            @else
                <x-ts-alert title="{{ __('person.files') }}" text="{{ __('app.nothing_recorded') }}" color="secondary" />
            @endif
        </div>
    </div>
</div>
