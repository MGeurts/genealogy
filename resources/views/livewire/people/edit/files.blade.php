<div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="flex-1 flex-grow max-w-full min-w-max">
                {{ __('person.files') }}
                @if ($files->count() > 0)
                    <x-ts-badge color="emerald" text="{{ count($files) }}" />
                @endif
            </div>

            <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                <x-ts-icon icon="tabler.files" class="inline-block" />
            </div>
        </div>
    </div>

    <div class="p-2 print:hidden">
        {{-- source --}}
        <div class="mb-3">
            <x-ts-textarea id="source" wire:model="source" label="{{ __('person.source') }} :" placeholder="{{ __('person.source_hint') }} ..." maxlength="1024" count autofocus />
        </div>

        {{-- source_date --}}
        <div class="mb-3">
            <x-ts-date wire:model="source_date" id="source_date" label="{{ __('person.source_date') }} :" format="YYYY-MM-DD" :max-date="now()" placeholder="{{ __('person.source_date_hint') }} ..." />
        </div>

        <x-hr.narrow class="my-2" />

        {{-- upload --}}
        <x-ts-upload id="uploads" wire:model="uploads" label="{{ __('person.files') }} :" accept=".pdf, .txt, .doc, .docx, .xls, .xlsx" hint="Max : 10 MB,<br/>Format : pdf, txt, doc(x), xls(x)"
            tip="{{ __('person.update_files_tip') }} ..." multiple delete>
            <x-slot:footer when-uploaded>
                <x-ts-button class="w-full" wire:click="save()">
                    {{ __('app.save') }}
                </x-ts-button>
            </x-slot:footer>
        </x-ts-upload>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 rounded-b border-neutral-100 dark:border-neutral-600 bg-neutral-200">
        @if (count($files) > 0)
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ($files as $file)
                    <x-ts-card class="!p-2">
                        <x-slot:header>
                            <x-ts-link href="{{ $file->getUrl() }}" target="_blank" title="{{ __('app.show') }}">
                                {{ $file['file_name'] }}
                            </x-ts-link>
                        </x-slot:header>

                        @php
                            $file_type = substr($file['file_name'], strpos($file['file_name'], '.') + 1);
                        @endphp

                        <x-ts-link href="{{ $file->getUrl() }}" target="_blank" title="{{ __('app.show') }}">
                            <img src="{{ url('img/icons/' . $file_type . '.svg') }}" width="80px" alt="{{ $file['name'] }}" class="rounded" />
                        </x-ts-link>

                        @if ($file->hasCustomProperty('source'))
                            <p>{{ __('person.source') }} :</p>
                            <p>{{ $file->getCustomProperty('source') }}</p>
                        @endif

                        @if ($file->hasCustomProperty('source_date'))
                            <p>{{ __('person.source_date') }} : {{ Carbon\Carbon::parse($file->getCustomProperty('source_date'))->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') }}</p>
                        @endif

                        <x-slot:footer>
                            <div class="w-full">
                                @if ($file->order_column < count($files))
                                    <x-ts-button color="secondary" class="!p-2" title="{{ __('app.move_down') }}" wire:click="moveFile({{ $file->order_column }}, 'down')">
                                        <x-ts-icon icon="tabler.arrow-move-down" class="size-5" />
                                    </x-ts-button>
                                @endif

                                @if ($file->order_column > 1)
                                    <x-ts-button color="secondary" class="!p-2" title="{{ __('app.move_up') }}" wire:click="moveFile({{ $file->order_column }}, 'up')">
                                        <x-ts-icon icon="tabler.arrow-move-up" class="size-5" />
                                    </x-ts-button>
                                @endif
                            </div>

                            {{ strtoupper($file_type) }}

                            <x-ts-button href="{{ $file->getUrl() }}" color="secondary" class="!p-2" title="{{ __('app.download') }}" download="{{ $file['name'] }}">
                                <x-ts-icon icon="tabler.download" class="size-5" />
                            </x-ts-button>

                            <div class="text-end">{{ Number::fileSize($file['size'], 2) }}</div>

                            <x-ts-button color="danger" class="!p-2 text-white" title="{{ __('app.delete') }}" wire:click="deleteFile({{ $file->id }})">
                                <x-ts-icon icon="tabler.trash" class="size-5" />
                            </x-ts-button>
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
