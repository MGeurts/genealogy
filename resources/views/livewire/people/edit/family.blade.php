<form wire:submit="saveFamily">
    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('person.edit_family') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end"></div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <div class="grid grid-cols-6 gap-5">
                {{-- father_id --}}
                <div class="col-span-6">
                    <x-label for="father_id" value="{{ __('person.father') }} ({{ __('person.biological') }})" />
                    <x-select.select class="bg-white" wire:model="familyForm.father_id" name="father_id" id="father_id" :options="$fathers" value-field='id' text-field='name'
                        placeholder="{{ __('app.select') }} ..." search-input-placeholder="{{ __('app.search') }} ..." :searchable="true" :clearable="true" wire:dirty.class="bg-warning-100"
                        no-options="{{ __('app.no_data') }}" no-result="{{ __('app.no_result') }}" class="form-select pl-0 py-0" />
                    <x-input-error for="father_id" class="mt-1" />
                </div>

                {{-- mother_id --}}
                <div class="col-span-6">
                    <x-label for="mother_id" value="{{ __('person.mother') }} ({{ __('person.biological') }})" />
                    <x-select.select class="bg-white" wire:model="familyForm.mother_id" name="mother_id" id="mother_id" :options="$mothers" value-field='id' text-field='name'
                        placeholder="{{ __('app.select') }} ..." search-input-placeholder="{{ __('app.search') }} ..." :searchable="true" :clearable="true" wire:dirty.class="bg-warning-100"
                        no-options="{{ __('app.no_data') }}" no-result="{{ __('app.no_result') }}" class="form-select pl-0 py-0" />
                    <x-input-error for="mother_id" class="mt-1" />
                </div>

                <div class="col-span-6 text-sm rounded bg-warning-200 p-3 text-warning-700" role="alert">
                    <b>{{ __('person.father') }}</b> and <b>{{ __('person.mother') }}</b> <u>may only be used</u> for the <b>biological parents</b> and must therefore be of opposite sex.
                    <x-hr.narrow class="col-span-6" />
                    <b>{{ __('person.parents') }}</b> <u>may be</u> the biological parents, but <u>may also be</u> used for non-biological parents (gay or adoptive).<br />
                    In the latter case, simply leave <b>{{ __('person.father') }}</b> and/or <b>{{ __('person.mother') }}</b> blank.
                </div>

                {{-- parents_id --}}
                <div class="col-span-6">
                    <x-label for="parents_id" value="{{ __('person.parents') }}" />
                    <x-select.select class="bg-white" wire:model="familyForm.parents_id" name="parents_id" id="parents_id" :options="$parents" value-field='id' text-field='couple'
                        placeholder="{{ __('app.select') }} ..." search-input-placeholder="{{ __('app.search') }} ..." :searchable="true" :clearable="true" wire:dirty.class="bg-warning-100"
                        no-options="{{ __('app.no_data') }}" no-result="{{ __('app.no_result') }}" class="form-select pl-0 py-0" />
                    <x-input-error for="parents_id" class="mt-1" />
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
                <x-button.secondary class="mr-2" wire:click="resetFamily()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-button.secondary>

                <x-button.primary>
                    {{ __('app.save') }}
                </x-button.primary>
            </div>
        </div>
    </div>
</form>
