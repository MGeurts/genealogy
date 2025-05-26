<form wire:submit="savePartner">
    @csrf

    <div class="md:w-192 flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="flex-1 grow max-w-full min-w-max">
                    {{ __('person.edit_relationship') }}
                </div>

                <div class="flex-1 grow max-w-full min-w-max text-end">
                    <x-ts-icon icon="tabler.user-edit" class="inline-block" />
                </div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <x-ts-errors class="mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- person2_id --}}
                <div class="col-span-6">
                    <x-ts-select.styled wire:model="partnerForm.person2_id" id="person2_id" label="{{ __('person.partner') }} :" :options="$persons" select="label:name|value:id"
                        placeholder="{{ __('app.select') }} ..." searchable />
                </div>

                {{-- date_start --}}
                <div class="col-span-3">
                    <x-ts-date wire:model="partnerForm.date_start" id="date_start" label="{{ __('couple.date_start') }} :" format="YYYY-MM-DD"
                        :max-date="now()" placeholder="{{ __('app.select') }} ..." />
                </div>

                {{-- date_end --}}
                <div class="col-span-3">
                    <x-ts-date wire:model="partnerForm.date_end" id="date_end" label="{{ __('couple.date_end') }} :" format="YYYY-MM-DD"
                        :max-date="now()" placeholder="{{ __('app.select') }} ..." />
                </div>

                {{-- is_married --}}
                <div class="col-span-3">
                    <x-ts-toggle wire:model="partnerForm.is_married" name="is_married" id="is_married" label="{{ __('couple.is_married') }} ?" position="left" />
                </div>

                {{-- has_ended --}}
                <div class="col-span-3">
                    <x-ts-toggle wire:model="partnerForm.has_ended" name="has_ended" id="has_ended" label="{{ __('couple.has_ended') }} ?" position="left" />
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end p-4 rounded-b">
            <x-ts-button type="submit" color="primary">
                {{ __('app.save') }}
            </x-ts-button>
        </div>
    </div>
</form>
