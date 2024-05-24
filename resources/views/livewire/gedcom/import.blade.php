<x-form-section submit="createTeam">
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('team.team_details') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('team.team_create_new_gedcom') }}
        </div>

        <div class="dark:text-gray-100">
            <br />
            <p>{{ __('team.team_gedcom_reference') }} :
                <x-link href="https://gedcom.io/specs/" target="_blank" title="{{ __('team.team_gedcom_specifications') }}">
                    <x-svg.gedcom class="size-36 dark:fill-white hover:fill-primary-300 dark:hover:fill-primary-300" alt="gedcom" />
                </x-link>
            </p>
        </div>
    </x-slot>

    <x-slot name="form" enctype="multipart/form-data">
        <div class="col-span-6">
            <x-label value="{{ __('team.owner') }}" />

            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full object-cover" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}">

                <div class="ms-4 leading-tight">
                    <div class="text-gray-900 dark:text-white">{{ $this->user->name }}</div>
                    <div class="text-gray-700 dark:text-gray-700 text-sm">{{ $this->user->email }}</div>
                </div>
            </div>
        </div>

        {{-- team name --}}
        <div class="col-span-6 sm:col-span-4">
            <x-ts-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" autofocus required label="{{ __('team.name') }} *" />
        </div>

        {{-- team description --}}
        <div class="col-span-6 sm:col-span-4">
            <div class="relative mt-1 mb-3 block w-full">
                <x-ts-textarea wire:model="state.description" id="description" label="{{ __('team.description') }} *" resize-auto required />
            </div>

            <x-input-error for="description" class="mt-2" />
        </div>

        {{-- gedcom file input --}}
        <div class="col-span-6 sm:col-span-4">
            <x-ts-upload accept=".ged" wire:model="file" label="{{ __('team.gedcom_file') }} *" hint="{{ __('team.team_gedcom_hint') }}" tip="{{ __('team.team_gedcom_tip') }}" required>
                <x-slot:footer>
                    <x-button class="w-full">{{ __('team.team_gedcom_version') }}</x-button>
                </x-slot:footer>
            </x-ts-upload>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-ts-button color="primary">
            {{ __('team.create') }}
        </x-ts-button>
    </x-slot>
</x-form-section>
