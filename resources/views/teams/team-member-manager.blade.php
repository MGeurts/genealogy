<div>
    @if (Gate::check('addTeamMember', $team))
        <x-section-border />

        {{-- add team member --}}
        <div class="mt-10 sm:mt-0">
            <x-form-section submit="addTeamMember">
                <x-slot name="title">
                    <div class="dark:text-gray-400">
                        {{ __('team.team_add_member') }}
                    </div>
                </x-slot>

                <x-slot name="description">
                    <div class="dark:text-gray-100">
                        {{ __('team.team_add_member_message') }}
                    </div>
                </x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            {{ __('team.team_provide_email') }}<br />
                            {{ __('team.team_provide_role') }}
                        </div>
                    </div>

                    {{-- member email --}}
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="email" value="{{ __('team.email') }} :" />
                        <x-input id="email" type="email" class="block w-full mt-1" wire:model="addTeamMemberForm.email" />
                        <x-input-error for="email" class="mt-2" />
                    </div>

                    {{-- role --}}
                    @if (count($this->roles) > 0)
                        <div class="col-span-6 lg:col-span-4">
                            <x-label for="role" value="{{ __('team.role') }} :" />
                            <x-input-error for="role" class="mt-2" />

                            <div class="relative z-0 mt-1 border border-gray-200 rounded cursor-pointer dark:border-gray-700">
                                @foreach ($this->roles as $index => $role)
                                    <button type="button"
                                        class="relative px-4 py-3 inline-flex w-full rounded focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ $index > 0 ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}"
                                        wire:click="$set('addTeamMemberForm.role', '{{ $role->key }}')">
                                        <div @class([
                                            'opacity-50' =>
                                                isset($addTeamMemberForm['role']) and
                                                $addTeamMemberForm['role'] !== $role->key,
                                        ])>
                                            {{-- role name --}}
                                            <div class="flex items-center">
                                                <div class="text-sm text-gray-600 {{ $addTeamMemberForm['role'] == $role->key ? 'font-semibold' : '' }}">
                                                    <b>{{ __('jetstream.role_' . strtolower($role->key) . '_name') }}</b>
                                                </div>

                                                @if ($addTeamMemberForm['role'] == $role->key)
                                                    <x-ts-icon icon="circle-check" class="inline-block size-5 ms-2 text-emerald-600" />
                                                @endif
                                            </div>

                                            {{-- role description --}}
                                            <div class="mt-2 text-xs text-gray-600 text-start">
                                                {{ __('jetstream.role_' . strtolower($role->key) . '_description') }}
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </x-slot>

                <x-slot name="actions">
                    <x-action-message class="p-3 mr-3 rounded bg-success-200 text-emerald-600" role="alert" on="saved">
                        {{ __('app.saved') }}
                    </x-action-message>

                    <x-ts-button type="submit" color="primary">
                        {{ __('app.add') }}
                    </x-ts-button>
                </x-slot>
            </x-form-section>
        </div>
    @endif

    @if ($team->teamInvitations->isNotEmpty() and Gate::check('addTeamMember', $team))
        <x-section-border />

        {{-- team member invitations --}}
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    <div class="dark:text-gray-400">
                        {{ __('team.team_pending') }}
                    </div>
                </x-slot>

                <x-slot name="description">
                    <div class="dark:text-gray-100">
                        {{ __('team.team_pending_message') }}
                    </div>
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($team->teamInvitations as $invitation)
                            <div class="flex items-center justify-between">
                                <div class="text-gray-600">{{ $invitation->email }}</div>

                                <div class="flex items-center">
                                    @if (Gate::check('removeTeamMember', $team))
                                        {{-- cancel team invitation --}}
                                        <x-ts-button color="danger" wire:click="cancelTeamInvitation({{ $invitation->id }})">
                                            {{ __('app.cancel') }}
                                        </x-ts-button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-action-section>
        </div>
    @endif

    @if ($team->users->isNotEmpty())
        <x-section-border />

        {{-- manange team members --}}
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    <div class="dark:text-gray-400">
                        {{ __('team.team_members') }}
                    </div>
                </x-slot>

                <x-slot name="description">
                    <div class="dark:text-gray-100">
                        {{ __('team.team_members_message') }}
                    </div>
                </x-slot>

                {{-- team member list --}}
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($team->users->sortBy('name') as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="object-cover rounded-full size-8" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    <div class="ms-3">{{ $user->name }}</div>
                                </div>

                                <div class="flex items-center">
                                    {{-- manage team member role --}}
                                    @if (Gate::check('updateTeamMember', $team) and Laravel\Jetstream\Jetstream::hasRoles())
                                        <x-ts-button class="min-w-28 ms-3" wire:click="manageRole('{{ $user->id }}')" title="{{ __('team.change_role') }}">
                                            {{ __('jetstream.role_' . strtolower(Laravel\Jetstream\Jetstream::findRole($user->membership->role)->key) . '_name') }}
                                        </x-ts-button>
                                    @elseif (Laravel\Jetstream\Jetstream::hasRoles())
                                        <div class="text-sm min-w-28 ms-3">
                                            {{ __('jetstream.role_' . strtolower(Laravel\Jetstream\Jetstream::findRole($user->membership->role)->key) . '_name') }}
                                        </div>
                                    @endif

                                    @if ($this->user->id === $user->id)
                                        {{-- leave team --}}
                                        <x-ts-button color="danger" class="min-w-28 ms-3" wire:click="$toggle('confirmingLeavingTeam')" title="{{ __('team.leave_team') }}">
                                            {{ __('team.leave') }}
                                        </x-ts-button>
                                    @elseif (Gate::check('removeTeamMember', $team))
                                        {{-- remove team member --}}
                                        <x-ts-button color="danger" class="min-w-28 ms-3" wire:click="confirmTeamMemberRemoval('{{ $user->id }}')" title="{{ __('team.remove_member') }}">
                                            {{ __('team.remove') }}
                                        </x-ts-button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-action-section>
        </div>
    @endif

    {{-- role management modal --}}
    <x-dialog-modal wire:model.live="currentlyManagingRole">
        <x-slot name="title">
            {{ __('team.manage_role') }}
        </x-slot>

        <x-slot name="content">
            <div class="relative z-0 mt-1 border border-gray-200 rounded cursor-pointer dark:border-gray-700">
                @foreach ($this->roles as $index => $role)
                    <button type="button"
                        class="relative px-4 py-3 inline-flex w-full rounded focus:z-10 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 {{ $index > 0 ? 'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none' : '' }} {{ !$loop->last ? 'rounded-b-none' : '' }}"
                        wire:click="$set('currentRole', '{{ $role->key }}')">
                        <div @class(['opacity-75' => $currentRole !== $role->key])>
                            {{-- role name --}}
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 {{ $currentRole == $role->key ? 'font-semibold' : '' }}">
                                    {{ __('jetstream.role_' . strtolower($role->key) . '_name') }}
                                </div>

                                @if ($currentRole == $role->key)
                                    <x-ts-icon icon="circle-check" class="inline-block size-5 ms-2 text-emerald-600" />
                                @endif
                            </div>

                            {{-- role description --}}
                            <div class="mt-2 text-xs text-gray-600">
                                {{ __('jetstream.role_' . strtolower($role->key) . '_description') }}
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-ts-button color="secondary" wire:click="stopManagingRole" wire:loading.attr="disabled">
                {{ __('app.cancel') }}
            </x-ts-button>

            <x-ts-button color="primary" class="ms-3" wire:click="updateRole" wire:loading.attr="disabled">
                {{ __('app.save') }}
            </x-ts-button>
        </x-slot>
    </x-dialog-modal>

    {{-- leave team confirmation modal --}}
    <x-confirmation-modal wire:model.live="confirmingLeavingTeam">
        <x-slot name="title">
            {{ __('team.leave_team') }}
        </x-slot>

        <x-slot name="content">
            {{ __('team.leave_team_sure') }}
        </x-slot>

        <x-slot name="footer">
            <x-ts-button color="secondary" wire:click="$toggle('confirmingLeavingTeam')" wire:loading.attr="disabled">
                {{ __('app.cancel') }}
            </x-ts-button>

            <x-ts-button color="danger" class="ms-3" wire:click="leaveTeam" wire:loading.attr="disabled">
                {{ __('team.leave') }}
            </x-ts-button>
        </x-slot>
    </x-confirmation-modal>

    {{-- remove team member confirmation modal --}}
    <x-confirmation-modal wire:model.live="confirmingTeamMemberRemoval">
        <x-slot name="title">
            {{ __('team.remove_menber') }}
        </x-slot>

        <x-slot name="content">
            {{ __('team.remove_member_sure') }}
        </x-slot>

        <x-slot name="footer">
            <x-ts-button color="secondary" wire:click="$toggle('confirmingTeamMemberRemoval')" wire:loading.attr="disabled">
                {{ __('app.cancel') }}
            </x-ts-button>

            <x-ts-button color="danger" class="ms-3" wire:click="removeTeamMember" wire:loading.attr="disabled">
                {{ __('team.remove') }}
            </x-ts-button>
        </x-slot>
    </x-confirmation-modal>
</div>
