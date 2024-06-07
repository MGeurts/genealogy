<x-form-section submit="updatePassword">
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('user.update_password') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('user.update_password_secure') }}
        </div>
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="current_password" value="{{ __('user.current_password') }} :" />
            <x-input id="current_password" type="password" class="mt-1 block w-full" wire:model="state.current_password" autocomplete="current-password" />
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password" value="{{ __('user.new_password') }} :" />
            <x-input id="password" type="password" class="mt-1 block w-full" wire:model="state.password" autocomplete="new-password" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password_confirmation" value="{{ __('user.confirm_new_password') }} :" />
            <x-input id="password_confirmation" type="password" class="mt-1 block w-full" wire:model="state.password_confirmation" autocomplete="new-password" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="p-2.5 mr-3 rounded text-xs bg-success-200 text-emerald-600" role="alert" on="saved">
            {{ __('app.saved.') }}
        </x-action-message>

        <x-ts-button type="submit" color="primary">
            {{ __('app.save') }}
        </x-ts-button>
    </x-slot>
</x-form-section>
