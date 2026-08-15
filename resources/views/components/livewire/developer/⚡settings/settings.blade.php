<form wire:submit="saveSettings">
    @csrf

    <div class="w-full p-2">
        <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] md:w-3xl dark:bg-neutral-700 dark:text-neutral-50">
            <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="max-w-full min-w-max flex-1 grow">{{ __('app.settings') }}</div>

                    <div class="max-w-full min-w-max flex-1 grow text-end">
                        <x-ts-icon icon="tabler.settings" class="inline-block size-5" />
                    </div>
                </div>
            </div>

            <div class="bg-neutral-200 p-4">
                <x-ts-errors class="mb-2" close />

                <div class="grid grid-cols-6 gap-5">
                    <div class="col-span-6">
                        <x-ts-toggle
                            wire:model="settingsForm.logAllQueries"
                            label="{{ __('settings.log_all_queries') }} ?"
                        />
                    </div>

                    <x-hr.narrow class="col-span-6 my-0!" />

                    <div class="col-span-3">
                        <x-ts-toggle
                            wire:model="settingsForm.logAllQueriesSlow"
                            label="{{ __('settings.log_all_queries_slow') }} ?"
                        />
                    </div>

                    <div class="col-span-3">
                        <x-ts-input
                            type="number"
                            min="1"
                            wire:model="settingsForm.logAllQueriesSlowThreshold"
                            label="{{ __('settings.log_all_queries_slow_threshold') }} :"
                        />
                    </div>

                    <x-hr.narrow class="col-span-6 my-0!" />

                    <div class="col-span-6">
                        <x-ts-toggle
                            wire:model="settingsForm.logAllQueriesNPlusOne"
                            label="{{ __('settings.log_all_queries_nplusone') }} ?"
                        />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end rounded-b p-4">
                <x-ts-button type="submit" color="primary"> {{ __('app.save') }} </x-ts-button>
            </div>
        </div>
    </div>
</form>
