<x-action-section>
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('team.delete') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('Permanently delete this team.') }}
        </div>
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('Once a team is deleted, all of its resources and data will be permanently deleted. Before deleting this team, please download any data or information regarding this team that you wish to retain.') }}
        </div>

        <div class="mt-5">
            <x-button.danger wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                {{ __('team.delete') }}
            </x-button.danger>
        </div>

        {{-- delete team confirmation modal --}}
        <x-confirmation-modal wire:model.live="confirmingTeamDeletion">
            <x-slot name="title">
                {{ __('team.delete') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Are you sure you want to delete this team? Once a team is deleted, all of its resources and data will be permanently deleted.') }}
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                    {{ __('team.cancel') }}
                </x-button.secondary>

                <x-button.danger class="ms-3" wire:click="deleteTeam" wire:loading.attr="disabled">
                    {{ __('team.delete') }}
                </x-button.danger>
            </x-slot>
        </x-confirmation-modal>
    </x-slot>
</x-action-section>
