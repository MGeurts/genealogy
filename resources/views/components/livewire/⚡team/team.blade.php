<div>
    @section('title')
        &vert; {{ __('team.team') }}
    @endsection

    <div class="max-w-7xl grow overflow-x-auto p-2 dark:text-neutral-200">
        <div class="space-y-6">
            <!-- Team Name Header -->
            <div class="rounded-lg border bg-white p-4 dark:bg-neutral-700">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $user->currentTeam->name }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('team.team_overview') }}</p>
            </div>

            <!-- Three Category Cards side by side -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <!-- Users Card -->
                <button
                    wire:click="$set('activeTab', 'users')"
                    class="bg-white dark:bg-neutral-700 rounded-lg p-6 border transition-all duration-200 text-left {{ $activeTab === 'users' ? 'ring-2 ring-yellow-500 shadow-md' : 'border-gray-300' }}"
                    title="{{ __('app.show') }}"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('team.users') }}</h3>
                        <div class="flex items-center space-x-2">
                            <x-ts-badge color="emerald" sm text="{{ number_format($teamCounts['users']) }}" />
                            @if ($activeTab === 'users')
                                <x-ts-icon icon="tabler.check" class="size-5 text-yellow-500" />
                            @endif
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('team.click_to_view_members') }}</p>
                </button>

                <!-- Persons Card -->
                <button
                    wire:click="$set('activeTab', 'persons')"
                    class="bg-white dark:bg-neutral-700 rounded-lg p-6 border transition-all duration-200 text-left {{ $activeTab === 'persons' ? 'ring-2 ring-yellow-500 shadow-md' : 'border-gray-300' }}"
                    title="{{ __('app.show') }}"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('team.persons') }}</h3>
                        <div class="flex items-center space-x-2">
                            <x-ts-badge color="emerald" sm text="{{ number_format($teamCounts['persons']) }}" />
                            @if ($activeTab === 'persons')
                                <x-ts-icon icon="tabler.check" class="size-5 text-yellow-500" />
                            @endif
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('team.click_to_view_persons') }}</p>
                </button>

                <!-- Couples Card -->
                <button
                    wire:click="$set('activeTab', 'couples')"
                    class="bg-white dark:bg-neutral-700 rounded-lg p-6 border transition-all duration-200 text-left {{ $activeTab === 'couples' ? 'ring-2 ring-yellow-500 shadow-md' : 'border-gray-300' }}"
                    title="{{ __('app.show') }}"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('team.couples') }}</h3>
                        <div class="flex items-center space-x-2">
                            <x-ts-badge color="emerald" sm text="{{ number_format($teamCounts['couples']) }}" />
                            @if ($activeTab === 'couples')
                                <x-ts-icon icon="tabler.check" class="size-5 text-yellow-500" />
                            @endif
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('team.click_to_view_couples') }}</p>
                </button>
            </div>

            <!-- Search Bar -->
            <div class="rounded-lg bg-white p-4 dark:bg-neutral-700">
                <div class="flex items-center justify-between space-x-2">
                    <!-- Left: PerPage Select -->
                    <div class="max-w-24 min-w-max flex-1 grow items-center space-x-2">
                        <x-ts-select.styled
                            :options="[5, 10, 25, 50, 100]"
                            wire:model.live="perPage"
                            required
                            button-class="!w-24"
                        />
                    </div>

                    <!-- Right: Search Input -->
                    <div class="max-w-md flex-1">
                        <x-ts-input
                            wire:model.live.debounce.300ms="search"
                            placeholder="{{ __('app.search') }} {{ strtolower(__('team.' . $activeTab)) }} ..."
                            class="w-full"
                        />
                    </div>
                </div>

                @if ($search)
                    <div class="mt-3 flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                        <x-ts-icon icon="tabler.filter" class="size-5" />
                        <span>{{ __('team.filtered_by') }} : "{{ $search }}"</span>
                        <button
                            wire:click="$set('search', '')"
                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-100 px-3 py-2 text-sm leading-4 font-medium text-indigo-700 hover:bg-indigo-200"
                        >
                            {{ __('team.reset_filter') }}
                        </button>
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if ($this->paginatedData->hasPages())
                <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-neutral-600 dark:bg-neutral-700">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">{{ $this->paginatedData->links('components/pagination/tailwind') }}</div>
                    </div>
                </div>
            @endif

            <!-- Data Table -->
            <div class="overflow-hidden rounded-lg bg-white dark:bg-neutral-700">
                @if ($this->paginatedData->count() > 0)
                    @if ($activeTab === 'users')
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">
                                            {{ __('team.users') }}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-neutral-600 dark:bg-neutral-700">
                                    @foreach ($this->paginatedData->items() as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $user['name'] }}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif ($activeTab === 'persons')
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">
                                            {{ __('team.persons') }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">
                                            {{ __('backup.actions') }}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-neutral-600 dark:bg-neutral-700">
                                    @foreach ($this->paginatedData->items() as $person)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $person['name'] }}
                                                    </span>
                                                    <x-ts-icon
                                                        icon="tabler.{{ $person['sex'] === 'm' ? 'gender-male' : 'gender-female' }}"
                                                        class="size-5 {{ $person['sex'] === 'm' ? 'text-blue-500' : 'text-pink-500' }}"
                                                    />
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                                <x-ts-link
                                                    href="/people/{{ $person['id'] }}"
                                                    class="text-indigo-600 hover:text-yellow-500"
                                                >{{ __('app.show') }}</x-ts-link>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 dark:bg-neutral-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">
                                            {{ __('team.couples') }}
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400">
                                            {{ __('backup.actions') }}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-neutral-600 dark:bg-neutral-700">
                                    @foreach ($this->paginatedData->items() as $couple)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $couple['person1']['name'] }}
                                                    </span>
                                                    <x-ts-icon
                                                        icon="tabler.{{ $couple['person1']['sex'] === 'm' ? 'gender-male' : 'gender-female' }}"
                                                        class="size-5 {{ $couple['person1']['sex'] === 'm' ? 'text-blue-500' : 'text-pink-500' }}"
                                                    />
                                                    <span class="text-gray-500">-</span>
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $couple['person2']['name'] }}
                                                    </span>
                                                    <x-ts-icon
                                                        icon="tabler.{{ $couple['person2']['sex'] === 'm' ? 'gender-male' : 'gender-female' }}"
                                                        class="size-5 {{ $couple['person2']['sex'] === 'm' ? 'text-blue-500' : 'text-pink-500' }}"
                                                    />
                                                </div>
                                            </td>
                                            <td class="space-x-8 px-6 py-4 text-center whitespace-nowrap">
                                                <x-ts-link
                                                    href="/people/{{ $couple['person1']['id'] }}"
                                                    title="{{ __('app.show') }} {{ $couple['person1']['name'] }}"
                                                    class="text-indigo-600 hover:text-yellow-500"
                                                >
                                                    # 1
                                                </x-ts-link>
                                                <x-ts-link
                                                    href="/people/{{ $couple['person2']['id'] }}"
                                                    title="{{ __('app.show') }} {{ $couple['person2']['name'] }}"
                                                    class="text-indigo-600 hover:text-yellow-500"
                                                >
                                                    # 2
                                                </x-ts-link>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @else
                    <div class="px-6 py-12 text-center">
                        <x-ts-icon icon="tabler.search" class="mx-auto size-12 text-gray-400" />
                        <h3 class="mt-4 font-medium text-gray-900 dark:text-gray-100">
                            {{ __('team.no_results_found') }}
                        </h3>

                        @if ($search)
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('team.try_adjusting_your_search_terms') }}
                            </p>

                            <div class="mt-4">
                                <button
                                    wire:click="$set('search', '')"
                                    class="inline-flex items-center rounded-md border border-transparent bg-indigo-100 px-3 py-2 text-sm leading-4 font-medium text-indigo-700 hover:bg-indigo-200"
                                >
                                    {{ __('team.reset_filter') }}
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
