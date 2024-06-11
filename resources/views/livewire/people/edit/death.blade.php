<form wire:submit="saveDeath">
    @csrf

    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('person.edit_death') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                    <x-ts-icon icon="grave-2" class="inline-block" />
                </div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <x-ts-errors class="mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- yod --}}
                <div class="col-span-3">
                    <x-ts-input wire:model="deathForm.yod" id="yod" label="{{ __('person.yod') }} :" wire:dirty.class="bg-warning-200 dark:text-black" autofocus />
                </div>

                {{-- dod --}}
                <div class="col-span-3">
                    <x-ts-date wire:model="deathForm.dod" id="dod" label="{{ __('person.dod') }} :" wire:dirty.class="bg-warning-200 dark:text-black" format="YYYY-MM-DD" :max-date="now()"
                        placeholder="{{ __('app.select') }} ..." />
                </div>

                {{-- pod --}}
                <div class="col-span-6">
                    <x-ts-input wire:model="deathForm.pod" id="pod" label="{{ __('person.pod') }} :" wire:dirty.class="bg-warning-200 dark:text-black" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                <div class="col-span-5 h-4">
                    <h4 class="text-lg font-medium text-neutral-800">{{ __('person.cemetery_location') }}</h4>
                </div>

                {{-- show on google maps button --}}
                <div class="col-span-1 h-4 text-end">
                    @if ($person->cemetery_google)
                        <a target="_blank" href="{{ $person->cemetery_google }}">
                            <x-ts-button color="info" class="!p-2 mb-2 text-white" title="{{ __('app.show_on_google_maps') }}">
                                <x-ts-icon icon="brand-google-maps" class="size-5" />
                            </x-ts-button>
                        </a>
                    @endif
                </div>

                {{-- cemetery_location_name --}}
                <div class="col-span-6">
                    <x-ts-input wire:model="deathForm.cemetery_location_name" id="cemetery_location_name" label="{{ __('metadata.location_name') }} :"
                        wire:dirty.class="bg-warning-200 dark:text-black" />
                </div>

                {{-- cemetery_location_address --}}
                <div class="col-span-6">
                    <x-ts-textarea wire:model="deathForm.cemetery_location_address" id="cemetery_location_address" label="{{ __('metadata.address') }} :"
                        wire:dirty.class="bg-warning-200 dark:text-black" resize-auto />
                </div>

                {{-- cemetery_location_latitude --}}
                <div class="col-span-3">
                    <x-ts-input wire:model="deathForm.cemetery_location_latitude" id="cemetery_location_latitude" label="{{ __('metadata.latitude') }} :"
                        wire:dirty.class="bg-warning-200 dark:text-black" />
                </div>

                {{-- cemetery_location_longitude --}}
                <div class="col-span-3">
                    <x-ts-input wire:model="deathForm.cemetery_location_longitude" id="cemetery_location_longitude" label="{{ __('metadata.longitude') }} :"
                        wire:dirty.class="bg-warning-200 dark:text-black" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6 rounded-b">
            <div class="flex-grow max-w-full flex-1 text-left">
                <x-action-message class="p-2.5 rounded bg-warning-200 text-warning-700" role="alert" on="" wire:dirty>
                    {{ __('app.unsaved_changes') }} ...
                </x-action-message>

                <x-action-message class="p-2.5 rounded bg-success-200 text-emerald-600" role="alert" on="saved">
                    {{ __('app.saved') }}
                </x-action-message>
            </div>

            <div class="flex-grow max-w-full flex-1 text-end">
                <x-ts-button color="secondary" class="mr-1" wire:click="resetDeath()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-ts-button>

                <x-ts-button type="submit" color="primary">
                    {{ __('app.save') }}
                </x-ts-button>
            </div>
        </div>
    </div>
</form>
