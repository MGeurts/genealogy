<div>
    <form wire:submit="saveFather">
        @csrf

        <x-ts-tab selected="{{ $selectedTab }}" class="dark:bg-red-100">
            <x-ts-tab.items tab="{{ __('person.add_new_person_as_father') }}">
                <x-slot:left>
                    <x-ts-icon name="tabler.user-plus" class="inline-block size-5"/>
                </x-slot:left>

                {{-- Add New Person UI --}}
                @include('livewire.people.partials.father-new')
            </x-ts-tab.items>

            <x-ts-tab.items tab="{{ __('person.add_existing_person_as_father') }}">
                <x-slot:left>
                    <x-ts-icon name="tabler.search" class="inline-block size-5" />
                </x-slot:left>

                {{-- Select Existing Person UI --}}
                @include('livewire.people.partials.person-existing')
            </x-ts-tab.items>
        </x-ts-tab>
    </form>

    @if (auth()->user()->currentTeam->personal_team)
        @include('livewire.people.partials.caution-personal-team')
    @endif
</div>
