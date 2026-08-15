<div>
    <form wire:submit="savePartner">
        @csrf

        <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] md:w-3xl dark:bg-neutral-700 dark:text-neutral-50">
            <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="max-w-full min-w-max flex-1 grow">{{ __('person.edit_relationship') }}</div>

                    <div class="max-w-full min-w-max flex-1 grow text-end">
                        <x-ts-icon icon="tabler.user-edit" class="inline-block size-5" />
                    </div>
                </div>
            </div>

            <div class="bg-neutral-200 p-4">
                <x-ts-errors class="mb-2" close />

                <div class="grid grid-cols-6 gap-5">
                    {{-- partner_id --}}
                    <div class="col-span-6">
                        <x-ts-select.styled
                            wire:model="partner_id"
                            id="partner_id"
                            label="{{ __('person.partner') }} : *"
                            :options="$persons"
                            select="label:name|value:id"
                            placeholder="{{ __('app.select') }} ..."
                            searchable
                        />
                    </div>

                    {{-- date_start --}}
                    <div class="col-span-3">
                        <x-ts-date
                            wire:model="date_start"
                            id="date_start"
                            label="{{ __('couple.date_start') }} :"
                            format="YYYY-MM-DD"
                            :max-date="now()"
                            placeholder="{{ __('app.select') }} ..."
                        />
                    </div>

                    {{-- date_end --}}
                    <div class="col-span-3">
                        <x-ts-date
                            wire:model="date_end"
                            id="date_end"
                            label="{{ __('couple.date_end') }} :"
                            format="YYYY-MM-DD"
                            :max-date="now()"
                            placeholder="{{ __('app.select') }} ..."
                        />
                    </div>

                    {{-- is_married --}}
                    <div class="col-span-3">
                        <x-ts-toggle
                            wire:model="is_married"
                            name="is_married"
                            id="is_married"
                            label="{{ __('couple.is_married') }} ?"
                            position="left"
                        />
                    </div>

                    {{-- has_ended --}}
                    <div class="col-span-3">
                        <x-ts-toggle
                            wire:model="has_ended"
                            name="has_ended"
                            id="has_ended"
                            label="{{ __('couple.has_ended') }} ?"
                            position="left"
                        />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end rounded-b p-4">
                <x-ts-button type="submit" color="primary"> {{ __('app.save') }} </x-ts-button>
            </div>
        </div>
    </form>

    @if (auth()->user()->currentTeam->personal_team)
        @include('components.livewire.people.partials.caution-personal-team')
    @endif
</div>
