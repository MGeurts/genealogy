<x-form-section submit="exportteam">
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('gedcom.export') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('gedcom.gedcom_export') }}
        </div>

        <div class="dark:text-gray-100">
            <br />
            <p>
                {{ __('gedcom.team_gedcom_reference') }} :
                <x-link href="https://gedcom.io/specs/" target="_blank" title="{{ __('gedcom.team_gedcom_specifications') }}">
                    <x-svg.gedcom class="size-36 dark:fill-white hover:fill-primary-300 dark:hover:fill-primary-300" alt="gedcom" />
                </x-link>
            </p>
        </div>

        <x-ts-alert color="cyan">
            <x-slot:title>
                {{ __('gedcom.after_import') }} ...
            </x-slot:title>

            {{ __('gedcom.validate') }} :
            <x-ts-link class="text-white text-lg" href="https://ged-inline.org/" target="_blank" />
        </x-ts-alert>
    </x-slot>

    <x-slot name="form" enctype="multipart/form-data">
        <div class="col-span-6">
            <x-label value="{{ __('team.owner') }} :" />

            <div class="flex items-center mt-2">
                <img class="object-cover w-12 h-12 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">

                <div class="leading-tight ms-4">
                    <div class="text-sm text-gray-700">{{ $user->name }}</div>
                    <div class="text-sm text-gray-700">{{ $user->email }}</div>
                </div>
            </div>
        </div>

        <div class="col-span-6">
            <hr class="my-2 h-0.5 border-t-0 bg-neutral-600 dark:bg-neutral-400 opacity-100 dark:opacity-75" />
        </div>

        {{-- filename --}}
        <div class="col-span-6">
            <x-ts-input id="filename" type="text" class="block w-full mt-1" wire:model="filename" label="{{ __('gedcom.filename') }} :" required />
        </div>

        {{-- format --}}
        <div class="col-span-6">
            <x-ts-select.styled wire:model.live="format" id="format" :options="$formats" select="label:label|value:value" label="{{ __('gedcom.format') }} :" required />
        </div>

        {{-- encoding --}}
        <div class="col-span-6">
            <x-ts-select.styled wire:model.live="encoding" id="encoding" :options="$encodings" select="label:label|value:value" label="{{ __('gedcom.character_encoding') }} :" required />
        </div>

        {{-- line endings --}}
        <div class="col-span-6">
            <x-label for="line_endings" class="mr-5" value="{{ __('gedcom.line_endings') }} :" />

            <div class="flex">
                <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                    <x-ts-radio color="primary" wire:model="line_endings" name="line_endings" id="line_ending_windows" value="windows" label="{{ __('gedcom.line_endings_windows') }}" />
                </div>

                <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                    <x-ts-radio color="primary" wire:model="line_endings" name="line_endings" id="line_ending_unix" value="unix" label="{{ __('gedcom.line_endings_unix') }}" />
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-ts-button type="submit" color="primary">
            <x-ts-icon icon="tabler.download" class="inline-block" />
            {{ __('app.download') }}
        </x-ts-button>
    </x-slot>
</x-form-section>
