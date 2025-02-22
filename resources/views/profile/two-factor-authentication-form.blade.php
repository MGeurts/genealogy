<x-action-section>
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('user.2fa') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('user.2fa_add') }}
        </div>
    </x-slot>

    <x-slot name="content">
        <h3 class="text-lg font-medium text-gray-900">
            @if ($this->enabled)
                @if ($showingConfirmation)
                    {{ __('user.2fa_finish') }}
                @else
                    {{ __('user.2fa_enabled') }}
                @endif
            @else
                {{ __('user.2fa_not_enabled') }}
            @endif
        </h3>

        <div class="max-w-xl mt-3 text-sm text-gray-600">
            <p>
                {{ __('user.2fa_message') }}
            </p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="max-w-xl mt-4 text-sm text-gray-600">
                    <p class="font-semibold">
                        @if ($showingConfirmation)
                            {{ __('user.2fa_to_finish') }}
                        @else
                            {{ __('user.2fa_enabled_scan') }}
                        @endif
                    </p>
                </div>

                <div class="inline-block p-2 mt-4 bg-white">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="max-w-xl mt-4 text-sm text-gray-600">
                    <p class="font-semibold">
                        {{ __('user.2fa_setup_key') }}: {{ decrypt($this->user->two_factor_secret) }}
                    </p>
                </div>

                @if ($showingConfirmation)
                    <div class="mt-4">
                        <x-label for="code" value="{{ __('user.2fa_code') }} :" />

                        <x-input id="code" type="text" name="code" class="block w-1/2 mt-1" inputmode="numeric" autofocus autocomplete="one-time-code" wire:model="code"
                            wire:keydown.enter="confirmTwoFactorAuthentication" />

                        <x-input-error for="code" class="mt-2" />
                    </div>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div class="max-w-xl mt-4 text-sm text-gray-600">
                    <p class="font-semibold">
                        {{ __('user.2fa_store_codes') }}
                    </p>
                </div>

                <div class="grid max-w-xl gap-1 p-4 mt-4 font-mono text-sm bg-gray-100 rounded-sm">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-5">
            @if (!$this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-ts-button color="primary" type="button" wire:loading.attr="disabled">
                        {{ __('user.2fa_enable') }}
                    </x-ts-button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes">
                        <x-ts-button color="secondary" class="me-3">
                            {{ __('user.2fa_regenerate') }}
                        </x-ts-button>
                    </x-confirms-password>
                @elseif ($showingConfirmation)
                    <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                        <x-ts-button color="primary" type="button" class="me-3" wire:loading.attr="disabled">
                            {{ __('user.2fa_confirm') }}
                        </x-ts-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes">
                        <x-ts-button color="secondary" class="me-3">
                            {{ __('user.2fa_show') }}
                        </x-ts-button>
                    </x-confirms-password>
                @endif

                @if ($showingConfirmation)
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-ts-button color="secondary" wire:loading.attr="disabled">
                            {{ __('user.cancel') }}
                        </x-ts-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="disableTwoFactorAuthentication">
                        <x-ts-button color="red" wire:loading.attr="disabled">
                            {{ __('user.2fa_disable') }}
                        </x-ts-button>
                    </x-confirms-password>
                @endif

            @endif
        </div>
    </x-slot>
</x-action-section>
