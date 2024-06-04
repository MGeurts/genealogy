<div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                {{ __('person.files') }}
                @if (count($files) > 0)
                    <x-ts-badge color="emerald" text="{{ count($files) }}" />
                @endif
            </div>

            <div class="flex-grow min-w-max max-w-full flex-1 text-end"> <x-ts-icon icon="files" class="inline-block" /></div>
        </div>
    </div>

    <div class="p-5 grid grid-cols-1 gap-5">
        {{-- file upload --}}
        <form id="form">
            <x-ts-upload id="uploads" wire:model="uploads" accept=".pdf, .txt, .doc, .docx, .xls, .xlsx" hint="Max: 10 MB, Format: pdf, txt, doc(x), xls(x)"
                tip="{{ __('person.update_files_tip') }} ..." multiple delete>
                <x-slot:footer when-uploaded>
                    <x-ts-button class="w-full" wire:click="save()">
                        {{ __('app.save') }}
                    </x-ts-button>
                </x-slot:footer>
            </x-ts-upload>
        </form>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 border-neutral-100 dark:border-neutral-600 rounded-b bg-neutral-200">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            @if (count($files) > 0)
                @foreach ($files as $file)
                    <x-ts-card class="!p-2">
                        <x-slot:header>
                            <div class="text-sm">
                                {{ $file['file_name'] }}
                            </div>
                        </x-slot:header>

                        @php
                            $file_type = substr($file['file_name'], strpos($file['file_name'], '.') + 1);
                        @endphp

                        <img src="{{ url('img/icons/' . $file_type . '.svg') }}" width="80px" alt="{{ $file['name'] }}" class="rounded" />

                        <x-slot:footer>
                            <div class="container">
                                @if ($file->order_column < count($files))
                                    <x-ts-button color="secondary" class="!p-2" title="{{ __('app.move_down') }}" wire:click="moveFile({{ $file->order_column }}, 'down')">
                                        <x-ts-icon icon="arrow-move-down" class="size-5" />
                                    </x-ts-button>
                                @endif

                                @if ($file->order_column > 1)
                                    <x-ts-button color="secondary" class="!p-2" title="{{ __('app.move_up') }}" wire:click="moveFile({{ $file->order_column }}, 'up')">
                                        <x-ts-icon icon="arrow-move-up" class="size-5" />
                                    </x-ts-button>
                                @endif
                            </div>

                            <div class="text-sm">{{ strtoupper($file_type) }}</div>

                            <x-ts-button href="{{ $file->getUrl() }}" color="secondary" class="!p-2" title="{{ __('app.download') }}" download="{{ $file['name'] }}">
                                <x-ts-icon icon="download" class="size-5" />
                            </x-ts-button>

                            <div class="text-sm text-end">{{ Number::fileSize($file['size'], 1) }}</div>

                            <x-ts-button color="danger" class="!p-2" title="{{ __('app.delete') }}" wire:click="deleteFile({{ $file->id }})">
                                <x-ts-icon icon="trash" class="size-5" />
                            </x-ts-button>
                        </x-slot:footer>
                    </x-ts-card>
                @endforeach
            @else
                <x-ts-alert title="{{ __('person.photos') }}" text="{{ __('app.nothing_recorded') }}" color="secondary" />
            @endif
        </div>
    </div>
</div>
