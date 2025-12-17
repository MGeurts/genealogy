<form wire:submit="saveContact">
    @csrf

    <div class="md:w-3xl flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="flex-1 grow max-w-full min-w-max">
                    {{ __('person.edit_contact') }}
                </div>

                <div class="flex-1 grow max-w-full min-w-max text-end">
                    <x-ts-icon icon="tabler.address-book" class="inline-block size-5" />
                </div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <x-ts-errors class="mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- street --}}
                <div class="col-span-4">
                    <x-ts-input wire:model="street" id="street" label="{{ __('person.street') }} :" autocomplete="street" autofocus />
                </div>

                {{-- number --}}
                <div class="col-span-2">
                    <x-ts-input wire:model="number" id="number" label="{{ __('person.number') }} :" autocomplete="number" />
                </div>

                {{-- postal_code --}}
                <div class="col-span-2">
                    <x-ts-input wire:model="postal_code" id="postal_code" label="{{ __('person.postal_code') }} :" autocomplete="postal_code"
                        />
                </div>

                {{-- city --}}
                <div class="col-span-4">
                    <x-ts-input wire:model="city" id="postal_code" label="{{ __('person.city') }} :" autocomplete="city" />
                </div>

                {{-- province --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="province" id="province" label="{{ __('person.province') }} :" autocomplete="province" />
                </div>

                {{-- state --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="state" id="state" label="{{ __('person.state') }} :" autocomplete="state" />
                </div>

                {{-- country --}}
                <div class="col-span-5">
                    <x-ts-select.styled wire:model="country" id="country" label="{{ __('person.country') }} :" :options="$this->countries()" select="label:name|value:id"
                        placeholder="{{ __('app.select') }} ..." searchable />
                </div>

                {{-- show on google maps button --}}
                <div class="h-4 col-span-1 pt-5 text-end">
                    @if ($person->address_google)
                        <x-ts-button href="{{ $person->address_google }}" target="_blank" color="cyan" class="p-2! text-white" title="{{ __('app.show_on_google_maps') }}">
                            <x-ts-icon icon="tabler.brand-google-maps" class="inline-block size-5" />
                        </x-ts-button>
                    @endif
                </div>
                <x-hr.narrow class="col-span-6 my-0!" />

                {{-- phone --}}
                <div class="col-span-6">
                    <x-ts-input wire:model="phone" id="phone" label="{{ __('person.phone') }} :" autocomplete="phone" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end p-4 rounded-b">
            <x-ts-button type="submit" color="primary">
                {{ __('app.save') }}
            </x-ts-button>
        </div>
    </div>
</form>
