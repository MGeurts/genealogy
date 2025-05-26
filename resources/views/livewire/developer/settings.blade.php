<form wire:submit="saveSettings">
    @csrf

    <x-slot name="heading">
        {{ __('app.settings') }}
    </x-slot>

    <div class="p-2 w-full">
        <div class="md:w-192 flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="flex-1 grow max-w-full min-w-max">
                        {{ __('app.settings') }}
                    </div>

                    <div class="flex-1 grow max-w-full min-w-max text-end">
                        <x-ts-icon icon="tabler.settings" class="inline-block" />
                    </div>
                </div>
            </div>

            <div class="p-4 bg-neutral-200">
                <x-ts-errors class="mb-2" close />

                <div class="grid grid-cols-6 gap-5">
                    <div class="col-span-6">
                        <x-ts-toggle wire:model="settingsForm.logAllQueries" label="{{ __('settings.log_all_queries') }} ?" />
                    </div>

                    <x-hr.narrow class="col-span-6 my-0!" />

                    <div class="col-span-3">
                        <x-ts-toggle wire:model="settingsForm.logAllQueriesSlow" label="{{ __('settings.log_all_queries_slow') }} ?" />
                    </div>

                    <div class="col-span-3">
                        <x-ts-input type="number" min="1" wire:model="settingsForm.logAllQueriesSlowThreshold" label="{{ __('settings.log_all_queries_slow_threshold') }} :" />
                    </div>

                    <x-hr.narrow class="col-span-6 my-0!" />

                    <div class="col-span-6">
                        <x-ts-toggle wire:model="settingsForm.logAllQueriesNPlusOne" label="{{ __('settings.log_all_queries_nplusone') }} ?" />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end p-4 rounded-b">
                <x-ts-button type="submit" color="primary">
                    {{ __('app.save') }}
                </x-ts-button>
            </div>
        </div>
    </div>
</form>
