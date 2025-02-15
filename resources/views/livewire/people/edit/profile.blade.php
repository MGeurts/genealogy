<form wire:submit="saveProfile">
    @csrf

    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="flex-1 flex-grow max-w-full min-w-max">
                    {{ __('person.edit_profile') }}
                </div>

                <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                    <x-ts-icon icon="tabler.id" class="inline-block" />
                </div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <x-ts-errors class="mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- firstname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="profileForm.firstname" id="firstname" label="{{ __('person.firstname') }} :" wire:dirty.class="bg-warning-200 dark:text-black" required autofocus />
                </div>

                {{-- surname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="profileForm.surname" id="surname" label="{{ __('person.surname') }} : *" wire:dirty.class="bg-warning-200 dark:text-black" required />
                </div>

                {{-- birthname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="profileForm.birthname" id="birthname" label="{{ __('person.birthname') }} :" wire:dirty.class="bg-warning-200 dark:text-black" />
                </div>

                {{-- nickname --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="profileForm.nickname" id="nickname" label="{{ __('person.nickname') }} :" wire:dirty.class="bg-warning-200 dark:text-black" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- sex --}}
                <div class="col-span-3">
                    <x-label for="sex" class="mr-5" value="{{ __('person.sex') }} ({{ __('person.biological') }}) : *" />
                    <div class="flex">
                        <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <x-ts-radio color="primary" wire:model="profileForm.sex" name="sex" id="sexM" value="m" label="{{ __('app.male') }}" />
                        </div>
                        <div class="mt-3 mb-[0.125rem] mr-4 inline-block min-h-[1.5rem] pl-[1.5rem]">
                            <x-ts-radio color="primary" wire:model="profileForm.sex" name="sex" id="sexF" value="f" label="{{ __('app.female') }}" />
                        </div>
                    </div>
                </div>

                {{-- gender_id --}}
                <div class="col-span-3">
                    <x-ts-select.styled wire:model="profileForm.gender_id" id="gender_id" label="{{ __('person.gender') }} :" :options="$profileForm->genders()" select="label:name|value:id"
                        placeholder="{{ __('app.select') }} ..." wire:dirty.class="bg-warning-200 dark:text-black" searchable />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- yob --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-input wire:model="profileForm.yob" id="yob" label="{{ __('person.yob') }} :" wire:dirty.class="bg-warning-200 dark:text-black" />
                </div>

                {{-- dob --}}
                <div class="col-span-6 md:col-span-3">
                    <x-ts-date wire:model="profileForm.dob" id="dob" name="dob" label="{{ __('person.dob') }} :" wire:dirty.class="bg-warning-200 dark:text-black" format="YYYY-MM-DD"
                        :max-date="now()" placeholder="{{ __('app.select') }} ..." />
                </div>

                {{-- pob --}}
                <div class="col-span-6">
                    <x-ts-input wire:model="profileForm.pob" id="pob" label="{{ __('person.pob') }} :" wire:dirty.class="bg-warning-200 dark:text-black" autocomplete="pob" />
                </div>
                <x-hr.narrow class="col-span-6 !my-0" />

                {{-- summary --}}
                <div class="col-span-6">
                    <x-ts-textarea wire:model="profileForm.summary" id="summary" label="{{ __('person.summary') }} :" wire:dirty.class="bg-warning-200 dark:text-black" autocomplete="summary"
                        maxlength="65535" count />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end p-4 text-right rounded-b sm:px-6">
            <div class="flex-1 flex-grow max-w-full text-left">
                <x-action-message class="p-3 rounded bg-warning-200 text-warning-700" role="alert" on="" wire:dirty>
                    {{ __('app.unsaved_changes') }} ...
                </x-action-message>

                <x-action-message class="p-3 rounded bg-success-200 text-emerald-600" role="alert" on="saved">
                    {{ __('app.saved') }}
                </x-action-message>
            </div>

            <div class="flex-1 flex-grow max-w-full text-end">
                <x-ts-button color="secondary" class="mr-1" wire:click="resetProfile()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-ts-button>

                <x-ts-button type="submit" color="primary">
                    {{ __('app.save') }}
                </x-ts-button>
            </div>
        </div>
    </div>
</form>
