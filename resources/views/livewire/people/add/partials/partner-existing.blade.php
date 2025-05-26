<div>
    <div class="p-4 md:w-192 bg-neutral-200">
        <x-ts-errors class="mb-2" close />

        <div class="col-span-6">
            {{-- person_id --}}
            <x-ts-select.styled wire:model="form.person_id" id="person_id" label="{{ __('person.person') }} : *" :options="$persons" select="label:name|value:id"
                placeholder="{{ __('app.select') }} ..." searchable>
                <x-slot:after>
                    <div class="w-full px-2 mb-2">
                        <x-ts-alert title="{{ __('app.nothing_available') }}" text="{{ __('person.use_tab') . ' : ' . __('person.add_new_person_as_child') }}" color="cyan" />
                    </div>
                </x-slot:after>
            </x-ts-select.styled>
        </div>
    </div>

    <div class="p-4 mt-4 md:w-192 bg-neutral-200">
        <div class="grid grid-cols-6 gap-5">
            {{-- date_start --}}
            <div class="col-span-3">
                <x-ts-date wire:model="date_start" id="date_start" label="{{ __('couple.date_start') }} :" format="YYYY-MM-DD"
                    :max-date="now()" placeholder="{{ __('app.select') }} ..." />
            </div>

            {{-- date_end --}}
            <div class="col-span-3">
                <x-ts-date wire:model="date_end" id="date_end" label="{{ __('couple.date_end') }} :" format="YYYY-MM-DD" :max-date="now()"
                    placeholder="{{ __('app.select') }} ..." />
            </div>

            {{-- is_married --}}
            <div class="col-span-3">
                <x-ts-toggle wire:model="is_married" name="is_married" id="is_married" label="{{ __('couple.is_married') }} ?" position="left" />
            </div>

            {{-- has_ended --}}
            <div class="col-span-3">
                <x-ts-toggle wire:model="has_ended" name="has_ended" id="has_ended" label="{{ __('couple.has_ended') }} ?" position="left" />
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end p-4">
        <x-ts-button type="submit" color="primary">
            {{ __('app.save') }}
        </x-ts-button>
    </div>
</div>
