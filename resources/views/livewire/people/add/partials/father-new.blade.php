<div>
    <div class="p-4 md:w-192 bg-neutral-200">
        <x-ts-errors class="mb-2" close />

        <div class="grid grid-cols-6 gap-5">
            {{-- firstname --}}
            <div class="col-span-6 md:col-span-3">
                <x-ts-input wire:model="form.firstname" id="firstname" label="{{ __('person.firstname') }} :" autocomplete="firstname" />
            </div>

            {{-- surname --}}
            <div class="col-span-6 md:col-span-3">
                <x-ts-input wire:model="form.surname" id="surname" label="{{ __('person.surname') }} : *" autocomplete="surname" />
            </div>

            {{-- birthname --}}
            <div class="col-span-6 md:col-span-3">
                <x-ts-input wire:model="form.birthname" id="birthname" label="{{ __('person.birthname') }} :" autocomplete="birthname" />
            </div>

            {{-- nickname --}}
            <div class="col-span-6 md:col-span-3">
                <x-ts-input wire:model="form.nickname" id="nickname" label="{{ __('person.nickname') }}" autocomplete="nickname" />
            </div>
            <x-hr.narrow class="col-span-6 my-0!" />

            {{-- sex --}}
            <div class="col-span-6 md:col-span-3">
                <x-label for="sex" class="mr-5" value="{{ __('person.sex') }} ({{ __('person.biological') }}) :" />
                <div class="flex">
                    <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem] text-gray-700">
                        {{ __('app.male') }}
                    </div>
                </div>
            </div>

            {{-- gender_id --}}
            <div class="col-span-6 md:col-span-3">
                <x-ts-select.styled wire:model="form.gender_id" id="gender_id" label="{{ __('person.gender') }} :" :options="$form->genders()" select="label:name|value:id"
                    placeholder="{{ __('app.select') }} ..." searchable />
            </div>
            <x-hr.narrow class="col-span-6 my-0!" />

            {{-- yob --}}
            <div class="col-span-6 md:col-span-3">
                <x-ts-input wire:model="form.yob" id="yob" label="{{ __('person.yob') }} :" autocomplete="yob" type="number" min="1" max="{{ now()->year }}" />
            </div>

            {{-- dob --}}
            <div class="col-span-6 md:col-span-3">
                <x-ts-date wire:model="form.dob" id="dob" label="{{ __('person.dob') }} :" format="YYYY-MM-DD" :max-date="now()"
                    placeholder="{{ __('app.select') }} ..." />
            </div>

            {{-- pob --}}
            <div class="col-span-6">
                <x-ts-input wire:model="form.pob" id="pob" label="{{ __('person.pob') }} :" autocomplete="pod" />
            </div>
            <x-hr.narrow class="col-span-6 my-0!" />

            {{-- uploads --}}
            <div class="col-span-6">
                <x-ts-upload wire:model="form.uploads" id="uploads" label="{{ __('person.photos') }} :" accept="{{ implode(',', array_keys(config('app.upload_photo_accept'))) }}"
                    hint="{{ __('person.upload_max_size', ['max' => config('app.upload_max_size')]) }}<br/>{{ __('person.upload_accept_types', ['types' => implode(', ', array_values(config('app.upload_photo_accept')))]) }}"
                    tip="{{ __('person.upload_photos_tip') }}" multiple delete />
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end p-4">
        <x-ts-button type="submit" color="primary">
            {{ __('app.save') }}
        </x-ts-button>
    </div>
</div>
