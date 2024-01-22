<div>
    <!-- search -->
    <div class="w-full mb-5">
        <form>
            <div class="p-5 flex flex-col justify-end rounded dark:text-neutral-200 bg-white dark:bg-neutral-700 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
                <!-- heading -->
                <div class="flex flex-wrap mb-2 text-lg">
                    <div class="flex-grow max-w-full flex-1">
                        {{ __('app.search_family') }}
                    </div>

                    <div class="flex-grow max-w-full flex-1 text-center">
                        @if (auth()->user()->hasPermission('person:create'))
                            <a wire:navigate href="/people/add">
                                <x-button.success>
                                    <x-icon.tabler icon="user-plus" class="me-2" />
                                    {{ __('person.add_person') }}
                                </x-button.success>
                            </a>
                        @endif
                    </div>

                    <div class="flex-grow max-w-full flex-1 text-end">
                        @if ($this->search)
                            @if (env('GOD_MODE', 'false') && auth()->user()->is_developer)
                                {!! __('app.persons_found', [
                                    'total' => $people->total(),
                                    'scope' => 'ALL TEAMS',
                                    'keyword' => $this->search,
                                ]) !!}
                            @else
                                {!! __('app.persons_found', [
                                    'total' => $people->total(),
                                    'scope' => auth()->user()->currentTeam->name,
                                    'keyword' => $this->search,
                                ]) !!}
                            @endif
                        @else
                            @if (env('GOD_MODE', 'false') && auth()->user()->is_developer)
                                {!! __('app.persons_available', [
                                    'total' => $people_db,
                                    'scope' => 'ALL TEAMS',
                                ]) !!}
                            @else
                                {!! __('app.persons_available', [
                                    'total' => $people_db,
                                    'scope' => auth()->user()->currentTeam->name,
                                ]) !!}
                            @endif
                        @endif
                    </div>
                </div>

                <!-- search box -->
                <div class="flex flex-wrap items-stretch">
                    <input wire:model.live.debounce.500ms="search" type="search" placeholder="{{ __('app.search_family_placeholder') }}" aria-label="Search" x-init="$el.focus();"
                        class="m-0 -mr-0.5 block w-[1px] min-w-0 flex-auto rounded bg-transparent border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-auto focus:border-primary focus:text-neutral-700 dark:focus:text-neutral-200 focus:shadow-[inset_0_0_0_1px_rgb(59,113,202)] focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200 dark:focus:border-primary" />
                </div>

                <!-- pagination -->
                @if ($people->count() > 0)
                    <div class="mt-2 flex flex-wrap gap-2 justify-center items-center">
                        <div class="flex-grow min-w-max max-w-36 flex-1">
                            <x-simple-select class="bg-white" wire:model.live="perpage" name="perpage" id="perpage" :options="$options" :searchable="false" :clearable="false"
                                class="form-select pl-0 py-0" />
                        </div>

                        <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                            {{ $people->links('livewire/pagination/tailwind') }}
                        </div>
                    </div>
                @endif
            </div>
        </form>
    </div>

    <!-- people grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-5">
        @foreach ($people as $person)
            <livewire:people.person :person="$person" :key="$person->id" />
        @endforeach
    </div>
</div>
