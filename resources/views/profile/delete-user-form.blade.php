<x-action-section>
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('user.delete_account') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('user.delete_account_permanently') }}
        </div>
    </x-slot>

    <x-slot name="content">
        @php
            $headers = [
                ['index' => 'team', 'label' => __('team.team')],
                ['index' => 'users', 'label' => __('team.users')],
                ['index' => 'persons', 'label' => __('team.persons')],
                ['index' => 'couples', 'label' => __('team.couples')],
                ['index' => 'personal', 'label' => __('team.team_personal') . '?'],
            ];

            $rows = collect(auth()->user()->teamsStatistics())
                ->map(function ($team) {
                    return [
                        'team' => $team->name,
                        'users' => $team->users_count > 0 ? $team->users_count : '',
                        'persons' => $team->persons_count > 0 ? $team->persons_count : '',
                        'couples' => $team->couples_count > 0 ? $team->couples_count : '',
                        'personal' => $team->personal_team,
                    ];
                })
                ->toArray();
        @endphp

        <x-ts-table :$headers :$rows striped>
            @interact('column_personal', $row)
                @if ($row['personal'])
                    <x-ts-icon icon="tabler.circle-check" class="inline-block size-5 text-emerald-600" />
                @endif
            @endinteract
        </x-ts-table>

        <x-hr.normal />

        @if (auth()->user()->isDeletable())
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                {{ __('user.once_deleted') }}
            </div>

            <div class="mt-5">
                <x-ts-button color="red" wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                    {{ __('user.delete_account') }}
                </x-ts-button>
            </div>

            {{-- delete user confirmation modal --}}
            <x-dialog-modal wire:model.live="confirmingUserDeletion">
                <x-slot name="title">
                    {{ __('user.delete_account') }}
                </x-slot>

                <x-slot name="content">
                    {{ __('user.sure') }}

                    <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                        <x-input type="password" class="block w-3/4 mt-1" autocomplete="current-password" placeholder="{{ __('user.password') }}" x-ref="password" wire:model="password"
                            wire:keydown.enter="deleteUser" />

                        <x-input-error for="password" class="mt-2" />
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <x-ts-button color="secondary" wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                        {{ __('user.cancel') }}
                    </x-ts-button>

                    <x-ts-button color="red" class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                        {{ __('user.delete_account') }}
                    </x-ts-button>
                </x-slot>
            </x-dialog-modal>
        @else
            <x-ts-alert title="{{ __('user.delete_account') }}" text="{{ __('user.can_not_delete') }}" color="cyan" />
        @endif
    </x-slot>
</x-action-section>
