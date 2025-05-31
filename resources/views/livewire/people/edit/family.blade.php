<form wire:submit="saveFamily">
    @csrf

    <div class="md:w-192 flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
            <div class="flex flex-wrap items-start justify-center gap-2">
                <div class="flex-1 grow max-w-full min-w-max">
                    {{ __('person.edit_family') }}
                </div>

                <div class="flex-1 grow max-w-full min-w-max text-end">
                    <x-ts-icon icon="tabler.edit" class="inline-block" />
                </div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200">
            <x-ts-errors class="mb-2" close />

            <div class="grid grid-cols-6 gap-5">
                {{-- father_id --}}
                <div class="col-span-6">
                    <x-ts-select.styled wire:model="father_id" id="father_id" label="{{ __('person.father') }} ({{ __('person.biological') }}) :" :options="$fathers"
                        select="label:name|value:id" placeholder="{{ __('app.select') }} ..." searchable />
                </div>

                {{-- mother_id --}}
                <div class="col-span-6">
                    <x-ts-select.styled wire:model="mother_id" id="mother_id" label="{{ __('person.mother') }} ({{ __('person.biological') }}) :" :options="$mothers"
                        select="label:name|value:id" placeholder="{{ __('app.select') }} ..." searchable />
                </div>

                <div class="col-span-6">
                    <x-ts-alert color="cyan" icon="tabler.exclamation-circle" close>
                        <x-slot:title>
                            {{ __('team.personal_team_caution') }}
                        </x-slot:title>

                        <p>{{ __('person.family_caution_1') }}</p>

                        <x-hr.narrow class="col-span-6" />

                        <p>{{ __('person.family_caution_2') }}</p>
                    </x-ts-alert>
                </div>

                {{-- parents_id --}}
                <div class="col-span-6">
                    <x-ts-select.styled wire:model="parents_id" id="parents_id" label="{{ __('person.parents') }} :" :options="$parents" select="label:couple|value:id"
                        placeholder="{{ __('app.select') }} ..." searchable />
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
