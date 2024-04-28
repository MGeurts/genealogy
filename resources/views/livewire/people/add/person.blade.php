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
            <x-ts-errors icon="exclamation-circle" class="inline-block mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- firstname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="personForm.firstname" id="firstname" label="{{ __('person.firstname') }}" wire:dirty.class="bg-warning-100 dark:text-black" autocomplete="firstname"
                        autofocus />
                </div>

                {{-- surname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="personForm.surname" id="surname" label="{{ __('person.surname') }}" wire:dirty.class="bg-warning-100 dark:text-black" autocomplete="surname" required />
                </div>

                {{-- birthname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="personForm.birthname" id="birthname" label="{{ __('person.birthname') }}" wire:dirty.class="bg-warning-100 dark:text-black" autocomplete="birthname" />
                </div>

                {{-- nickname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="personForm.nickname" id="nickname" label="{{ __('person.nickname') }}" wire:dirty.class="bg-warning-100 dark:text-black" autocomplete="nickname" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- sex --}}
                <div class="col-span-3">
                    <x-label for="sex" class="mr-5" value="{{ __('person.sex') }} ({{ __('person.biological') }})" />
                    <div class="flex">
                        {{-- <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <x-ts-radio color="primary" wire:model="profileForm.sex" name="sex" id="sexM" value="m" label="{{ __('app.male') }}" />
                        </div>
                        <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <x-ts-radio color="primary" wire:model="profileForm.sex" name="sex" id="sexF" value="f" label="{{ __('app.female') }}" />
                        </div> --}}

                        <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <input
                                class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                type="radio" name="sex" id="sexM" value="m" wire:model="personForm.sex" />
                            <label class="mt-px text-sm inline-block pl-[0.15rem] hover:cursor-pointer dark:text-neutral-700" for="sexM">
                                {{ __('app.male') }} <x-ts-icon icon="gender-male" class="size-5 inline-block" />
                            </label>
                        </div>

                        <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <input
                                class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                type="radio" name="sex" id="sexF" value="f" wire:model="personForm.sex" />
                            <label class="mt-px text-sm inline-block pl-[0.15rem] hover:cursor-pointer dark:text-neutral-700" for="sexF">
                                {{ __('app.female') }} <x-ts-icon icon="gender-female" class="size-5 inline-block" />
                            </label>
                        </div>
                    </div>
                </div>

                {{-- gender_id --}}
                <div class="col-span-3">
                    <x-ts-select.styled wire:model="gender_id" id="gender_id" label="{{ __('person.gender') }}" :options="$personForm->genders()" select="label:name|value:id" placeholder="{{ __('app.select') }} ..."
                        wire:dirty.class="bg-warning-100 dark:text-black" searchable />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- yob --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="personForm.yob" id="yob" label="{{ __('person.yob') }}" wire:dirty.class="bg-warning-100 dark:text-black" autocomplete="yob" />
                </div>

                {{-- dob --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-date wire:model="personForm.dob" id="dob" label="{{ __('person.dob') }}" wire:dirty.class="bg-warning-100 dark:text-black" format="YYYY-MM-DD" :max-date="now()"
                        placeholder="{{ __('app.select') }} ..." />
                </div>

                {{-- pob --}}
                <div class="col-span-6">
                    <x-ts-input wire:model="personForm.pob" id="pob" label="{{ __('person.pob') }}" wire:dirty.class="bg-warning-100 dark:text-black" autocomplete="pod" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- images --}}
                <div class="col-span-6">
                    <x-ts-upload id="photos" wire:model="photos" accept=".jpeg, .jpg, gif, .png, .svg, .webp"
                        hint="Format: <b>jpeg/jpg</b>, <b>gif</b>, <b>png</b> ,<b>svg</b> or <b>webp</b><br/>Max: <b>1024 KB</b>" tip="{{ __('person.update_photo_tip') }} ..." multiple delete>
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
