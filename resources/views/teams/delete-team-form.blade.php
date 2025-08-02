<x-action-section>
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('team.delete') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('team.delete_team_message') }}
        </div>
    </x-slot>

    <x-slot name="content">
        @php
            $headers = [['index' => 'object', 'label' => $team->name], ['index' => 'count', 'label' => '#']];

            $rows = [
                ['object' => __('team.users'), 'count' => count($team->users)],
                ['object' => __('team.persons'), 'count' => count($team->persons)],
                ['object' => __('team.couples'), 'count' => count($team->couples)],
            ];
        @endphp

        <x-ts-table :$headers :$rows striped />

        <x-hr.normal />

        @if ($team->isDeletable())
            <div class="max-w-xl text-sm text-gray-600">
                {{ __('team.delete_team_text') }}
            </div>

            <div class="mt-5">
                <x-ts-button color="red" wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                    {{ __('team.delete') }}
                </x-ts-button>
            </div>

            {{-- delete team confirmation modal --}}
            <x-confirmation-modal wire:model.live="confirmingTeamDeletion">
                <x-slot name="title">
                    {{ __('team.delete') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('team.delete_team_sure') }}
                </x-slot>

                <x-slot name="footer">
                    <x-ts-button color="secondary" wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                        {{ __('team.cancel') }}
                    </x-ts-button>

                    <x-ts-button color="red" class="ms-3" wire:click="deleteTeam" wire:loading.attr="disabled">
                        {{ __('team.delete') }}
                    </x-ts-button>
                </x-slot>
            </x-confirmation-modal>
        @else
            <x-ts-alert title="{{ __('team.delete') }}" text="{{ __('team.can_not_delete') }}" color="cyan" />
        @endif
    </x-slot>
</x-action-section>
