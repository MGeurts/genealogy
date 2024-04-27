<form id="form" wire:submit="saveContact">
    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('person.edit_contact') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                    <x-ts-icon icon="address-book" class="inline-block" />
                </div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <x-ts-errors class="mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- street --}}
                <div class="col-span-4">
                    <x-ts-input wire:model="contactForm.street" id="street" label="{{ __('person.street') }}" autocomplete="street" wire:dirty.class="bg-warning-100 dark:text-black" autofocus />
                </div>

                {{-- number --}}
                <div class="col-span-2">
                    <x-ts-input wire:model="contactForm.number" id="number" label="{{ __('person.number') }}" autocomplete="number" wire:dirty.class="bg-warning-100 dark:text-black" />
                </div>

                {{-- postal_code --}}
                <div class="col-span-2">
                    <x-ts-input wire:model="contactForm.postal_code" id="postal_code" label="{{ __('person.postal_code') }}" autocomplete="postal_code"
                        wire:dirty.class="bg-warning-100 dark:text-black" />
                </div>

                {{-- city --}}
                <div class="col-span-4">
                    <x-ts-input wire:model="contactForm.city" id="postal_code" label="{{ __('person.city') }}" autocomplete="city" wire:dirty.class="bg-warning-100 dark:text-black" />
                </div>

                {{-- province --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="contactForm.province" id="province" label="{{ __('person.province') }}" autocomplete="province" wire:dirty.class="bg-warning-100 dark:text-black" />
                </div>

                {{-- state --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="contactForm.state" id="province" label="{{ __('person.state') }}" autocomplete="state" wire:dirty.class="bg-warning-100 dark:text-black" />
                </div>

                {{-- country_id --}}
                <div class="col-span-5">
                    <x-ts-select.styled wire:model="contactForm.country_id" id="country_id" label="{{ __('person.country') }}" :options="$contactForm->countries()" select="label:name|value:id"
                        placeholder="{{ __('app.select') }} ..." wire:dirty.class="bg-warning-100 dark:text-black" searchable />
                </div>

                {{-- show on google maps button --}}
                <div class="col-span-1 pt-5 h-4 text-end">
                    @if ($person->address_google)
                        <x-ts-button href="{{ $person->address_google }}" target="_blank" color="info" class="!p-2 text-white" title="{{ __('app.show_on_google_maps') }}">
                            <x-ts-icon icon="brand-google-maps" class="size-5" />
                        </x-ts-button>
                    @endif
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- phone --}}
                <div class="col-span-6">
                    <x-ts-input wire:model="contactForm.phone" id="phone" label="{{ __('person.phone') }}" autocomplete="phone" wire:dirty.class="bg-warning-100 dark:text-black" />
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
                <x-ts-button color="secondary" class="mr-1" wire:click="resetContact()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-ts-button>

                <x-ts-button color="primary">
                    {{ __('app.save') }}
                </x-ts-button>
            </div>
        </div>
    </div>
</form>
