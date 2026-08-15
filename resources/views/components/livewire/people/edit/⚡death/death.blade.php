<form wire:submit="saveDeath">
    @csrf

    <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] md:w-3xl dark:bg-neutral-700 dark:text-neutral-50">
        <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="max-w-full min-w-max flex-1 grow">{{ __('person.edit_death') }}</div>

                <div class="max-w-full min-w-max flex-1 grow text-end">
                    <x-ts-icon icon="tabler.grave-2" class="inline-block size-5" />
                </div>
            </div>
        </div>

        <div class="bg-neutral-200 p-4">
            <x-ts-errors class="mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- yod --}}
                <div class="col-span-3">
                    <x-ts-input
                        wire:model="yod"
                        id="yod"
                        label="{{ __('person.yod') }} :"
                        type="number"
                        max="{{ date('Y') }}"
                        autofocus
                    />
                </div>

                {{-- dod --}}
                <div class="col-span-3">
                    <x-ts-date
                        wire:model="dod"
                        id="dod"
                        label="{{ __('person.dod') }} :"
                        format="YYYY-MM-DD"
                        :max-date="now()"
                        placeholder="{{ __('app.select') }} ..."
                    />
                </div>

                {{-- pod --}}
                <div class="col-span-6">
                    <x-ts-input wire:model="pod" id="pod" label="{{ __('person.pod') }} :" />
                </div>
                <x-hr.narrow class="col-span-6 my-0!" />

                <div class="col-span-5 h-4">
                    <h4 class="text-lg font-medium text-neutral-800">{{ __('person.cemetery_location') }}</h4>
                </div>

                {{-- show on google maps button --}}
                <div class="col-span-1 h-4 text-end">
                    @if ($person->cemetery_google)
                        <a target="_blank" href="{{ $person->cemetery_google }}">
                            <x-ts-button
                                color="cyan"
                                class="mb-2 p-2! text-white"
                                title="{{ __('app.show_on_google_maps') }}"
                            >
                                <x-ts-icon icon="tabler.brand-google-maps" class="inline-block size-5" />
                            </x-ts-button>
                        </a>
                    @endif
                </div>

                {{-- cemetery_location_name --}}
                <div class="col-span-6">
                    <x-ts-input
                        wire:model="cemetery_location_name"
                        id="cemetery_location_name"
                        label="{{ __('metadata.location_name') }} :"
                    />
                </div>

                {{-- cemetery_location_address --}}
                <div class="col-span-6">
                    <x-ts-textarea
                        wire:model="cemetery_location_address"
                        id="cemetery_location_address"
                        label="{{ __('metadata.address') }} :"
                        resize-auto
                    />
                </div>

                {{-- cemetery_location_latitude --}}
                <div class="col-span-3">
                    <x-ts-input
                        wire:model="cemetery_location_latitude"
                        id="cemetery_location_latitude"
                        label="{{ __('metadata.latitude') }} :"
                    />
                </div>

                {{-- cemetery_location_longitude --}}
                <div class="col-span-3">
                    <x-ts-input
                        wire:model="cemetery_location_longitude"
                        id="cemetery_location_longitude"
                        label="{{ __('metadata.longitude') }} :"
                    />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end rounded-b p-4">
            <x-ts-button type="submit" color="primary"> {{ __('app.save') }} </x-ts-button>
        </div>
    </div>
</form>
