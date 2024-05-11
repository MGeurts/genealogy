<form wire:submit="savePerson">
    @csrf

    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('person.add_person') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                    <x-ts-icon icon="user-plus" class="inline-block" />
                </div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <x-ts-errors class="mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- firstname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="personForm.firstname" id="firstname" label="{{ __('person.firstname') }}" wire:dirty.class="bg-warning-200 dark:text-black" autocomplete="firstname"
                        autofocus />
                </div>

                {{-- surname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="personForm.surname" id="surname" label="{{ __('person.surname') }}" wire:dirty.class="bg-warning-200 dark:text-black" autocomplete="surname" />
                </div>

                {{-- birthname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="personForm.birthname" id="birthname" label="{{ __('person.birthname') }}" wire:dirty.class="bg-warning-200 dark:text-black" autocomplete="birthname" />
                </div>

                {{-- nickname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="personForm.nickname" id="nickname" label="{{ __('person.nickname') }}" wire:dirty.class="bg-warning-200 dark:text-black" autocomplete="nickname" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- sex --}}
                <div class="col-span-6 md:col-span-3">
                    <x-label for="sex" class="mr-5" value="{{ __('person.sex') }} ({{ __('person.biological') }})" />
                    <div class="flex">
                        <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <x-ts-radio color="primary" wire:model="personForm.sex" name="sex" id="sexM" value="m" label="{{ __('app.male') }}" />
                        </div>
                        <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <x-ts-radio color="primary" wire:model="personForm.sex" name="sex" id="sexF" value="f" label="{{ __('app.female') }}" />
                        </div>
                    </div>
                </div>

                {{-- gender_id --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-select.styled wire:model="gender_id" id="gender_id" label="{{ __('person.gender') }}" :options="$personForm->genders()" select="label:name|value:id" placeholder="{{ __('app.select') }} ..."
                        wire:dirty.class="bg-warning-200 dark:text-black" searchable />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- yob --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="personForm.yob" id="yob" label="{{ __('person.yob') }}" wire:dirty.class="bg-warning-200 dark:text-black" autocomplete="yob" />
                </div>

                {{-- dob --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-date wire:model="personForm.dob" id="dob" label="{{ __('person.dob') }}" wire:dirty.class="bg-warning-200 dark:text-black" format="YYYY-MM-DD" :max-date="now()"
                        placeholder="{{ __('app.select') }} ..." />
                </div>

                {{-- pob --}}
                <div class="col-span-6">
                    <x-ts-input wire:model="personForm.pob" id="pob" label="{{ __('person.pob') }}" wire:dirty.class="bg-warning-200 dark:text-black" autocomplete="pod" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- images --}}
                <div class="col-span-6">
                    <x-ts-upload id="photos" wire:model="photos" label="{{ __('person.photos') }}" accept=".jpeg, .jpg, .gif, .png, .svg, .webp"
                        hint="Max: 1024 KB, Format: jpeg/jpg, gif, png,svg or webp" tip="{{ __('person.update_photo_tip') }} ..." multiple delete>
                    </x-ts-upload>
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
                <x-ts-button color="secondary" class="mr-1" wire:click="resetPerson()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-ts-button>

                <x-ts-button color="primary">
                    {{ __('app.save') }}
                </x-ts-button>
            </div>
        </div>
    </div>
</form>
