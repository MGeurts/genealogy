<div>
    <div class="bg-neutral-200 p-4 md:w-3xl">
        <x-ts-errors class="mb-2" close />

        <div class="col-span-6">
            {{-- person_id --}}
            <x-ts-select.styled
                wire:model="form.person_id"
                id="person_id"
                label="{{ __('person.person') }} : *"
                :request="route('people.relationship-candidates', ['person' => $person, 'relationship' => $relationship])"
                select="label:name|value:id"
                placeholder="{{ __('app.select') }} ..."
                indicator="spinner"
            >
                <x-slot:after>
                    <div x-cloak x-show="search.length < 2" class="mb-2 w-full px-2">
                        <x-ts-alert
                            title="{{ __('person.search_similar') }}"
                            text="{{ __('person.search_relationship_candidates_hint') }}"
                            color="cyan"
                        />
                    </div>

                    <div x-cloak x-show="search.length >= 2" class="mb-2 w-full px-2">
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
