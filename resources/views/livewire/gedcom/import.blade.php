<div>
    <x-form-section submit="importTeam">
        <x-slot name="title">
            <div class="dark:text-gray-400">
                {{ __('team.team_details') }}
            </div>
        </x-slot>

        <x-slot name="description">
            <div class="dark:text-gray-100">
                {{ __('gedcom.team_create_new_gedcom') }}
            </div>

            <div class="dark:text-gray-100">
                <br />
                <p>{{ __('gedcom.team_gedcom_reference') }} :
                    <x-link href="https://gedcom.io/specs/" target="_blank" title="{{ __('gedcom.team_gedcom_specifications') }}">
                        <x-svg.gedcom class="size-36 dark:fill-white hover:fill-primary-300 dark:hover:fill-primary-300" alt="gedcom" />
                    </x-link>
                </p>
            </div>
        </x-slot>

        <x-slot name="form" enctype="multipart/form-data">
            <div class="col-span-6">
                <x-label value="{{ __('team.owner') }} :" />

                <div class="flex items-center mt-2">
                    <img class="w-12 h-12 rounded-full object-cover" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}">

                    <div class="ms-4 leading-tight">
                        <div class="text-gray-700 text-sm">{{ $this->user->name }}</div>
                        <div class="text-gray-700 text-sm">{{ $this->user->email }}</div>
                    </div>
                </div>
            </div>

            {{-- team name --}}
            <div class="col-span-6 sm:col-span-4">
                <x-ts-input id="name" name="name" type="text" class="mt-1 block w-full" wire:model="name" autofocus required label="{{ __('team.name') }} : *" />
            </div>

            {{-- team description --}}
            <div class="col-span-6 sm:col-span-4">
                <div class="relative mt-1 mb-3 block w-full">
                    <x-ts-textarea id="description" name="description" wire:model="description" label="{{ __('team.description') }} :" />
                </div>

                <x-input-error for="description" class="mt-2" />
            </div>

            {{-- upload --}}
            <div class="col-span-6 sm:col-span-4">
                <x-ts-upload id="file" wire:model="file" accept=".ged" label="{{ __('gedcom.gedcom_file') }} : *" hint="{{ __('gedcom.team_gedcom_hint') }}"
                    tip="{{ __('gedcom.team_gedcom_tip') }}" required>
                    <x-slot:footer>
                        <x-ts-alert class="w-full">{{ __('gedcom.team_gedcom_version') }}</x-ts-alert>
                    </x-slot:footer>
                </x-ts-upload>

                <x-input-error for="file" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-ts-button type="submit" color="primary">
                {{ __('team.create') }}
            </x-ts-button>
        </x-slot>
    </x-form-section>

    <div>
        <div class="my-2 dark:text-gray-400">{{ __('app.terminal') }} :</div>

        <div class="terminal rounded dark:border-2 dark:border-white" wire:stream="stream">
            {!! $output !!}
        </div>
    </div>

    @push('styles')
        <link href="{{ asset('css/terminal.css') }}" rel="stylesheet">
    @endpush
</div>
