<div class="space-y-6">
    <!-- Team Name Header -->
    <div class="bg-white dark:bg-neutral-700 rounded-lg p-4 border">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
            {{ $user->currentTeam->name }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('team.team_overview') }}</p>
    </div>

    <!-- Three Category Cards side by side -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Users Card -->
        <button wire:click="$set('activeTab', 'users')" class="bg-white dark:bg-neutral-700 rounded-lg p-6 border transition-all duration-200 text-left {{ $activeTab === 'users' ? 'ring-2 ring-yellow-500 shadow-md' : 'border-gray-300' }}" title="{{ __('app.show') }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('team.users') }}
                </h3>
                <div class="flex items-center space-x-2">
                    <x-ts-badge color="emerald" sm text="{{ number_format($teamCounts['users']) }}" />
                    @if($activeTab === 'users')
                        <x-ts-icon icon="tabler.check" class="size-5 text-yellow-500" />
                    @endif
                </div>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('team.click_to_view_members') }}
            </p>
        </button>

        <!-- Persons Card -->
        <button wire:click="$set('activeTab', 'persons')" class="bg-white dark:bg-neutral-700 rounded-lg p-6 border transition-all duration-200 text-left {{ $activeTab === 'persons' ? 'ring-2 ring-yellow-500 shadow-md' : 'border-gray-300' }}" title="{{ __('app.show') }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('team.persons') }}
                </h3>
                <div class="flex items-center space-x-2">
                    <x-ts-badge color="emerald" sm text="{{ number_format($teamCounts['persons']) }}" />
                    @if($activeTab === 'persons')
                        <x-ts-icon icon="tabler.check" class="size-5 text-yellow-500" />
                    @endif
                </div>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('team.click_to_view_persons') }}
            </p>
        </button>

        <!-- Couples Card -->
        <button wire:click="$set('activeTab', 'couples')" class="bg-white dark:bg-neutral-700 rounded-lg p-6 border transition-all duration-200 text-left {{ $activeTab === 'couples' ? 'ring-2 ring-yellow-500 shadow-md' : 'border-gray-300' }}" title="{{ __('app.show') }}">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('team.couples') }}
                </h3>
                <div class="flex items-center space-x-2">
                    <x-ts-badge color="emerald" sm text="{{ number_format($teamCounts['couples']) }}" />
                    @if($activeTab === 'couples')
                        <x-ts-icon icon="tabler.check" class="size-5 text-yellow-500" />
                    @endif
                </div>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('team.click_to_view_couples') }}
            </p>
        </button>
    </div>

    <!-- Search Bar -->
    <div class="bg-white dark:bg-neutral-700 rounded-lg p-4">
        <div class="flex items-center justify-between space-x-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('app.show') }} :</span>
                <x-ts-select.styled :options="[5, 10, 25, 50, 100]" wire:model.live="perPage" required button-class="!w-24" />

                {{-- <select wire:model.live="perPage" class="border rounded-md text-sm px-2 py-1
                        bg-white dark:bg-gray-800 dark:border-gray-700
                        text-gray-700 dark:text-gray-300
                        focus:ring focus:ring-indigo-500/50
                        w-20">
                    @foreach ([5, 10, 25, 50, 100] as $size)
                        <option value="{{ $size }}">{{ $size }}</option>
                    @endforeach
                </select> --}}
            </div>

            <!-- Left: Search Input -->
            <div class="flex-1 max-w-md">
                <x-ts-input wire:model.live.debounce.300ms="search" placeholder="{{ __('app.search') }} {{ strtolower(__('team.' . $activeTab)) }} ..." class="w-full" />
            </div>

            <!-- Right: Results Count -->
            <div class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('team.showing') }} {{ $paginatedData->firstItem() ?? 0 }} - {{ $paginatedData->lastItem() ?? 0 }}
                {{ __('team.of') }} {{ number_format($paginatedData->total()) }} {{ strtolower(__('team.' . $activeTab)) }}
            </div>
        </div>

        @if($search)
            <div class="mt-3 flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                <x-ts-icon icon="tabler.filter" class="size-4" />
                <span>{{ __('team.filtered_by') }} : "{{ $search }}"</span>
                <button wire:click="$set('search', '')"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                    {{ __('team.reset_filter') }}
                </button>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($paginatedData->hasPages())
        <div class="bg-white dark:bg-neutral-700 px-4 py-3 border border-gray-200 dark:border-neutral-600 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    {{ $paginatedData->links('vendor.pagination.team.custom-tailwind') }}
                </div>
            </div>
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white dark:bg-neutral-700 rounded-lg overflow-hidden">
        @if($paginatedData->count() > 0)
            @if($activeTab === 'users')
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-neutral-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('team.users') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-700 divide-y divide-gray-200 dark:divide-neutral-600">
                            @foreach($paginatedData->items() as $user)
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
            @elseif($activeTab === 'persons')
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-neutral-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('team.persons') }}
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('backup.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-700 divide-y divide-gray-200 dark:divide-neutral-600">
                            @foreach($paginatedData->items() as $person)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $person['name'] }}
                                            </span>
                                            <x-ts-icon icon="tabler.{{ $person['sex'] === 'm' ? 'gender-male' : 'gender-female' }}"
                                                class="size-5 {{ $person['sex'] === 'm' ? 'text-blue-500' : 'text-pink-500' }}" />
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <x-ts-link href="/people/{{ $person['id'] }}" class="text-indigo-600 hover:text-yellow-500">{{ __('app.show') }}</x-ts-link>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('team.couples') }}
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    {{ __('backup.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-neutral-700 divide-y divide-gray-200 dark:divide-neutral-600">
                            @foreach($paginatedData->items() as $couple)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $couple['person1']['name'] }}
                                            </span>
                                            <x-ts-icon icon="tabler.{{ $couple['person1']['sex'] === 'm' ? 'gender-male' : 'gender-female' }}"
                                                class="size-5 {{ $couple['person1']['sex'] === 'm' ? 'text-blue-500' : 'text-pink-500' }}" />
                                            <span class="text-gray-500">-</span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $couple['person2']['name'] }}
                                            </span>
                                            <x-ts-icon icon="tabler.{{ $couple['person2']['sex'] === 'm' ? 'gender-male' : 'gender-female' }}"
                                                class="size-5 {{ $couple['person2']['sex'] === 'm' ? 'text-blue-500' : 'text-pink-500' }}" />
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-8 text-center">
                                        <x-ts-link href="/people/{{ $couple['person1']['id'] }}" title="{{ __('app.show') }} {{ $couple['person1']['name'] }}" class="text-indigo-600 hover:text-yellow-500">
                                            # 1
                                        </x-ts-link>
                                        <x-ts-link href="/people/{{ $couple['person2']['id'] }}" title="{{ __('app.show') }} {{ $couple['person2']['name'] }}" class="text-indigo-600 hover:text-yellow-500">
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
                <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ __('team.no_results_found') }}
                </h3>
                @if($search)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('team.try_adjusting_your_search_terms') }}
                    </p>
                    <div class="mt-4">
                        <button wire:click="$set('search', '')"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                            {{ __('team.reset_filter') }}
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
