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
                <img class="object-cover w-12 h-12 rounded-full" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">

                <div class="leading-tight ms-4">
                    <div class="text-sm text-gray-700">{{ auth()->user()->name }}</div>
                    <div class="text-sm text-gray-700">{{ auth()->user()->email }}</div>
                </div>
            </div>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('team.name') }} :" />
            <x-input id="name" type="text" class="block w-full mt-1" wire:model="state.name" autofocus />
            <x-input-error for="name" class="mt-2" />
        </div>

        {{-- team description --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="description" value="{{ __('team.description') }} :" />

            <div class="relative block w-full mt-1 mb-3">
                <x-textarea id="description" wire:model="state.description" rows="3"></x-textarea>
            </div>

            <x-input-error for="description" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-ts-button type="submit" color="primary">
            {{ __('team.create') }}
        </x-ts-button>
    </x-slot>
</x-form-section>
