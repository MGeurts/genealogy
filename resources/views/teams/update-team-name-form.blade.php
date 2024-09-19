<x-form-section submit="updateTeamName">
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('team.team_name') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('team.team_information') }}
        </div>
    </x-slot>

    <x-slot name="form">
        {{-- team owner information --}}
        <div class="col-span-6">
            <x-label value="{{ __('team.owner') }} :" />

            <div class="flex items-center mt-2">
                <img class="object-cover w-12 h-12 rounded-full" src="{{ $team->owner->profile_photo_url }}" alt="{{ $team->owner->name }}">

                <div class="leading-tight ms-4">
                    <div class="text-sm text-gray-700">{{ $team->owner->name }}</div>
                    <div class="text-sm text-gray-700">
                        <x-ts-link href="mailto:{{ $team->owner->email }}" title="{{ __('team.send_email') }}" icon="mail" position="right">
                            {{ $team->owner->email }}
                        </x-ts-link>
                    </div>
                </div>
            </div>
        </div>

        {{-- team name --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('team.name') }} :" />

            <x-input id="name" type="text" class="block w-full mt-1" wire:model="state.name" :disabled="!Gate::check('update', $team)" />

            <x-input-error for="name" class="mt-2" />
        </div>

        {{-- team description --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="description" value="{{ __('team.description') }} :" />

            <div class="relative block w-full mt-1 mb-3">
                <x-textarea id="description" wire:model="state.description" :disabled="!Gate::check('update', $team)" rows="3"></x-textarea>
            </div>

            <x-input-error for="description" class="mt-2" />
        </div>
    </x-slot>

    @if (Gate::check('update', $team))
        <x-slot name="actions">
            <x-action-message class="p-3 mr-3 rounded bg-success-200 text-emerald-600" role="alert" on="saved">
                {{ __('app.saved') }}
            </x-action-message>

            <x-ts-button type="submit" color="primary">
                {{ __('app.save') }}
            </x-ts-button>
        </x-slot>
    @endif
</x-form-section>
