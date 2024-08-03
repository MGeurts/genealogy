<div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="flex-1 flex-grow max-w-full min-w-max">
                {{ __('person.files') }}
                @if ($this->person->countFiles() > 0)
                    <x-ts-badge color="emerald" text="{{ $this->person->countFiles() }}" />
                @endif
            </div>

            @if (auth()->user()->hasPermission('person:update') or auth()->user()->hasPermission('person:delete'))
                <div class="flex-1 flex-grow min-w-max max-w-min text-end">
                    <x-ts-dropdown icon="menu-2" position="bottom-end">
                        @if (auth()->user()->hasPermission('person:update'))
                            <a href="/people/{{ $person->id }}/edit-files">
                                <x-ts-dropdown.items>
                                    <x-ts-icon icon="files" class="mr-2" />
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
        @foreach ($files as $file)
            <div class="flex flex-wrap items-start justify-center gap-2 px-2">
                <div class="flex-1 flex-grow max-w-full min-w-max">
                    <x-link href="{{ $file->getUrl() }}">
                        {{ $file->name }}
                    </x-link>
                </div>
            </div>
        @endforeach
    @else
        <p class="p-2">{{ __('app.nothing_recorded') }}</p>
    @endif
</div>
