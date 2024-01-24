@section('title')
    &vert; {{ __('app.dependencies') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.dependencies') }}
        </h2>
    </x-slot>

    <div class="grow max-w-5xl overflow-x-auto py-5 dark:text-neutral-200">
        {{-- card --}}
        <div class="block rounded bg-white p-2 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700">

            {{-- accordion --}}
            <div id="accordionFlushDependencies">
                <!-- Laravel -->
                <div class="rounded-none border border-l-0 border-r-0 border-t-0 border-neutral-200 bg-white dark:border-neutral-600 dark:bg-neutral-800">
                    <h2 class="mb-0" id="flush-heading-1">
                        <button type="button" data-te-collapse-init data-te-target="#flush-collapse-1" aria-expanded="false" aria-controls="flush-collapse-1"
                            class="group relative flex w-full items-center rounded-none border-0 bg-white px-5 py-2 text-left text-base text-neutral-800 transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-neutral-800 dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(229,231,235)] dark:[&:not([data-te-collapse-collapsed])]:bg-neutral-800 dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(75,85,99)]">
                            1. Laravel
                            <span
                                class="-mr-1 ml-auto h-5 w-5 shrink-0 rotate-[-180deg] fill-[#336dec] transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:mr-0 group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:fill-[#212529] motion-reduce:transition-none dark:fill-blue-300 dark:group-[[data-te-collapse-collapsed]]:fill-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </button>
                    </h2>

                    <div id="flush-collapse-1" class="!visible border-0" data-te-collapse-item data-te-collapse-show aria-labelledby="flush-heading-1" data-te-parent="#accordionFlushDependencies">
                        <div class="px-5 py-2 dark:bg-neutral-700">
                            <ul class="w-full ml-4">
                                <li class="py-2">
                                    <x-link href="https://laravel.com/" target="_blank">Laravel</x-link> 10.x
                                </li>
                                <li class="py-2">
                                    <x-link href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream</x-link> (with Teams) 4.x
                                </li>
                                <li class="py-2">
                                    <x-link href="https://livewire.laravel.com/" target="_blank">Laravel Livewire</x-link> 3.x
                                </li>
                                <li class="py-2">
                                    <x-link href="https://filamentphp.com/" target="_blank">Laravel Filament</x-link> (only Table Builder) 3.x
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tailwind -->
                <div class="rounded-none border border-l-0 border-r-0 border-t-0 border-neutral-200 bg-white dark:border-neutral-600 dark:bg-neutral-800">
                    <h2 class="mb-0" id="flush-heading-2">
                        <button type="button" data-te-collapse-init data-te-collapse-collapsed data-te-target="#flush-collapse-2" aria-expanded="false" aria-controls="flush-collapse-2"
                            class="group relative flex w-full items-center rounded-none border-0 bg-white px-5 py-2 text-left text-base text-neutral-800 transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-neutral-800 dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(229,231,235)] dark:[&:not([data-te-collapse-collapsed])]:bg-neutral-800 dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(75,85,99)]">
                            2. Tailwind
                            <span
                                class="-mr-1 ml-auto h-5 w-5 shrink-0 rotate-[-180deg] fill-[#336dec] transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:mr-0 group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:fill-[#212529] motion-reduce:transition-none dark:fill-blue-300 dark:group-[[data-te-collapse-collapsed]]:fill-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </button>
                    </h2>

                    <div id="flush-collapse-2" class="!visible hidden border-0" data-te-collapse-item aria-labelledby="flush-heading-2" data-te-parent="#accordionFlushDependencies">
                        <div class="px-5 py-2 dark:bg-neutral-700">
                            <ul class="w-full ml-4">
                                <li class="py-2">
                                    <x-link href="https://tailwindcss.com/docs/" target="_blank">Tailwind CSS</x-link>
                                </li>
                                <li class="py-2">
                                    <x-link href="https://tw-elements.com/" target="_blank">Tailwind Elements</x-link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Github -->
                <div class="rounded-none border border-l-0 border-r-0 border-t-0 border-neutral-200 bg-white dark:border-neutral-600 dark:bg-neutral-800">
                    <h2 class="mb-0" id="flush-heading-3">
                        <button type="button" data-te-collapse-init data-te-collapse-collapsed data-te-target="#flush-collapse-3" aria-expanded="false" aria-controls="flush-collapse-3"
                            class="group relative flex w-full items-center rounded-none border-0 bg-white px-5 py-2 text-left text-base text-neutral-800 transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-neutral-800 dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(229,231,235)] dark:[&:not([data-te-collapse-collapsed])]:bg-neutral-800 dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(75,85,99)]">
                            3. Github
                            <span
                                class="-mr-1 ml-auto h-5 w-5 shrink-0 rotate-[-180deg] fill-[#336dec] transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:mr-0 group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:fill-[#212529] motion-reduce:transition-none dark:fill-blue-300 dark:group-[[data-te-collapse-collapsed]]:fill-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </button>
                    </h2>

                    <div id="flush-collapse-3" class="!visible hidden" data-te-collapse-item aria-labelledby="flush-heading-3" data-te-parent="#accordionFlushDependencies">
                        <div class="px-5 py-2 dark:bg-neutral-700">
                            <ul class="w-full ml-4">
                                <li class="py-2">
                                    <x-link href="https://github.com/spatie/laravel-backup/" target="_blank">spatie/laravel-backup</x-link>
                                </li>
                                <li class="py-2">
                                    <x-link href="https://github.com/ARCANEDEV/LogViewer/" target="_blank">ARCANEDEV/LogViewer</x-link>
                                </li>

                                <li class="py-2">
                                    <x-link href="https://github.com/csstools/postcss-plugins/" target="_blank">csstools/postcss-plugins</x-link> (needed by Filament Table Builder)
                                </li>

                                <li class="py-2">
                                    <x-link href="https://github.com/victorybiz/laravel-simple-select/" target="_blank">victorybiz/laravel-simple-select</x-link>
                                </li>
                                <li class="py-2">
                                    <x-link href="https://github.com/usernotnull/tall-toasts/" target="_blank">usernotnull/tall-toasts</x-link>
                                </li>
                                <li class="py-2">
                                    <x-link href="https://github.com/Intervention/image/" target="_blank">intervention/image</x-link>
                                </li>
                                <li class="py-2">
                                    <x-link href="https://github.com/korridor/laravel-has-many-merged/" target="_blank">korridor/laravel-has-many-merged</x-link>
                                </li>
                                <li class="py-2">
                                    <x-link href="https://github.com/kevinkhill/lavacharts/" target="_blank">kevinkhill/lavacharts</x-link>
                                </li>
                                <li class="py-2">
                                    <x-link href="https://github.com/stevebauman/location/" target="_blank">stevebauman/location</x-link>
                                </li>

                                <x-hr.narrow class="w-full h-1 max-md:mx-auto my-4 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                                <li class="py-2">
                                    <x-link href="https://github.com/opcodesio/log-viewer/" target="_blank">opcodesio/log-viewer</x-link> (candidate : alternative Log Viewer)
                                </li>
                                <li class="py-2">
                                    <x-link href="https://github.com/stefangabos/world_countries/" target="_blank">stefangabos/world_countries</x-link> (candidate)
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- JS -->
                <div class="rounded-none border border-l-0 border-r-0 border-t-0 border-neutral-200 bg-white dark:border-neutral-600 dark:bg-neutral-800">
                    <h2 class="mb-0" id="flush-heading-4">
                        <button type="button" data-te-collapse-init data-te-collapse-collapsed data-te-target="#flush-collapse-4" aria-expanded="false" aria-controls="flush-collapse-4"
                            class="group relative flex w-full items-center rounded-none border-0 bg-white px-5 py-2 text-left text-base text-neutral-800 transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-neutral-800 dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(229,231,235)] dark:[&:not([data-te-collapse-collapsed])]:bg-neutral-800 dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(75,85,99)]">
                            4. Javascript
                            <span
                                class="-mr-1 ml-auto h-5 w-5 shrink-0 rotate-[-180deg] fill-[#336dec] transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:mr-0 group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:fill-[#212529] motion-reduce:transition-none dark:fill-blue-300 dark:group-[[data-te-collapse-collapsed]]:fill-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </button>
                    </h2>

                    <div id="flush-collapse-4" class="!visible hidden" data-te-collapse-item aria-labelledby="flush-heading-4" data-te-parent="#accordionFlushDependencies">
                        <div class="px-5 py-2 dark:bg-neutral-700">
                            <ul class="w-full ml-4">
                                <li class="py-2">
                                    <x-link href="https://www.chartjs.org/" target="_blank">Chart.js</x-link>
                                </li>
                                <li class="py-2">
                                    <x-link href="https://github.com/rstacruz/nprogress/" target="_blank">NProgress.js</x-link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- 3rd Party -->
                <div class="rounded-none border border-b-0 border-l-0 border-r-0 border-t-0 border-neutral-200 bg-white dark:border-neutral-600 dark:bg-neutral-800">
                    <h2 class="mb-0" id="flush-heading-5">
                        <button type="button" data-te-collapse-init data-te-collapse-collapsed data-te-target="#flush-collapse-5" aria-expanded="false" aria-controls="flush-collapse-5"
                            class="group relative flex w-full items-center rounded-none border-0 bg-white px-5 py-2 text-left text-base text-neutral-800 transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-neutral-800 dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(229,231,235)] dark:[&:not([data-te-collapse-collapsed])]:bg-neutral-800 dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:[box-shadow:inset_0_-1px_0_rgba(75,85,99)]">
                            5. 3rd Party
                            <span
                                class="-mr-1 ml-auto h-5 w-5 shrink-0 rotate-[-180deg] fill-[#336dec] transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:mr-0 group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:fill-[#212529] motion-reduce:transition-none dark:fill-blue-300 dark:group-[[data-te-collapse-collapsed]]:fill-white">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </span>
                        </button>
                    </h2>

                    <div id="flush-collapse-5" class="!visible hidden" data-te-collapse-item aria-labelledby="flush-heading-5" data-te-parent="#accordionFlushDependencies">
                        <div class="px-5 py-2 dark:bg-neutral-700">
                            <ul class="w-full ml-4">
                                <li class="py-2">
                                    <x-link href="https://tabler-icons.io/" target="_blank">Tabler Icons (2.46.0)</x-link> - Download and place
                                    <x-link href="https://github.com/tabler/tabler-icons/blob/master/packages/icons/tabler-sprite-nostroke.svg" target="_blank">tabler-sprite-nostroke.svg</x-link> in
                                    \public\tabler\
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
