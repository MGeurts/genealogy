<x-ts-slide id="offcanvas" size="sm" blur>
    <x-slot:title>
        {{ __('app.menu') }}
    </x-slot:title>

    {{-- role and permissions --}}
    <div class="pb-4">
        <div class="p-4 text-base rounded bg-secondary-100 text-secondary-800" role="alert">
            <div class="flex flex-row">
                <div class="basis-1/2">
                    {{ __('auth.role') }} :
                    <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />
                    {{ __('auth.permissions') }} :
                </div>

                <div class="basis-1/2">
                    @auth
                        {{ auth()->user()->teamRole(auth()->user()->currentTeam)->name }}

                        <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />
                        @foreach (auth()->user()->teamPermissions(auth()->user()->currentTeam) as $permission)
                            {{ $permission }}<br />
                        @endforeach
                    @else
                        {{ __('auth.guest') }}
                        
                        <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- offcanvas menu --}}
    <div class="flex-grow overflow-y-auto">
        @auth
            @if (auth()->user()->is_developer)
                {{-- developer --}}
                <div class="text-warning-500 dark:text-warning-200">{{ __('auth.developer') }} ...</div>

                <div>
                    <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('team') }}" :active="request()->routeIs('team')">
                            {{ __('team.team') }}
                        </x-nav-link-responsive>
                    </p>
                </div>

                <div>
                    <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('developer.teams') }}" :active="request()->routeIs('developer.teams')">
                            {{ __('team.teams') }}
                        </x-nav-link-responsive>
                    </p>
                    <p>
                        <x-nav-link-responsive href="{{ route('developer.people') }}" :active="request()->routeIs('developer.people')">
                            {{ __('person.people') }}
                        </x-nav-link-responsive>
                    </p>
                    <p>
                        <x-nav-link-responsive href="{{ route('developer.peoplelog') }}" :active="request()->routeIs('developer.peoplelog')">
                            {{ __('person.people_log') }}
                        </x-nav-link-responsive>
                    </p>
                </div>

                <div>
                    <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('developer.users') }}" :active="request()->routeIs('developer.users')">
                            {{ __('user.users') }}
                        </x-nav-link-responsive>
                    </p>
                    <p>
                        <x-nav-link-responsive href="{{ route('developer.userlogs.origin') }}" :active="request()->routeIs('developer.userlogs.origin')">
                            {{ __('userlog.users_origin') }}
                        </x-nav-link-responsive>
                    </p>
                    <p>
                        <x-nav-link-responsive href="{{ route('developer.userlogs.origin-map') }}" :active="request()->routeIs('developer.userlogs.origin-map')">
                            {{ __('userlog.users_origin') }} (Map)
                        </x-nav-link-responsive>
                    </p>
                    <p>
                        <x-nav-link-responsive href="{{ route('developer.userlogs.period') }}" :active="request()->routeIs('developer.userlogs.period')">
                            {{ __('userlog.users_stats') }}
                        </x-nav-link-responsive>
                    </p>
                    <p>
                        <x-nav-link-responsive href="{{ route('developer.userlogs.log') }}" :active="request()->routeIs('developer.userlogs.log')">
                            {{ __('userlog.users_log') }}
                        </x-nav-link-responsive>
                    </p>
                </div>

                <div>
                    <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('developer.backups') }}" :active="request()->routeIs('developer.backups')">
                            {{ __('Backups') }}
                        </x-nav-link-responsive>
                    </p>

                    <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ url('log-viewer') }}" target="_blank">
                            {{ __('Log Viewer') }}
                        </x-nav-link-responsive>
                    </p>
                </div>

                <div>
                    <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('developer.dependencies') }}" :active="request()->routeIs('developer.dependencies')">
                            {{ __('app.dependencies') }}
                        </x-nav-link-responsive>
                    </p>

                    <p>
                        <x-nav-link-responsive href="{{ route('developer.session') }}" :active="request()->routeIs('developer.session')">
                            {{ __('app.session') }}
                        </x-nav-link-responsive>
                    </p>
                </div>
            @else
                {{-- other --}}
                <div class="text-warning-500 dark:text-warning-200">{{ auth()->user()->teamRole(auth()->user()->currentTeam)->name }} ...</div>

                <div>
                    <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />

                    <p>
                        <x-nav-link-responsive href="{{ route('team') }}" :active="request()->routeIs('team')">{{ __('team.team') }}</x-nav-link-responsive>
                    </p>
                </div>
            @endif

            {{-- all --}}
            <div>
                <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />

                <p>
                    <x-nav-link-responsive href="{{ route('help') }}" :active="request()->routeIs('help')">{{ __('app.help') }}</x-nav-link-responsive>
                </p>
            </div>

            <div>
                <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />

                <p>
                    <x-nav-link-responsive href="{{ route('test') }}" :active="request()->routeIs('test')">Test</x-nav-link-responsive>
                </p>
            </div>
        @else
            {{-- guest --}}
            <div class="text-warning-500 dark:text-warning-200">{{ __('auth.guest') }} ...</div>

            <div>
                <div><x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" /></div>

                <p>
                    <x-nav-link-responsive href="{{ route('help') }}" :active="request()->routeIs('help')">
                        {{ __('app.help') }}
                    </x-nav-link-responsive>
                </p>
            </div>
        @endauth
    </div>

    <x-slot:footer end>
        <div class="flex items-center text-xs">
            <div class="px-2 text-right">
                {{ __('app.design_development') }}<br />
                {{ __('app.by') }} <x-link href="https://www.kreaweb.be/" target="_blank">KREAWEB</x-link>
            </div>

            <a href="https://www.kreaweb.be/" target="_blank" title="Kreaweb">
                <x-svg.kreaweb class="size-11 dark:fill-white hover:fill-primary-300 dark:hover:fill-primary-300" alt="kreaweb" />
            </a>
        </div>
    </x-slot:footer>
</x-ts-slide>
