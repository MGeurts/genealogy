<form wire:submit="saveDeath">
    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('person.edit_death') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end"></div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <div class="grid grid-cols-6 gap-5">
                {{-- yod --}}
                <div class="col-span-3">
                    <x-label for="yod" value="{{ __('person.yod') }}" />
                    <x-input id="yod" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.yod" wire:dirty.class="bg-warning-100" autocomplete="yod" autofocus
                        x-init="$el.focus();" x-on:saved.window="$el.focus();" />
                    <x-input-error for="deathForm.yod" class="mt-1" />
                </div>

                {{-- dod --}}
                <div class="col-span-3">
                    <x-label for="dod" value="{{ __('person.dod') }}" />
                    <div wire:ignore>
                        <div class="relative mb-1" data-te-format="yyyy-mm-dd" data-te-show-format="true" data-te-disable-future="true" data-te-datepicker-init data-te-input-wrapper-init
                            data-te-inline="true">
                            <x-input id="dod" type="text" wire:model="deathForm.dod" wire:dirty.class="bg-warning-100"
                                class="mt-1 peer block min-h-[auto] w-full rounded border-0 px-3 leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-800 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                                placeholder="Select a date" />

                        </div>
                    </div>
                    <x-input-error for="deathForm.dod" class="mt-1" />
                </div>

                {{-- pod --}}
                <div class="col-span-6">
                    <x-label for="pod" value="{{ __('person.pod') }}" />
                    <x-input id="pod" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.pod" wire:dirty.class="bg-warning-100" autocomplete="pod" />
                    <x-input-error for="deathForm.pod" class="mt-1" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                <div class="col-span-5 h-4">
                    <h4 class="text-lg font-medium text-neutral-800">{{ __('person.cemetery_location') }}</h4>
                </div>
                {{-- show on google maps button --}}
                <div class="col-span-1 h-4 text-end">
                    @if ($person->cemetery_google)
                        <x-link target="_blank" href="{{ $person->cemetery_google }}">
                            <x-button.info class="!p-2" title="{{ __('app.show_on_google_maps') }}">
                                <x-icon.tabler icon="brand-google-maps" class="!size-4" />
                            </x-button.info>
                        </x-link>
                    @endif
                </div>


                {{-- cemetery_location_name --}}
                <div class="col-span-6">
                    <x-label for="cemetery_location_name" value="{{ __('metadata.location_name') }}" />
                    <x-input id="cemetery_location_name" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.cemetery_location_name" wire:dirty.class="bg-warning-100"
                        autocomplete="cemetery_location_name" />
                    <x-input-error for="deathForm.cemetery_location_name" class="mt-1" />
                </div>

                {{-- cemetery_location_address --}}
                <div class="col-span-6">
                    <x-label for="cemetery_location_address" value="{{ __('metadata.address') }}" />
                    <div class="relative" data-te-input-wrapper-init>
                        <textarea id="cemetery_location_address" wire:model="deathForm.cemetery_location_address" wire:dirty.class="bg-warning-100" rows="3"
                            class="peer block min-h-[auto] w-full rounded border-0 px-3 py-[0.32rem] leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-800 dark:placeholder:text-neutral-400 [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0">
                        </textarea>
                    </div>
                    <x-input-error for="deathForm.cemetery_location_address" class="mt-1" />
                </div>

                {{-- cemetery_location_latitude --}}
                <div class="col-span-3">
                    <x-label for="cemetery_location_latitude" value="{{ __('metadata.latitude') }}" />
                    <x-input id="cemetery_location_latitude" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.cemetery_location_latitude"
                        wire:dirty.class="bg-warning-100" autocomplete="cemetery_location_latitude" />
                    <x-input-error for="deathForm.cemetery_location_latitude" class="mt-1" />
                </div>

                {{-- cemetery_location_longitude --}}
                <div class="col-span-3">
                    <x-label for="cemetery_location_longitude" value="{{ __('metadata.longitude') }}" />
                    <x-input id="cemetery_location_longitude" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="deathForm.cemetery_location_longitude"
                        wire:dirty.class="bg-warning-100" autocomplete="cemetery_location_longitude" />
                    <x-input-error for="deathForm.cemetery_location_longitude" class="mt-1" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6 rounded-b">
            <div class="flex-grow max-w-full flex-1 text-left">
                <x-action-message class="p-2.5 rounded bg-warning-200 text-warning-700" role="alert" on="" wire:dirty>
                    {{ __('app.unsaved_changes') }} ...
                </x-action-message>

                <x-action-message class="p-2.5 rounded bg-success-200 text-success-700" role="alert" on="saved">
                    {{ __('app.saved') }}
                </x-action-message>
            </div>

            <div class="flex-grow max-w-full flex-1 text-end">
                <x-button.secondary class="mr-2" wire:click="resetDeath()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-button.secondary>

                <x-button.primary>
                    {{ __('app.save') }}
                </x-button.primary>
            </div>
        </div>
    </div>
</form>
