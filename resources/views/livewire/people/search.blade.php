<div>
    {{-- search box section --}}
    <div class="mb-5 p-2 flex flex-col rounded dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
        {{-- header --}}
        <div class="flex flex-wrap mb-2 text-lg">
            <div class="flex-1 flex-grow max-w-full">
                @if (auth()->user()->is_developer)
                    {!! __('app.people_search', [
                        'scope' => strtoupper(__('team.all_teams')),
                    ]) !!}
                @else
                    {!! __('app.people_search', [
                        'scope' => auth()->user()->currentTeam->name,
                    ]) !!}
                @endif
            </div>

            <div class="flex-1 flex-grow max-w-full text-center">
                @if (auth()->user()->hasPermission('person:create'))
                    {{-- add button --}}
                    <x-ts-button href="/people/add" color="emerald" class="text-sm">
                        <x-ts-icon icon="user-plus" class="size-5"/>
                        {{ __('person.add_person') }}
                    </x-ts-button>
                @endif
            </div>

            <div class="flex-1 flex-grow max-w-full text-end">
                @if ($this->search)
                    @if (auth()->user()->is_developer)
                        {!! __('app.people_found', [
                            'found' => $people->total(),
                            'total' => $people_db,
                            'scope' => strtoupper(__('team.all_teams')),
                            'keyword' => $this->search,
                        ]) !!}
                    @else
                        {!! __('app.people_found', [
                            'found' => $people->total(),
                            'total' => $people_db,
                            'scope' => auth()->user()->currentTeam->name,
                            'keyword' => $this->search,
                        ]) !!}
                    @endif
                @else
                    @if (auth()->user()->is_developer)
                        {!! __('app.people_available', [
                            'total' => $people_db,
                            'scope' => strtoupper(__('team.all_teams')),
                        ]) !!}
                    @else
                        {!! __('app.people_available', [
                            'total' => $people_db,
                            'scope' => auth()->user()->currentTeam->name,
                        ]) !!}
                    @endif
                @endif
            </div>
        </div>

        {{-- search box --}}
        <div class="flex flex-wrap items-start gap-2">
            <div class="flex-1 flex-grow w-full">
                <x-ts-input wire:model.live.debounce.500ms="search" type="search" icon="search"
                    hint="{{ __('app.people_search_tip') }}" placeholder="{{ __('app.people_search_placeholder') }}"
                    autofocus />
            </div>

            <div class="flex-1 max-w-max">
                <x-ts-button color="info" title="{{ __('app.help') }}" x-on:click="$modalOpen('search-help')" class="!pb-1 !p-1.5 !mt-1 text-sm text-white">
                    <x-ts-icon icon="help" />
                </x-ts-button>
            </div>
        </div>

        {{-- footer : perpage and pagination --}}
        @if (count($people) > 0)
            <div class="flex flex-wrap items-center justify-center gap-2 mt-2">
                <div class="flex-1 flex-grow min-w-max max-w-36">
                    <x-ts-select.styled wire:model.live="perpage" name="perpage" id="perpage" :options="$options" select="label:label|value:value" required />
                </div>

                <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                    {{ $people->links('livewire/pagination/tailwind') }}
                </div>
            </div>
        @endif
    </div>

    {{-- people grid --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
        @foreach ($people as $person)
            <livewire:people.person :person="$person" :key="$person->id" />
        @endforeach
    </div>

    {{-- search help modal --}}
    <x-ts-modal id="search-help" size="6xl" blur>
        <x-slot:title>
            <x-ts-icon icon="help" class="inline-block"/>{{ __('app.help') }}
        </x-slot:title>

        <p>{!! __('app.people_search_help_1') !!}</p><br/>
        <p>{!! __('app.people_search_help_2') !!}</p><br/>
        <p>{!! __('app.people_search_help_3') !!}</p>
    </x-ts-modal>
</div>
