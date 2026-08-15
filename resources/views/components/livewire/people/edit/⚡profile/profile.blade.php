<form wire:submit="saveProfile">
    @csrf

    <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] md:w-3xl dark:bg-neutral-700 dark:text-neutral-50">
        <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="max-w-full min-w-max flex-1 grow">{{ __('person.edit_profile') }}</div>

                <div class="max-w-full min-w-max flex-1 grow text-end">
                    <x-ts-icon icon="tabler.id" class="inline-block size-5" />
                </div>
            </div>
        </div>

        <div class="bg-neutral-200 p-4">
            <x-ts-errors class="mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- firstname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input
                        wire:model="firstname"
                        id="firstname"
                        label="{{ __('person.firstname') }} :"
                        autofocus
                    />
                </div>

                {{-- surname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="surname" id="surname" label="{{ __('person.surname') }} : *" required />
                </div>

                {{-- birthname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="birthname" id="birthname" label="{{ __('person.birthname') }} :" />
                </div>

                {{-- nickname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="nickname" id="nickname" label="{{ __('person.nickname') }} :" />
                </div>
                <x-hr.narrow class="col-span-6 my-0!" />

                {{-- sex --}}
                <div class="col-span-3">
                    <x-label
                        for="sex"
                        class="mr-5"
                        value="{{ __('person.sex') }} ({{ __('person.biological') }}) : *"
                    />
                    <div class="flex">
                        <div class="mt-3 mr-4 mb-[0.125rem] inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <x-ts-radio
                                color="primary"
                                wire:model="sex"
                                name="sex"
                                id="sexM"
                                value="m"
                                label="{{ __('app.male') }}"
                            />
                        </div>
                        <div class="mt-3 mr-4 mb-[0.125rem] inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <x-ts-radio
                                color="primary"
                                wire:model="sex"
                                name="sex"
                                id="sexF"
                                value="f"
                                label="{{ __('app.female') }}"
                            />
                        </div>
                    </div>
                </div>

                {{-- gender_id --}}
                <div class="col-span-3">
                    <x-ts-select.styled
                        wire:model="gender_id"
                        id="gender_id"
                        label="{{ __('person.gender') }} :"
                        :options="$this->genders()"
                        select="label:name|value:id"
                        placeholder="{{ __('app.select') }} ..."
                        searchable
                    />
                </div>
                <x-hr.narrow class="col-span-6 my-0!" />

                {{-- yob --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input
                        wire:model="yob"
                        id="yob"
                        label="{{ __('person.yob') }} :"
                        autocomplete="yob"
                        type="number"
                        max="{{ date('Y') }}"
                    />
                </div>

                {{-- dob --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-date
                        wire:model="dob"
                        id="dob"
                        name="dob"
                        label="{{ __('person.dob') }} :"
                        format="YYYY-MM-DD"
                        :max-date="now()"
                        placeholder="{{ __('app.select') }} ..."
                    />
                </div>

                {{-- pob --}}
                <div class="col-span-6">
                    <x-ts-input wire:model="pob" id="pob" label="{{ __('person.pob') }} :" autocomplete="pob" />
                </div>
                <x-hr.narrow class="col-span-6 my-0!" />

                {{-- summary --}}
                <div class="col-span-6">
                    <x-ts-textarea
                        wire:model="summary"
                        id="summary"
                        label="{{ __('person.summary') }} :"
                        autocomplete="summary"
                        maxlength="65535"
                        count
                    />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end rounded-b p-4">
            <x-ts-button type="submit" color="primary"> {{ __('app.save') }} </x-ts-button>
        </div>
    </div>
</form>
