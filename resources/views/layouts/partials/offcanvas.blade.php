<x-ts-slide id="offcanvas" size="sm" blur>
    <x-slot:title>
        {{ __('app.menu') }}
    </x-slot:title>

    {{-- role and permissions --}}
    <div class="pb-4">
        <div class="rounded bg-secondary-100 p-4 text-base text-secondary-800" role="alert">
            <div class="flex flex-row">
                <div class="basis-1/2">
                    {{ __('auth.role') }} :
                    <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />
                    {{ __('auth.permissions') }} :
                </div>

                <div class="basis-1/2">
                    @if (Auth::user())
                        {{ Auth::user()->teamRole(auth()->user()->currentTeam)->name }}

                        <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />
                        @foreach (Auth::user()->teamPermissions(Auth::user()->currentTeam) as $permission)
                            {{ $permission }}<br />
                        @endforeach
                    @else
                        {{ __('auth.guest') }}
                        <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- offcanvas menu --}}
    <div class="flex-grow overflow-y-auto">
        @if (Auth::user())
            @if (Auth::user()->is_developer)
                {{-- developer --}}
                <div>{{ __('auth.developer') }} ...</div>

                <div>
                    <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('teams') }}" :active="request()->routeIs('teams')">
                            {{ __('team.teams') }}
                        </x-nav-link-responsive>
                    </p>
                    <p>
                        <x-nav-link-responsive href="{{ route('persons') }}" :active="request()->routeIs('persons')">
                            {{ __('person.people') }}
                        </x-nav-link-responsive>
                    </p>
                </div>

                <div>
                    <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('users') }}" :active="request()->routeIs('users')">
                            {{ __('user.users') }}
                        </x-nav-link-responsive>
                    </p>

                    <p>
                        <x-nav-link-responsive href="{{ route('userlogs.log') }}" :active="request()->routeIs('userlogs.log')">
                            {{ __('userlog.users_log') }}
                        </x-nav-link-responsive>
                    </p>

                    <p>
                        <x-nav-link-responsive href="{{ route('userlogs.origin') }}" :active="request()->routeIs('userlogs.origin')">
                            {{ __('userlog.users_origin') }}
                        </x-nav-link-responsive>
                    </p>

                    <p>
                        <x-nav-link-responsive href="{{ route('userlogs.origin-map') }}" :active="request()->routeIs('userlogs.origin-map')">
                            {{ __('userlog.users_origin') }} (Map)
                        </x-nav-link-responsive>
                    </p>

                    <p>
                        <x-nav-link-responsive href="{{ route('userlogs.period') }}" :active="request()->routeIs('userlogs.period')">
                            {{ __('userlog.users_stats') }}
                        </x-nav-link-responsive>
                    </p>
                </div>

                <div>
                    <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('backups') }}" :active="request()->routeIs('backups')">
                            {{ __('Backups') }}
                        </x-nav-link-responsive>
                    </p>

                    <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ url('log-viewer') }}" target="_blank">
                            {{ __('Log Viewer') }}
                        </x-nav-link-responsive>
                    </p>
                </div>

                <div>
                    <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('dependencies') }}" :active="request()->routeIs('dependencies')">
                            {{ __('app.dependencies') }}
                        </x-nav-link-responsive>
                    </p>

                    <p>
                        <x-nav-link-responsive href="{{ route('session') }}" :active="request()->routeIs('session')">
                            {{ __('app.session') }}
                        </x-nav-link-responsive>
                    </p>
                </div>
            @else
                {{-- others --}}
                <div>{{ Auth::user()->teamRole(auth()->user()->currentTeam)->name }} ...</div>

                <div>
                    <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('help') }}" :active="request()->routeIs('help')">
                            {{ __('app.help') }}
                        </x-nav-link-responsive>
                    </p>
                </div>
            @endif
        @endif

        @guest
            {{-- guest --}}
            <div>{{ __('auth.guest') }} ...</div>

            <div><x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />
            </div>

            <p>
                <x-nav-link-responsive href="{{ route('help') }}" :active="request()->routeIs('help')">
                    {{ __('app.help') }}
                </x-nav-link-responsive>
            </p>
        @endguest
    </div>

    <x-slot:footer end>
        <div class="flex items-center text-xs">
            <div class="text-right px-2">
                {{ __('app.design_development') }}<br />
                {{ __('app.by') }} <x-link href="https://www.kreaweb.be/" target="_blank">KREAWEB</x-link>
            </div>

            <a href="https://www.kreaweb.be/" target="_blank" title="Kreaweb">
                <x-svg.kreaweb class="size-11 dark:fill-white hover:fill-primary-300 dark:hover:fill-primary-300" alt="kreaweb" />
            </a>
        </div>
    </x-slot:footer>
</x-ts-slide>
