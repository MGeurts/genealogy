<div class="flex space-x-2">
    <div>
        <div class="invisible fixed bottom-0 left-0 top-0 z-[1045] flex w-96 max-w-full -translate-x-full flex-col border-none bg-white bg-clip-padding text-neutral-700 shadow-sm outline-none transition duration-300 ease-in-out dark:bg-neutral-800 dark:text-neutral-200 [&[data-te-offcanvas-show]]:transform-none"
            tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel" data-te-offcanvas-init>
            <div class="flex items-center justify-between p-4">
                <h5 class="mb-0 font-semibold leading-normal" id="offcanvasLabel">
                    {{ __('app.menu') }}
                </h5>
                <button type="button" class="box-content rounded-none border-none opacity-50 hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                    data-te-offcanvas-dismiss>
                    <span
                        class="w-[1em] focus:opacity-100 disabled:pointer-events-none disabled:select-none disabled:opacity-25 [&.disabled]:pointer-events-none [&.disabled]:select-none [&.disabled]:opacity-25">
                        <x-icon.tabler icon="x" />
                    </span>
                </button>
            </div>

            <!-- Role -->
            <div class="p-4">
                <div class="rounded bg-secondary-100 p-4 text-base text-secondary-800" role="alert">
                    <div class="flex flex-row">
                        <div class="basis-1/3">{{ __('auth.role') }} :</div>
                        <div class="basis-2/3">
                            @if (Auth::user())
                                {{ Auth::user()->teamRole(auth()->user()->currentTeam)->name }}

                                <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />
                                <div class="text-sm">
                                    @foreach (Auth::user()->teamPermissions(Auth::user()->currentTeam) as $permission)
                                        {{ $permission }}<br />
                                    @endforeach
                                </div>
                            @else
                                {{ __('auth.guest') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- offcanvas menu -->
            <div class="flex-grow overflow-y-auto p-4">
                @if (Auth::user())
                    @if (Auth::user()->hasTeamRole(auth()->user()->currentTeam, 'administrator'))
                        <!-- administrator -->
                        <div>{{ __('auth.administrator') }} ...</div>

                        <div>
                            <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                            <p>
                                <x-nav-link-responsive wire:navigate href="{{ route('backups') }}" :active="request()->routeIs('backups')">
                                    {{ __('Backups') }}
                                </x-nav-link-responsive>
                            </p>

                            <p>
                                <x-nav-link-responsive href="{{ url('log-viewer') }}" target="_blank">
                                    {{ __('Log Viewer') }}
                                </x-nav-link-responsive>
                            </p>
                        </div>

                        <div>
                            <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                            <p>
                                <x-nav-link-responsive wire:navigate href="{{ route('userlogs.log') }}" :active="request()->routeIs('userlogs.log')">
                                    {{ __('userlog.users_log') }}
                                </x-nav-link-responsive>
                            </p>

                            <p>
                                <x-nav-link-responsive wire:navigate href="{{ route('userlogs.origin') }}" :active="request()->routeIs('userlogs.origin')">
                                    {{ __('userlog.users_origin') }}
                                </x-nav-link-responsive>
                            </p>

                            <p>
                                <x-nav-link-responsive wire:navigate href="{{ route('userlogs.origin-map') }}" :active="request()->routeIs('userlogs.origin-map')">
                                    {{ __('userlog.users_origin') }} (Map)
                                </x-nav-link-responsive>
                            </p>

                            <p>
                                <x-nav-link-responsive wire:navigate href="{{ route('userlogs.period') }}" :active="request()->routeIs('userlogs.period')">
                                    {{ __('userlog.users_stats') }}
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
                                <x-nav-link-responsive wire:navigate href="{{ route('session') }}" :active="request()->routeIs('session')">
                                    {{ __('app.session') }}
                                </x-nav-link-responsive>
                            </p>
                        </div>

                        <div>
                            <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                            <p>
                                <x-nav-link-responsive wire:navigate href="{{ route('test') }}" :active="request()->routeIs('test')">
                                    -- Test Page --
                                </x-nav-link-responsive>
                            </p>
                        </div>
                    @else
                        <!-- others -->
                        <div>{{ Auth::user()->teamRole(auth()->user()->currentTeam)->name }} ...</div>

                        <div>
                            <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />
                        </div>
                    @endif
                @endif

                @guest
                    <!-- guest -->
                    <div>{{ __('auth.guest') }} ...</div>

                    <div>
                        <x-hr.narrow class="w-full h-1 max-md:mx-auto my-1 bg-gray-100 border-0 rounded dark:bg-gray-700" />
                    </div>
                @endguest
            </div>
        </div>
    </div>
</div>
