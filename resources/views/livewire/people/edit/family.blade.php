<form wire:submit="saveFamily">
    @csrf

    <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="flex-1 flex-grow max-w-full min-w-max">
                    {{ __('person.edit_family') }}
                </div>

                <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                    <x-ts-icon icon="edit" class="inline-block" />
                </div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <x-ts-errors class="mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- father_id --}}
                <div class="col-span-6">
                    <x-ts-select.styled wire:model="familyForm.father_id" id="father_id" label="{{ __('person.father') }} ({{ __('person.biological') }}) :" :options="$fathers"
                        select="label:name|value:id" placeholder="{{ __('app.select') }} ..." wire:dirty.class="bg-warning-200 dark:text-black" searchable />
                </div>

                {{-- mother_id --}}
                <div class="col-span-6">
                    <x-ts-select.styled wire:model="familyForm.mother_id" id="mother_id" label="{{ __('person.mother') }} ({{ __('person.biological') }}) :" :options="$mothers"
                        select="label:name|value:id" placeholder="{{ __('app.select') }} ..." wire:dirty.class="bg-warning-200 dark:text-black" searchable />
                </div>

                <div class="col-span-6 p-3 text-sm rounded bg-warning-200 text-warning-700" role="alert">
                    <b>{{ __('person.father') }}</b> and <b>{{ __('person.mother') }}</b> <u>may only be used</u> for the <b>biological parents</b> and must therefore be of opposite sex.
                    <x-hr.narrow class="col-span-6" />
                    <b>{{ __('person.parents') }}</b> <u>may be</u> the biological parents, but <u>may also be</u> used for non-biological parents (gay or adoptive).<br />
                    In the latter case, simply leave <b>{{ __('person.father') }}</b> and/or <b>{{ __('person.mother') }}</b> blank.
                </div>

                {{-- parents_id --}}
                <div class="col-span-6">
                    <x-ts-select.styled wire:model="familyForm.parents_id" id="parents_id" label="{{ __('person.parents') }} :" :options="$parents" select="label:couple|value:id"
                        placeholder="{{ __('app.select') }} ..." wire:dirty.class="bg-warning-200 dark:text-black" searchable />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end px-4 py-3 text-right rounded-b sm:px-6">
            <div class="flex-1 flex-grow max-w-full text-left">
                <x-action-message class="p-3 rounded bg-warning-200 text-warning-700" role="alert" on="" wire:dirty>
                    {{ __('app.unsaved_changes') }} ...
                </x-action-message>

                <x-action-message class="p-3 rounded bg-success-200 text-emerald-600" role="alert" on="saved">
                    {{ __('app.saved') }}
                </x-action-message>
            </div>

            <div class="flex-1 flex-grow max-w-full text-end">
                <x-ts-button color="secondary" class="mr-1" wire:click="resetFamily()" wire:dirty>
                    {{ __('app.cancel') }}
                </x-ts-button>

                <x-ts-button type="submit" color="primary">
                    {{ __('app.save') }}
                </x-ts-button>
            </div>
        </div>
    </div>
</form>
