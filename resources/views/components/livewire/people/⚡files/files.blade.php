<div class="flex min-w-xs flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50">
    <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="max-w-full min-w-max flex-1 grow">
                {{ __('person.files') }}
                @if ($person->getMedia('files')?->count() > 0)
                    <x-ts-badge color="emerald" sm text="{{ $person->getMedia('files')?->count() }}" />
                @endif
            </div>

            @if (auth()->user()->hasPermission('person:update') or auth()->user()->hasPermission('person:delete'))
                <div class="max-w-min min-w-max flex-1 grow text-end">
                    <x-ts-dropdown icon="tabler.menu-2" position="bottom-end">
                        @if (auth()->user()->hasPermission('person:update'))
                            <a href="/people/{{ $person->id }}/edit-files">
                                <x-ts-dropdown.items>
                                    <x-ts-icon icon="tabler.files" class="mr-2 inline-block size-5" />
                                    {{ __('person.edit_files') }}
                                </x-ts-dropdown.items>
                            </a>
                        @endif
                    </x-ts-dropdown>
                </div>
            @endif
        </div>
    </div>

    @if (count($files) > 0)
        <div class="flex w-full flex-col">
            @foreach ($files as $file)
                @php
                    $file_type = mb_substr($file['file_name'], mb_strpos($file['file_name'], '.') + 1);
                @endphp

                <div class="flex-1 grow p-2">
                    <img
                        class="inline"
                        src="{{ url('img/icons/' . $file_type . '.svg') }}"
                        width="20px"
                        alt="{{ $file['name'] }}"
                        class="rounded-sm"
                    />

                    <x-link href="{{ $file->getUrl() }}" target="_blank" title="{{ __('app.show') }}">
                        {{ $file->name }}
                    </x-link>

                    {{ $file->human_readable_size }}
                </div>
            @endforeach
        </div>
    @else
        <p class="p-2">{{ __('app.nothing_recorded') }}</p>
    @endif
</div>
