<div class="w-full space-y-5 overflow-x-auto p-2">
    <div class="flex flex-wrap gap-5">
        <div class="flex grow flex-col gap-5 md:max-w-max">
            <form wire:submit="savePerson">
                @csrf

                <div class="flex max-w-max flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50">
                    <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
                        <div class="flex flex-wrap items-start justify-center gap-2">
                            <div class="max-w-full min-w-max flex-1 grow">{{ __('person.add_person') }}</div>

                            <div class="max-w-full min-w-max flex-1 grow text-end">
                                <x-ts-icon icon="tabler.user-plus" class="inline-block size-5" />
                            </div>
                        </div>
                    </div>

                    <div>
                        {{-- Add New Person UI --}}
                        @include('components.livewire.people.partials.person-new')
                    </div>
                </div>
            </form>
        </div>

        <div class="flex grow flex-col md:max-w-max">
            @include('components.livewire.people.partials.person-similar')

            @if (auth()->user()->currentTeam->personal_team)
                @include('components.livewire.people.partials.caution-personal-team')
            @endif
        </div>
    </div>
</div>
