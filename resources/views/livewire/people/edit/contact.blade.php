<form wire:submit="saveContact">
    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('person.edit_contact') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end"></div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <div class="grid grid-cols-6 gap-5">
                {{-- street --}}
                <div class="col-span-4">
                    <x-label for="street" value="{{ __('person.street') }}" />
                    <x-input id="street" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="contactForm.street" wire:dirty.class="bg-warning-100" autocomplete="street"
                        autofocus x-init="$el.focus();" x-on:saved.window="$el.focus();" />
                    <x-input-error for="contactForm.street" class="mt-1" />
                </div>

                {{-- number --}}
                <div class="col-span-2">
                    <x-label for="number" value="{{ __('person.number') }}" />
                    <x-input id="number" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="contactForm.number" wire:dirty.class="bg-warning-100" autocomplete="number" />
                    <x-input-error for="contactForm.number" class="mt-1" />
                </div>

                {{-- postal_code --}}
                <div class="col-span-2">
                    <x-label for="postal_code" value="{{ __('person.postal_code') }}" />
                    <x-input id="postal_code" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="contactForm.postal_code" wire:dirty.class="bg-warning-100"
                        autocomplete="postal_code" />
                    <x-input-error for="contactForm.postal_code" class="mt-1" />
                </div>

                {{-- city --}}
                <div class="col-span-4">
                    <x-label for="city" value="{{ __('person.city') }}" />
                    <x-input id="city" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="contactForm.city" wire:dirty.class="bg-warning-100" autocomplete="city" />
                    <x-input-error for="contactForm.city" class="mt-1" />
                </div>

                {{-- province --}}
                <div class="col-span-6 md:col-span-3">
                    <x-label for="province" value="{{ __('person.province') }}" />
                    <x-input id="province" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="contactForm.province" wire:dirty.class="bg-warning-100"
                        autocomplete="province" />
                    <x-input-error for="contactForm.province" class="mt-1" />
                </div>

                {{-- state --}}
                <div class="col-span-6 md:col-span-3">
                    <x-label for="state" value="{{ __('person.state') }}" />
                    <x-input id="state" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="contactForm.state" wire:dirty.class="bg-warning-100" autocomplete="state" />
                    <x-input-error for="contactForm.state" class="mt-1" />
                </div>

                {{-- country_id --}}
                <div class="col-span-5">
                    <x-label for="country_id" value="{{ __('person.country') }}" />
                    <x-select.select class="bg-white" wire:model="contactForm.country_id" name="country_id" id="country_id" :options="$contactForm->countries()" value-field='id' text-field='name'
                        placeholder="{{ __('app.select') }} ..." search-input-placeholder="{{ __('app.search') }} ..." :searchable="true" :clearable="true" wire:dirty.class="bg-warning-100"
                        no-options="{{ __('app.no_data') }}" no-result="{{ __('app.no_result') }}" class="form-select pl-0 py-0" />
                    <x-input-error for="contactForm.country_id" class="mt-1" />
                </div>

                {{-- show on google maps button --}}
                <div class="col-span-1 pt-5 h-4 text-end">
                    @if ($person->address_google)
                        <a target="_blank" href="{{ $person->address_google }}">
                            <x-button.info class="!p-2" title="{{ __('app.show_on_google_maps') }}">
                                <x-icon.tabler icon="brand-google-maps" class="!size-4" />
                            </x-button.info>
                        </a>
                    @endif
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- phone --}}
                <div class="col-span-6">
                    <x-label for="phone" value="{{ __('person.phone') }}" />
                    <x-input id="phone" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="contactForm.phone" wire:dirty.class="bg-warning-100"
                        autocomplete="phone" />
                    <x-input-error for="contactForm.phone" class="mt-1" />
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
                <x-button.secondary class="mr-2" wire:click="resetContact()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-button.secondary>

                <x-button.primary>
                    {{ __('app.save') }}
                </x-button.primary>
            </div>
        </div>
    </div>
</form>
