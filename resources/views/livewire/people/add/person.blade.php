<form wire:submit="savePerson">
    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('person.add_person') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end"></div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <div class="grid grid-cols-6 gap-5">
                <!-- firstname -->
                <div class="col-span-6 md:col-span-3">
                    <x-label for="firstname" value="{{ __('person.firstname') }}" />
                    <x-input id="firstname" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="personForm.firstname" wire:dirty.class="bg-warning-100" autocomplete="firstname"
                        autofocus x-init="$el.focus();" x-on:saved.window="$el.focus();" />
                    <x-input-error for="personForm.firstname" class="mt-1" />
                </div>

                <!-- surname -->
                <div class="col-span-6 md:col-span-3">
                    <x-label for="surname" value="{{ __('person.surname') }}" />
                    <x-input id="surname" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="personForm.surname" wire:dirty.class="bg-warning-100" autocomplete="surname"
                        required />
                    <x-input-error for="personForm.surname" class="mt-1" />
                </div>

                <!-- birthname -->
                <div class="col-span-6 md:col-span-3">
                    <x-label for="birthname" value="{{ __('person.birthname') }}" />
                    <x-input id="birthname" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="personForm.birthname" wire:dirty.class="bg-warning-100"
                        autocomplete="birthname" />
                    <x-input-error for="personForm.birthname" class="mt-1" />
                </div>

                <!-- nickname -->
                <div class="col-span-6 md:col-span-3">
                    <x-label for="nickname" value="{{ __('person.nickname') }}" />
                    <x-input id="nickname" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="personForm.nickname" wire:dirty.class="bg-warning-100" autocomplete="nickname" />
                    <x-input-error for="personForm.nickname" class="mt-1" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                <!-- sex -->
                <div class="col-span-3">
                    <x-label for="sex" class="mr-5" value="{{ __('person.sex') }} ({{ __('person.biological') }})" />
                    <div class="flex">
                        <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <input
                                class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                type="radio" name="sex" id="sexM" value="m" wire:model="personForm.sex" />
                            <label class="mt-px text-sm inline-block pl-[0.15rem] hover:cursor-pointer dark:text-neutral-700" for="sexM">
                                {{ __('app.male') }} <x-icon.tabler icon="gender-male" />
                            </label>
                        </div>

                        <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <input
                                class="relative float-left -ml-[1.5rem] mr-1 mt-0.5 h-5 w-5 appearance-none rounded-full border-2 border-solid border-neutral-300 before:pointer-events-none before:absolute before:h-4 before:w-4 before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] after:absolute after:z-[1] after:block after:h-4 after:w-4 after:rounded-full after:content-[''] checked:border-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:left-1/2 checked:after:top-1/2 checked:after:h-[0.625rem] checked:after:w-[0.625rem] checked:after:rounded-full checked:after:border-primary checked:after:bg-primary checked:after:content-[''] checked:after:[transform:translate(-50%,-50%)] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:outline-none focus:ring-0 focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:border-primary checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] dark:border-neutral-600 dark:checked:border-primary dark:checked:after:border-primary dark:checked:after:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:border-primary dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca]"
                                type="radio" name="sex" id="sexF" value="f" wire:model="personForm.sex" />
                            <label class="mt-px text-sm inline-block pl-[0.15rem] hover:cursor-pointer dark:text-neutral-700" for="sexF">
                                {{ __('app.female') }} <x-icon.tabler icon="gender-female" />
                            </label>
                        </div>
                    </div>
                    <x-input-error for="personForm.sex" class="mt-1" />
                </div>

                <!-- gender_id -->
                <div class="col-span-3">
                    <x-label for="gender_id" value="{{ __('person.gender') }}" />
                    <x-simple-select class="bg-white" wire:dirty.class="bg-warning-100" wire:model="personForm.gender_id" name="gender_id" id="gender_id" :options="$personForm->genders()" value-field='id'
                        text-field='name' placeholder="{{ __('app.select') }} ..." search-input-placeholder="{{ __('app.search') }} ..." :searchable="true" :clearable="true"
                        wire:dirty.class="bg-warning-100" no-options="{{ __('app.no_data') }}" no-result="{{ __('app.no_result') }}" class="form-select pl-0 py-0" />
                    <x-input-error for="personForm.gender_id" class="mt-1" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                <!-- yob -->
                <div class="col-span-6 md:col-span-3">
                    <x-label for="yob" value="{{ __('person.yob') }}" />
                    <x-input id="yob" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="personForm.yob" wire:dirty.class="bg-warning-100" autocomplete="yob" />
                    <x-input-error for="personForm.yob" class="mt-1" />
                </div>

                <!-- dob -->
                <div class="col-span-6 md:col-span-3">
                    <x-label for="dob" value="{{ __('person.dob') }}" />
                    <div wire:ignore>
                        <div class="relative mb-3" data-te-format="yyyy-mm-dd" data-te-show-format="true" data-te-disable-future="true" data-te-datepicker-init data-te-input-wrapper-init
                            data-te-inline="true">
                            <x-input id="dob" type="text" wire:model="personForm.dob" wire:dirty.class="bg-warning-100"
                                class="mt-1 peer block min-h-[auto] w-full rounded border-0 px-3 leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-800 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                                placeholder="Select a date" />
                        </div>
                    </div>
                    <x-input-error for="personForm.dob" class="mt-1" />
                </div>

                <!-- pob -->
                <div class="col-span-6">
                    <x-label for="pob" value="{{ __('person.pob') }}" />
                    <x-input id="pob" type="text" class="mt-1 block w-full dark:text-neutral-800" wire:model="personForm.pob" wire:dirty.class="bg-warning-100" autocomplete="pod" />
                    <x-input-error for="personForm.pob" class="mt-1" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                <!-- image -->
                <div class="col-span-6">
                    <x-label for="image" value="{{ __('person.upload_photo') }}" />
                    <x-input type="file" id="image{{ $personForm->iteration }}" accept="image/webp, image/png, image/jpeg" wire:model="personForm.image"
                        class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal leading-[1.60] text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-800 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary" />

                    <span class="text-xs dark:text-neutral-800">Format: <b>jpeg/jpg</b>, <b>png</b> ,<b>svg</b> or <b>webp</b>, Max: <b>1024 Kb</b>. </span>

                    <x-input-error for="personForm.image" />

                    <div class="col-span-6">
                        <div wire:loading wire:target="image" role="status"
                            class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]">
                            <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                        </div>

                        @if ($personForm->image)
                            <img class="block mt-2 rounded w-36" src="{{ $personForm->image->temporaryUrl() }}" alt="image preview" />
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6 rounded-b">
            <div class="flex-grow max-w-full flex-1 text-left">
                <x-action-message class="p-2.5 rounded bg-warning-200 text-warning-700" role="alert" on="" wire:dirty>
                    {{ __('app.unsaved_changes') }} ...
                </x-action-message>

                <x-action-message class="p-2.5 rounded bg-success-200 text-success-700" role="alert" on="saved">
                    {{ __('app.saved') }}
                </x-action-message>
            </div>

            <div class="flex-grow max-w-full flex-1 text-end">
                <x-button.secondary class="mr-2" wire:click="resetPerson()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-button.secondary>

                <x-button.primary>
                    {{ __('app.save') }}
                </x-button.primary>
            </div>
        </div>
    </div>
</form>
