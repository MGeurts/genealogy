<form wire:submit="saveSettings">
    @csrf

    <x-slot name="heading">
        {{ __('app.settings') }}
    </x-slot>

    <div class="py-5 w-full">
        <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="flex-1 flex-grow max-w-full min-w-max">
                        {{ __('app.settings') }}
                    </div>

                    <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                        <x-ts-icon icon="settings" class="inline-block" />
                    </div>
                </div>
            </div>

            <div class="p-4 bg-neutral-200">
                <x-ts-errors class="mb-2" close />

                <div class="grid grid-cols-6 gap-5">
                    <div class="col-span-6">
                        <x-ts-toggle wire:model="settingsForm.logAllQueries" label="{{ __('settings.log_all_queries') }} ?" />
                    </div>

                    <x-hr.narrow class="col-span-6 !my-0" />

                    <div class="col-span-3">
                        <x-ts-toggle wire:model="settingsForm.logAllQueriesSlow" label="{{ __('settings.log_all_queries_slow') }} ?" />
                    </div>

                    <div class="col-span-3">
                        <x-ts-input type="number" min="1" wire:model="settingsForm.logAllQueriesSlowThreshold" label="{{ __('settings.log_all_queries_slow_threshold') }} :" />
                    </div>

                    <x-hr.narrow class="col-span-6 !my-0" />

                    <div class="col-span-6">
                        <x-ts-toggle wire:model="settingsForm.logAllQueriesNPlusOne" label="{{ __('settings.log_all_queries_nplusone') }} ?" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end px-4 py-3 text-right rounded-b sm:px-6">
                <div class="flex-1 flex-grow max-w-full text-left">
                    <x-action-message class="p-3 rounded bg-warning-200 text-warning-700" role="alert" on="" wire:dirty>
                        {{ __('app.unsaved_changes') }} ...
                    </x-action-message>

                    <x-action-message class="p-3 rounded bg-success-200 text-emerald-600" role="alert" on="saved">
                        {{ __('app.saved') }}
                    </x-action-message>
                </div>

                <div class="flex-1 flex-grow max-w-full text-end">
                    <x-ts-button color="secondary" class="mr-1" wire:click="resetSettings()" wire:dirty>
                        {{ __('app.cancel') }}
                    </x-ts-button>

                    <x-ts-button type="submit" color="primary">
                        {{ __('app.save') }}
                    </x-ts-button>
                </div>
            </div>
        </div>
    </div>
</form>
