<nav x-data="{ open: false }" class="bg-neutral-200 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-200 border-b border-gray-100">
    {{-- primary navigation menu --}}
    <div class="px-2 flex min-h-16 gap-5">
        <div class="flex flex-grow gap-5">
            {{-- logo --}}
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" title="{{ __('app.home') }}">
                    <x-svg.genealogy class="size-12 fill-dark dark:fill-neutral-400 hover:fill-primary-300 dark:hover:fill-primary-300" alt="genealogy" />
                </a>
            </div>

            {{-- navigation links --}}
            <div class="flex flex-wrap gap-5 py-2">
                <div class="flex items-center gap-5">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        <x-ts-icon icon="home" class="size-5 mr-1" />
                        {{ __('app.home') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('people.search') }}" :active="request()->routeIs('people.search')">
                        <x-ts-icon icon="search" class="size-5 mr-1" />
                        {{ __('app.search') }}
                    </x-nav-link>
                </div>

                <div class="flex items-center gap-5">
                    <x-nav-link href="{{ route('people.birthdays') }}" :active="request()->routeIs('people.birthdays')">
                        <x-ts-icon icon="cake" class="size-5 mr-1" />
                        {{ __('birthday.birthdays') }}
                    </x-nav-link>

                    <x-nav-link href="{{ route('help') }}" :active="request()->routeIs('help')">
                        <x-ts-icon icon="help" class="size-5 mr-1" />
                        {{ __('app.help') }}
                    </x-nav-link>
                </div>
            </div>
        </div>

        <div class="hidden md:flex md:items-center gap-5">
            @auth
                {{-- settings dropdown --}}
                <div class="relative min-w-max">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" title="{{ auth()->user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded">
                                    <button type="button" title="{{ auth()->user()->name }}"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ auth()->user()->name }}

                                        <svg class="ml-2 -mr-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            {{-- account management --}}
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('app.manage_account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                <x-ts-icon icon="id" class="size-5 inline-block mr-1" />
                                {{ __('app.my_profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    <x-ts-icon icon="api" class="size-5 inline-block mr-1" />
                                    {{ __('app.api_tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            {{-- authentication --}}
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                    <x-ts-icon icon="logout" class="size-5 inline-block mr-1" />
                                    {{ __('auth.logout') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- teams dropdown --}}
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="relative min-w-max">
                        <x-dropdown align="right" width="60px">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded">
                                    <button type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ auth()->user()->currentTeam->name }}

                                        <svg class="ml-2 -mr-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-80">
                                    {{-- teams switcher --}}
                                    @if (auth()->user()->allTeams()->count() > 1)
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('team.switch') }}
                                        </div>

                                        @foreach (auth()->user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach

                                        <hr />
                                    @endif

                                    {{-- teams management --}}
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('team.manage') }}
                                    </div>

                                    {{-- teams settings --}}
                                    <x-dropdown-link href="{{ route('teams.show', auth()->user()->currentTeam->id) }}">
                                        <x-ts-icon icon="droplet-cog" class="size-5 inline-block mr-1" />
                                        {{ __('team.settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            <x-ts-icon icon="droplet-plus" class="size-5 inline-block mr-1" />
                                            {{ __('team.create') }}
                                        </x-dropdown-link>

                                        <hr />

                                        {{-- gedcom --}}
                                        <x-dropdown-link href="{{ route('gedcom.import') }}">
                                            <x-ts-icon icon="droplet-up" class="size-5 inline-block mr-1" />
                                            {{ __('gedcom.gedcom_import') }}
                                        </x-dropdown-link>

                                        <x-dropdown-link href="{{ route('gedcom.export') }}">
                                            <x-ts-icon icon="droplet-down" class="size-5 inline-block mr-1" />
                                            {{ __('gedcom.gedcom_export') }}
                                        </x-dropdown-link>
                                    @endcan
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif
            @else
                <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                    <x-ts-icon icon="login-2" class="size-5 inline-block mr-1" />
                    {{ __('auth.login') }}
                </x-nav-link>

                <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                    <x-ts-icon icon="user-plus" class="size-5 inline-block mr-1" />
                    {{ __('auth.register') }}
                </x-nav-link>

                <x-set.language />
            @endauth
        </div>

        <div class="flex flex-col place-items-center">
            <div class="min-h-8 hidden md:flex md:items-center md:ml-5 space-x-6">
                <x-ts-theme-switch only-icons />
            </div>
            <div class="min-h-8 hidden md:flex md:items-center md:ml-5 space-x-6">
                <x-set.offcanvas />
            </div>
        </div>

        {{-- hamburger --}}
        <div class="flex items-center md:hidden">
            <button @click="open = ! open"
                class="inline-flex items-center justify-center p-2 rounded text-gray-400 hover:text-gray-500 hover:bg-gray-300 focus:outline-none focus:bg-gray-300 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- responsive navigation menu --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden">
        <div class="space-y-1 border-t border-gray-200">
            <x-nav-link-responsive href="{{ route('home') }}" :active="request()->routeIs('home')">
                <x-ts-icon icon="home" class="size-5 inline-block mr-1" />
                {{ __('app.home') }}
            </x-nav-link-responsive>

            <x-nav-link-responsive href="{{ route('people.search') }}" :active="request()->routeIs('people.search')">
                <x-ts-icon icon="search" class="size-5 inline-block mr-1" />
                {{ __('app.search') }}
            </x-nav-link-responsive>

            <x-nav-link-responsive href="{{ route('people.birthdays') }}" :active="request()->routeIs('people.birthdays')">
                <x-ts-icon icon="cake" class="size-5 inline-block mr-1" />
                {{ __('birthday.birthdays') }}
            </x-nav-link-responsive>

            <x-nav-link-responsive href="{{ route('help') }}" :active="request()->routeIs('help')">
                <x-ts-icon icon="help" class="size-5 inline-block mr-1" />
                {{ __('app.help') }}
            </x-nav-link-responsive>
        </div>

        @guest
            <div class="space-y-1 border-t border-gray-200">
                <x-nav-link-responsive href="{{ route('login') }}" :active="request()->routeIs('login')">
                    <x-ts-icon icon="login-2" class="size-5 inline-block mr-1" />
                    {{ __('auth.login') }}
                </x-nav-link-responsive>

                <x-nav-link-responsive href="{{ route('register') }}" :active="request()->routeIs('register')">
                    <x-ts-icon icon="user-plus" class="size-5 inline-block mr-1" />
                    {{ __('auth.register') }}
                </x-nav-link-responsive>
            </div>

            {{-- responsive settings options --}}
            <div class="pl-4 pt-2 pb-2 border-t border-gray-200">
                <x-set.language />
            </div>
            <div class="pl-4 pt-2 pb-2 border-t border-gray-200">
                <x-ts-theme-switch only-icons />
            </div>
            <div class="pl-4 pt-2 pb-2 border-t border-gray-200">
                <x-set.offcanvas />
            </div>
        @endguest

        @auth
            <div class="pt-2 pb-2 border-t border-gray-200">
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800 dark:text-gray-400">{{ auth()->user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
                    </div>
                </div>

                <div class="space-y-1">
                    {{-- account management --}}
                    <x-nav-link-responsive href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        <x-ts-icon icon="id" class="size-5 inline-block mr-1" />
                        {{ __('app.my_profile') }}
                    </x-nav-link-responsive>

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                        <x-nav-link-responsive href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                            <x-ts-icon icon="api" class="size-5 inline-block mr-1" />
                            {{ __('app.api_tokens') }}
                        </x-nav-link-responsive>
                    @endif

                    {{-- authentication --}}
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-nav-link-responsive href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            <x-ts-icon icon="logout" class="size-5 inline-block mr-1" />
                            {{ __('auth.logout') }}
                        </x-nav-link-responsive>
                    </form>

                    {{-- team switcher --}}
                    @if (auth()->user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('team.switch') }}
                        </div>

                        @foreach (auth()->user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="nav-link-responsive" />
                        @endforeach
                    @endif

                    {{-- team management --}}
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('team.manage') }}
                        </div>

                        {{-- team settings --}}
                        <x-nav-link-responsive href="{{ route('teams.show', auth()->user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                            <x-ts-icon icon="droplet-cog" class="size-5 inline-block mr-1" />
                            {{ __('team.settings') }}
                        </x-nav-link-responsive>

                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                            <x-nav-link-responsive href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                                <x-ts-icon icon="droplet-plus" class="size-5 inline-block mr-1" />
                                {{ __('team.create') }}
                            </x-nav-link-responsive>

                            {{-- gedcom --}}
                            <x-nav-link-responsive href="{{ route('gedcom.import') }}" :active="request()->routeIs('gedcom.import')">
                                <x-ts-icon icon="droplet-up" class="size-5 inline-block mr-1" />
                                {{ __('team.gedcom_import') }}
                            </x-nav-link-responsive>

                            <x-nav-link-responsive href="{{ route('gedcom.export') }}" :active="request()->routeIs('gedcom.export')">
                                <x-ts-icon icon="droplet-down" class="size-5 inline-block mr-1" />
                                {{ __('team.gedcom_export') }}
                            </x-nav-link-responsive>
                        @endcan
                    @endif
                </div>
            </div>

            {{-- responsive settings options --}}
            <div class="pl-4 py-2 border-t border-gray-200">
                <x-ts-theme-switch only-icons />
            </div>
            <div class="pl-4 py-1 border-t border-gray-200">
                <x-set.offcanvas />
            </div>
        @endauth
    </div>
</nav>
