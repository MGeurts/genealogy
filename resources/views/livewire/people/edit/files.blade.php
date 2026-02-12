<div class="md:w-3xl flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="flex-1 grow max-w-full min-w-max">
                {{ __('person.files') }}
                @if ($files->count() > 0)
                    <x-ts-badge color="emerald" sm text="{{ count($files) }}" />
                @endif
            </div>

            <div class="flex-1 grow max-w-full min-w-max text-end">
                <x-ts-icon icon="tabler.files" class="inline-block size-5" />
            </div>
        </div>
    </div>

    <div class="print:hidden">
        <x-ts-card>
            <div>
                {{-- source --}}
                <div class="mb-3">
                    <x-ts-textarea id="source" wire:model="source" label="{{ __('person.source') }} :" placeholder="{{ __('person.source_hint') }}" maxlength="1024" count autofocus />
                </div>

                {{-- source_date --}}
                <div class="mb-3">
                    <x-ts-date wire:model="source_date" id="source_date" label="{{ __('person.source_date') }} :" format="YYYY-MM-DD" :max-date="now()"
                        placeholder="{{ __('person.source_date_hint') }} ..." />
                </div>

                <x-hr.narrow class="my-2" />

                {{-- uploads --}}
                <x-ts-upload id="uploads" wire:model="uploads" label="{{ __('person.files') }} :" accept="{{ $this->acceptMimes }}"
                    hint="{{ __('person.upload_max_size', ['max' => $maxSize]) }}<br/>{{ __('person.upload_accept_types', ['types' => $this->acceptedFormats]) }}" tip="{{ __('person.upload_files_tip') }}" multiple delete>
                </x-ts-upload>
            </div>

            <x-slot:footer>
                <div class="flex justify-end">
                    <x-ts-button wire:click="save()" wire:loading.attr="disabled" :disabled="empty($uploads)">
                        <span wire:loading.remove wire:target="save">{{ __('app.save') }}</span>
                        <span wire:loading wire:target="save">
                            <x-ts-icon icon="tabler.loader-2" class="inline-block size-5 animate-spin" />
                            {{ __('app.saving') ?? 'Saving...' }}
                        </span>
                    </x-ts-button>
                </div>
            </x-slot:footer>
        </x-ts-card>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 rounded-b border-neutral-100 dark:border-neutral-600 bg-neutral-200">
        @if (count($files) > 0)
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ($files as $file)
                    <x-ts-card>
                        <x-slot:header>
                            <div class="p-4">
                                <x-ts-link href="{{ $file->getUrl() }}" target="_blank" title="{{ __('app.show') }}">
                                    {{ $file['file_name'] }}
                                </x-ts-link>
                            </div>
                        </x-slot:header>

                        @php
                            $file_type = pathinfo($file['file_name'], PATHINFO_EXTENSION);
                        @endphp

                        <x-ts-link href="{{ $file->getUrl() }}" target="_blank" title="{{ __('app.show') }}">
                            <img src="{{ url('img/icons/' . $file_type . '.svg') }}" width="80px" alt="{{ $file['name'] }}" class="rounded-sm" />
                        </x-ts-link>

                        @if ($file->hasCustomProperty('source'))
                            <p>{{ __('person.source') }} :</p>
                            <p>{{ $file->getCustomProperty('source') }}</p>
                        @endif

                        @if ($file->hasCustomProperty('source_date'))
                            @php
                                $timezone = session('timezone', 'UTC');
                                $sourceDate = \Carbon\Carbon::parse($file->getCustomProperty('source_date'))->timezone($timezone)->isoFormat('LL');
                            @endphp

                            <p>{{ __('person.source_date') }} : {{ $sourceDate }}</p>
                        @endif

                        <x-slot:footer>
                            <div class="flex w-full items-center justify-between">
                                {{-- Left side --}}
                                <div class="flex items-center gap-2">
                                    @if ($file->order_column < count($files))
                                        <x-ts-button color="secondary" class="p-2!" title="{{ __('app.move_down') }}" wire:click="moveFile({{ $file->order_column }}, 'down')">
                                            <x-ts-icon icon="tabler.arrow-move-down" class="inline-block size-5" />
                                        </x-ts-button>
                                    @endif

                                    @if ($file->order_column > 1)
                                        <x-ts-button color="secondary" class="p-2!" title="{{ __('app.move_up') }}" wire:click="moveFile({{ $file->order_column }}, 'up')">
                                            <x-ts-icon icon="tabler.arrow-move-up" class="inline-block size-5" />
                                        </x-ts-button>
                                    @endif
                                </div>

                                {{-- Right side --}}
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold leading-none min-w-10 text-center">{{ strtoupper($file_type) }}</span>

                                    <x-ts-button href="{{ $file->getUrl() }}" color="secondary" class="p-2!" title="{{ __('app.download') }}" download="{{ $file['name'] }}">
                                        <x-ts-icon icon="tabler.download" class="inline-block size-5" />
                                    </x-ts-button>

                                    <span class="text-sm leading-none min-w-12.5 text-center">{{ Number::fileSize($file['size'], 2) }}</span>

                                    <x-ts-button color="red" class="p-2! text-white" title="{{ __('app.delete') }}" wire:click="deleteFile({{ $file->id }})">
                                        <x-ts-icon icon="tabler.trash" class="inline-block size-5" />
                                    </x-ts-button>
                                </div>
                            </div>
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
