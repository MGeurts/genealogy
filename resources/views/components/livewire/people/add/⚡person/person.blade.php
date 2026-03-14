<div class="w-full p-2 space-y-5 overflow-x-auto">
    <div class="flex flex-wrap gap-5">
        <div class="flex flex-col gap-5 grow md:max-w-max">
            <form wire:submit="savePerson">
                @csrf

                <div
                    class="max-w-max flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
                    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                        <div class="flex flex-wrap items-start justify-center gap-2">
                            <div class="flex-1 grow max-w-full min-w-max">
                                {{ __('person.add_person') }}
                            </div>

                            <div class="flex-1 grow max-w-full min-w-max text-end">
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

        <div class="flex flex-col grow md:max-w-max">
            @include('components.livewire.people.partials.person-similar')

            @if (auth()->user()->currentTeam->personal_team)
                @include('components.livewire.people.partials.caution-personal-team')
            @endif
        </div>
    </div>
</div>
