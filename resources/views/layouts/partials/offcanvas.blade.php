<x-ts-slide id="offcanvas" left size="sm" blur>
    @php
        $user = auth()->user();
        $currentTeam = $user?->currentTeam;
        $role = $user?->teamRole($currentTeam);
        $permissions = $user?->teamPermissions($currentTeam);
    @endphp

    <x-slot:title>
        {{ __('app.menu') }}
    </x-slot:title>

    {{-- role and permissions --}}
    <div class="pb-4">
        <div class="p-4 text-base rounded-sm bg-secondary-100 text-secondary-800" role="alert">
            <div class="flex flex-row">
                <div class="basis-1/2">
                    {{ __('auth.role') }} :

                    <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded-sm max-md:mx-auto dark:bg-gray-700" />

                    {{ __('auth.permissions') }} :
                </div>

                <div class="basis-1/2">
                    @auth
                        {{ $role?->name ?? __('auth.guest') }}

                        <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded-sm max-md:mx-auto dark:bg-gray-700" />

                        @if (!empty($permissions))
                            @foreach ($permissions as $permission)
                                {{ $permission }}<br />
                            @endforeach
                        @else
                            <span class="text-gray-500 italic">{{ __('auth.no_permissions') }}</span>
                        @endif
                    @else
                        {{ __('auth.guest') }}

                        <x-hr.narrow class="w-full h-1 my-1 bg-gray-100 border-0 rounded-sm max-md:mx-auto dark:bg-gray-700" />
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- offcanvas menu --}}
    <div class="grow overflow-y-auto">
        @auth
            @if ($user?->is_developer)
                {{-- developer --}}
                <div class="text-yellow-500 dark:text-yellow-200">{{ __('auth.developer') }} ...</div>

                {{-- Primary Team Routes --}}
                <x-hr.narrow />
                <x-nav-link-responsive href="{{ route('team') }}" :active="request()->routeIs('team')">
                    {{ __('team.team') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('teamlog') }}" :active="request()->routeIs('teamlog')">
                    {{ __('app.team_logbook') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('peoplelog') }}" :active="request()->routeIs('peoplelog')">
                    {{ __('app.people_logbook') }}
                </x-nav-link-responsive>

                {{-- Developer Section --}}
                <x-hr.narrow />
                <x-nav-link-responsive href="{{ route('developer.teams') }}" :active="request()->routeIs('developer.teams')">
                    {{ __('team.teams') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('developer.people') }}" :active="request()->routeIs('developer.people')">
                    {{ __('person.people') }}
                </x-nav-link-responsive>

                <x-hr.narrow />
                <x-nav-link-responsive href="{{ route('developer.users') }}" :active="request()->routeIs('developer.users')">
                    {{ __('user.users') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('developer.userlog.origin') }}" :active="request()->routeIs('developer.userlog.origin')">
                    {{ __('userlog.users_origin') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('developer.userlog.origin-map') }}" :active="request()->routeIs('developer.userlog.origin-map')">
                    {{ __('userlog.users_origin') }} (Map)
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('developer.userlog.period') }}" :active="request()->routeIs('developer.userlog.period')">
                    {{ __('userlog.users_stats') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('developer.userlog.log') }}" :active="request()->routeIs('developer.userlog.log')">
                    {{ __('userlog.users_log') }}
                </x-nav-link-responsive>

                <x-hr.narrow />
                <x-nav-link-responsive href="{{ route('developer.settings') }}" :active="request()->routeIs('developer.settings')">
                    {{ __('app.settings') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('developer.backups') }}" :active="request()->routeIs('developer.backups')">
                    {{ __('backup.backups') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ url('log-viewer') }}" target="_blank">
                    {{ __('app.log_viewer') }}
                </x-nav-link-responsive>

                <x-hr.narrow />
                <x-nav-link-responsive href="{{ route('developer.dependencies') }}" :active="request()->routeIs('developer.dependencies')">
                    {{ __('app.dependencies') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('developer.session') }}" :active="request()->routeIs('developer.session')">
                    {{ __('app.session') }}
                </x-nav-link-responsive>

                <x-hr.narrow />
                <x-nav-link-responsive href="{{ route('test') }}" :active="request()->routeIs('test')">
                    Test
                </x-nav-link-responsive>
            @else
                {{-- other --}}
                <div class="text-yellow-500 dark:text-yellow-200">{{ $role?->name ?? __('auth.role_unknown') }} ...</div>

                <x-hr.narrow />
                <x-nav-link-responsive href="{{ route('team') }}" :active="request()->routeIs('team')">
                    {{ __('team.team') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('teamlog') }}" :active="request()->routeIs('teamlog')">
                    {{ __('app.team_logbook') }}
                </x-nav-link-responsive>
                <x-nav-link-responsive href="{{ route('peoplelog') }}" :active="request()->routeIs('peoplelog')">
                    {{ __('app.people_logbook') }}
                </x-nav-link-responsive>
            @endif

            {{-- common links --}}
            <x-hr.narrow />
            <x-nav-link-responsive href="{{ route('help') }}" :active="request()->routeIs('help')">
                {{ __('app.help') }}
            </x-nav-link-responsive>
        @else
            {{-- guest --}}
            <div class="text-yellow-500 dark:text-yellow-200">{{ __('auth.guest') }} ...</div>

            <x-hr.narrow />
            <x-nav-link-responsive href="{{ route('help') }}" :active="request()->routeIs('help')">
                {{ __('app.help') }}
            </x-nav-link-responsive>
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
