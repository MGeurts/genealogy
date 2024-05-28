<x-form-section submit="createTeam">
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('team.team_details') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('team.team_create_new') }}
        </div>
    </x-slot>

    <x-slot name="form">
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

        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('team.name') }} :" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" autofocus />
            <x-input-error for="name" class="mt-2" />
        </div>

        {{-- team description --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="description" value="{{ __('team.description') }} :" />

            <div class="relative mt-1 mb-3 block w-full">
                <textarea id="description" wire:model="state.description" class="peer block min-h-[auto] w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded shadow-sm px-3 py-[0.32rem]"
                    rows="3">
                </textarea>
            </div>

            <x-input-error for="description" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-ts-button color="primary">
            {{ __('team.create') }}
        </x-ts-button>
    </x-slot>
</x-form-section>
