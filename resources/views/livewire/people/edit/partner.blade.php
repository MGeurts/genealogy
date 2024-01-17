<form wire:submit="savePartner">
    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('person.edit_relationship') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end"></div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200 grid grid-cols-6 gap-5">
            <!-- person2_id -->
            <div class="col-span-6">
                <x-label for="person2_id" value="{{ __('person.partner') }}" />
                <x-simple-select class="bg-white" wire:model="partnerForm.person2_id" name="person2_id" id="person2_id" :options="$persons" value-field='id' text-field='name'
                    placeholder="{{ __('app.select') }} ..." search-input-placeholder="{{ __('app.search') }} ..." :searchable="true" :clearable="true" wire:dirty.class="bg-warning-100"
                    no-options="{{ __('app.no_data') }}" no-result="{{ __('app.no_result') }}" class="form-select pl-0 py-0" />
                <x-input-error for="partnerForm.person2_id" class="mt-1" />
            </div>

            <!-- date_start -->
            <div class="col-span-3">
                <x-label for="date_start" value="{{ __('couple.date_start') }}" />
                <div wire:ignore>
                    <div class="relative mb-1" data-te-format="yyyy-mm-dd" data-te-show-format="true" data-te-disable-future="true" data-te-datepicker-init data-te-input-wrapper-init
                        data-te-inline="true">
                        <x-input id="date_start" type="text" wire:model="partnerForm.date_start" wire:dirty.class="bg-warning-100"
                            class="mt-1 peer block min-h-[auto] w-full rounded border-0 px-3 leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-800 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            placeholder="Select a date" />
                    </div>
                </div>
                <x-input-error for="partnerForm.date_start" class="mt-1" />
            </div>

            <!-- date_end -->
            <div class="col-span-3">
                <x-label for="date_end" value="{{ __('couple.date_end') }}" />
                <div wire:ignore>
                    <div class="relative mb-1" data-te-format="yyyy-mm-dd" data-te-show-format="true" data-te-disable-future="true" data-te-datepicker-init data-te-input-wrapper-init
                        data-te-inline="true">
                        <x-input id="date_end" type="text" wire:model="partnerForm.date_end" wire:dirty.class="bg-warning-100"
                            class="mt-1 peer block min-h-[auto] w-full rounded border-0 px-3 leading-[1.6] outline-none transition-all duration-200 ease-linear focus:placeholder:opacity-100 peer-focus:text-primary data-[te-input-state-active]:placeholder:opacity-100 motion-reduce:transition-none dark:text-neutral-800 dark:placeholder:text-neutral-200 dark:peer-focus:text-primary [&:not([data-te-input-placeholder-active])]:placeholder:opacity-0"
                            placeholder="Select a date" />
                    </div>
                </div>
                <x-input-error for="partnerForm.date_end" class="mt-1" />
            </div>

            <!-- is_married -->
            <div class="col-span-3">
                <div class="flex items-center w-full">
                    <x-label for="is_married" class="flex items-center cursor-pointer">
                        <span class="mr-5">{{ __('couple.is_married') }} ?</span>

                        <input type="checkbox" name="is_married" id="is_married" wire:model="partnerForm.is_married" class="sr-only peer">
                        <div
                            class="block relative bg-gray-300 w-16 h-9 p-1 rounded-full before:absolute before:bg-white before:w-7 before:h-7 before:p-1 before:rounded-full before:transition-all before:duration-500 before:left-1 peer-checked:before:left-8 peer-checked:before:bg-primary">
                        </div>
                    </x-label>
                </div>
                <x-input-error for="partnerForm.is_married="mt-1" />
            </div>

            <!-- has_ended -->
            <div class="col-span-3">
                <div class="flex items-center w-full">
                    <x-label for="has_ended" class="flex items-center cursor-pointer">
                        <span class="mr-5">{{ __('couple.has_ended') }} ?</span>
                        <input type="checkbox" name="has_ended" id="has_ended" wire:model="partnerForm.has_ended" class="sr-only peer">
                        <div
                            class="block relative bg-gray-300 w-16 h-9 p-1 rounded-full before:absolute before:bg-white before:w-7 before:h-7 before:p-1 before:rounded-full before:transition-all before:duration-500 before:left-1 peer-checked:before:left-8 peer-checked:before:bg-primary">
                        </div>
                    </x-label>
                </div>
                <x-input-error for="partnerForm.has_ended" class="mt-1" />
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
                <x-button.secondary class="mr-2" wire:click="resetPartner()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-button.secondary>

                <x-button.primary>
                    {{ __('app.save') }}
                </x-button.primary>
            </div>
        </div>
    </div>
</form>
