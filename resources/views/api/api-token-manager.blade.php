<div>
    {{-- generate api token --}}
    <x-form-section submit="createApiToken">
        <x-slot name="title">
            <div class="dark:text-gray-400">
                {{ __('api.create_api_token') }}
            </div>
        </x-slot>

        <x-slot name="description">
            <div class="dark:text-gray-100">
                {{ __('api.api_tokens_explanation') }}
            </div>
        </x-slot>

        <x-slot name="form">
            {{-- token name --}}
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('api.token_name') }} :" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="createApiTokenForm.name" autofocus />
                <x-input-error for="name" class="mt-2" />
            </div>

            {{-- token permissions --}}
            @if (Laravel\Jetstream\Jetstream::hasPermissions())
                <div class="col-span-6">
                    <x-label for="permissions" value="{{ __('api.permissions') }} :" />

                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (Laravel\Jetstream\Jetstream::$permissions as $permission)
                            <label class="flex items-center">
                                <x-checkbox wire:model="createApiTokenForm.permissions" :value="$permission" />
                                <span class="ms-2 text-sm text-gray-600">{{ $permission }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="created">
                {{ __('api.created.') }}
            </x-action-message>

            <x-ts-button type="submit" color="primary">
                {{ __('api.create') }}
            </x-ts-button>
        </x-slot>
    </x-form-section>

    @if ($this->user->tokens->isNotEmpty())
        <x-section-border />

        {{-- manage api tokens --}}
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    <div class="dark:text-gray-400">
                        {{ __('api.manage_api_tokens') }}
                    </div>
                </x-slot>

                <x-slot name="description">
                    <div class="dark:text-gray-100">
                        {{ __('api.may_delete') }}
                    </div>
                </x-slot>

                {{-- api tokens list --}}
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($this->user->tokens->sortBy('name') as $token)
                            <div class="flex items-center justify-between">
                                <div class="break-all">
                                    {{ $token->name }}
                                </div>

                                <div class="flex items-center ms-2">
                                    @if ($token->last_used_at)
                                        <div class="text-sm text-gray-400">
                                            {{ __('api.last_used') }} {{ $token->last_used_at->diffForHumans() }}
                                        </div>
                                    @endif

                                    @if (Laravel\Jetstream\Jetstream::hasPermissions())
                                        <x-ts-button sm color="primary" class="min-w-28 ms-3 text-sm" wire:click="manageApiTokenPermissions({{ $token->id }})"
                                            title="{{ __('api.permissions_edit') }}">
                                            {{ __('api.permissions') }}
                                        </x-ts-button>
                                    @endif

                                    <x-ts-button sm color="danger" class="min-w-28 ms-3 text-sm" wire:click="confirmApiTokenDeletion({{ $token->id }})" title="{{ __('api.delete_api_token') }}">
                                        {{ __('api.delete') }}
                                    </x-ts-button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-action-section>
        </div>
    @endif

    {{-- token value modal --}}
    <x-dialog-modal wire:model.live="displayingToken">
        <x-slot name="title">
            {{ __('api.api_token') }}
        </x-slot>

        <x-slot name="content">
            <div>
                {{ __('api.please_copy') }}
            </div>

            <x-input x-ref="plaintextToken" type="text" readonly :value="$plainTextToken" class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full break-all" autofocus
                autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" @showing-token-modal.window="setTimeout(() => $refs.plaintextToken.select(), 250)" />
        </x-slot>

        <x-slot name="footer">
            <x-ts-button color="secondary" wire:click="$set('displayingToken', false)" wire:loading.attr="disabled">
                {{ __('api.close') }}
            </x-ts-button>
        </x-slot>
    </x-dialog-modal>

    {{-- api token permissions modal --}}
    <x-dialog-modal wire:model.live="managingApiTokenPermissions">
        <x-slot name="title">
            {{ __('api.api_token_permissions') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach (Laravel\Jetstream\Jetstream::$permissions as $permission)
                    <label class="flex items-center">
                        <x-checkbox wire:model="updateApiTokenForm.permissions" :value="$permission" />
                        <span class="ms-2 text-sm text-gray-600">{{ $permission }}</span>
                    </label>
                @endforeach
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-ts-button color="secondary" wire:click="$set('managingApiTokenPermissions', false)" wire:loading.attr="disabled">
                {{ __('api.cancel') }}
            </x-ts-button>

            <x-ts-button color="primary" class="ms-3" wire:click="updateApiToken" wire:loading.attr="disabled">
                {{ __('api.save') }}
            </x-ts-button>
        </x-slot>
    </x-dialog-modal>

    {{-- delete token confirmation modal --}}
    <x-confirmation-modal wire:model.live="confirmingApiTokenDeletion">
        <x-slot name="title">
            {{ __('api.delete_api_token') }}
        </x-slot>

        <x-slot name="content">
            {{ __('api.sure') }}
        </x-slot>

        <x-slot name="footer">
            <x-ts-button color="secondary" wire:click="$toggle('confirmingApiTokenDeletion')" wire:loading.attr="disabled">
                {{ __('api.cancel') }}
            </x-ts-button>

            <x-ts-button color="danger" class="ms-3" wire:click="deleteApiToken" wire:loading.attr="disabled">
                {{ __('api.delete') }}
            </x-ts-button>
        </x-slot>
    </x-confirmation-modal>
</div>
