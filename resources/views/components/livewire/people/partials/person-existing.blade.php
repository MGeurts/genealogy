<div>
    <div class="bg-neutral-200 p-4 md:w-3xl">
        <x-ts-errors class="mb-2" close />

        <div class="col-span-6">
            {{-- person_id --}}
            <x-ts-select.styled
                wire:model="form.person_id"
                id="person_id"
                label="{{ __('person.person') }} : *"
                :options="$persons"
                select="label:name|value:id"
                placeholder="{{ __('app.select') }} ..."
                searchable
            >
                <x-slot:after>
                    <div class="mb-2 w-full px-2">
                        <x-ts-alert
                            title="{{ __('app.nothing_available') }}"
                            text="{{ __('person.use_tab') . ' : ' . __('person.add_person') }}"
                            color="cyan"
                        />
                    </div>
                </x-slot:after>
            </x-ts-select.styled>
        </div>
    </div>

    <div class="flex items-center justify-end p-4">
        <x-ts-button type="submit" color="primary"> {{ __('app.save') }} </x-ts-button>
    </div>
</div>
