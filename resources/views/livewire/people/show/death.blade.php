<form>
    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('app.death') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end"></div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200 rounded-b">
            <div class="grid grid-cols-6 gap-5">
                <!-- yod -->
                <div class="col-span-3">
                    <x-label for="yod" value="{{ __('person.yod') }}" />
                    <x-input-readonly id="yod" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.yod" />
                </div>

                <!-- dod -->
                <div class="col-span-3">
                    <x-label for="dod" value="{{ __('person.dod') }}" />
                    <x-input-readonly id="dod" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.dod" />
                </div>

                <!-- pod -->
                <div class="col-span-6">
                    <x-label for="pod" value="{{ __('person.pod') }}" />
                    <x-input-readonly id="pod" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.pod" readonly />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                <div class="col-span-5 h-4">
                    <h4 class="text-lg font-medium text-neutral-800">{{ __('person.cemetery_location') }}</h4>
                </div>
                <!-- show on Google Maps button -->
                <div class="col-span-1 h-4 text-end">
                    @if ($person->cemetery_google)
                        <a target="_blank" href="{{ $person->cemetery_google }}">
                            <x-button.info class="!p-2" title="{{ __('app.show_on_google_maps') }}">
                                <x-icon.tabler icon="brand-google-maps" class="!size-4" />
                            </x-button.info>
                        </a>
                    @endif
                </div>

                <!-- cemetery_location_name -->
                <div class="col-span-6">
                    <x-label for="cemetery_location_name" value="{{ __('metadata.location_name') }}" />
                    <x-input-readonly id="cemetery_location_name" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.cemetery_location_name" readonly />
                </div>

                <!-- cemetery_location_address -->
                <div class="col-span-6">
                    <x-label for="cemetery_location_address" value="{{ __('metadata.address') }}" />
                    <div class="relative" data-te-input-wrapper-init>
                        <textarea id="cemetery_location_address" wire:model="deathForm.cemetery_location_address" rows="3" disabled readonly
                            class="peer block min-h-[auto] w-full rounded border-0 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-800 dark:placeholder:text-neutral-400 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0">
                        </textarea>
                    </div>
                </div>

                <!-- cemetery_location_latitude -->
                <div class="col-span-3">
                    <x-label for="cemetery_location_latitude" value="{{ __('metadata.latitude') }}" />
                    <x-input-readonly id="cemetery_location_latitude" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.cemetery_location_latitude" readonly />
                </div>

                <!-- cemetery_location_longitude -->
                <div class="col-span-3">
                    <x-label for="cemetery_location_longitude" value="{{ __('metadata.longitude') }}" />
                    <x-input-readonly id="cemetery_location_longitude" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.cemetery_location_longitude" readonly />
                </div>
            </div>
        </div>
    </div>
</form>
