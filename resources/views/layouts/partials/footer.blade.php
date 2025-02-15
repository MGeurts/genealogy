<footer class="text-center print:hidden text-neutral-600 dark:text-neutral-200 lg:text-left">
    <!-- Top Section: Social Media Links -->
    <div class="flex items-center justify-center p-2 border-b-2 border-neutral-200 dark:border-neutral-500 lg:justify-between bg-neutral-200 dark:bg-neutral-700">
        <!-- Social Media Header (Visible on Large Screens) -->
        <div class="hidden mr-12 lg:block">
            <span>{{ __('app.connected_social') }}:</span>
        </div>

        <!-- Social Media Icons -->
        <div class="flex justify-center">
            <a href="https://www.facebook.com/" class="mr-6" target="_blank" aria-label="Visit Facebook" title="Facebook">
                <x-ts-icon icon="tabler.brand-facebook" class="text-neutral-900 dark:text-neutral-200" />
            </a>
            <a href="https://twitter.com/Kreaweb_be" class="mr-6" target="_blank" aria-label="Visit X (formerly Twitter)" title="X">
                <x-ts-icon icon="tabler.brand-x" class="text-neutral-900 dark:text-neutral-200" />
            </a>
            <a href="https://www.instagram.com/" class="mr-6" target="_blank" aria-label="Visit Instagram" title="Instagram">
                <x-ts-icon icon="tabler.brand-instagram" class="text-neutral-900 dark:text-neutral-200" />
            </a>
            <a href="https://www.linkedin.com/" class="mr-6" target="_blank" aria-label="Visit LinkedIn" title="LinkedIn">
                <x-ts-icon icon="tabler.brand-linkedin" class="text-neutral-900 dark:text-neutral-200" />
            </a>
            <a href="https://www.youtube.com/channel/UClUVszEUeb-nY7qM00ERCHg" class="mr-6" target="_blank" aria-label="Visit YouTube" title="YouTube">
                <x-ts-icon icon="tabler.brand-youtube" class="text-neutral-900 dark:text-neutral-200" />
            </a>
            <a href="https://github.com/MGeurts" class="" target="_blank" aria-label="Visit GitHub" title="GitHub">
                <x-ts-icon icon="tabler.brand-github" class="text-neutral-900 dark:text-neutral-200" />
            </a>
        </div>
    </div>

    <!-- Middle Section: Main Content -->
    <div class="p-2 text-center md:text-left bg-neutral-100 dark:bg-neutral-600">
        <div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
            <!-- Logo Section -->
            <div class="flex justify-center md:justify-start">
                <a href="{{ route('home') }}" aria-label="Go to Home" title="Home">
                    <x-svg.genealogy class="size-48 fill-dark dark:fill-neutral-400 hover:fill-primary-300 dark:hover:fill-primary-300" alt="Genealogy Logo" />
                </a>
            </div>

            <!-- Useful Links Section -->
            <div>
                <h6 class="flex justify-center mb-4 font-semibold uppercase md:justify-start">{{ __('app.useful_links') }}</h6>
                <x-hr.narrow class="w-48 h-1 my-4 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />
                <p class="mb-4">
                    <x-nav-link-footer href="{{ route('about') }}" :active="request()->routeIs('about')">
                        {{ __('app.about') }}
                    </x-nav-link-footer>
                </p>
                <p class="mb-4">
                    <x-nav-link-footer href="{{ route('help') }}" :active="request()->routeIs('help')">
                        {{ __('app.help') }}
                    </x-nav-link-footer>
                </p>
            </div>

            <!-- Impressum Section -->
            <div>
                <h6 class="flex justify-center mb-4 font-semibold uppercase md:justify-start">{{ __('app.impressum') }}</h6>
                <x-hr.narrow class="w-48 h-1 my-4 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />
                <p class="mb-4">
                    <x-nav-link-footer href="{{ url('terms-of-service') }}" :active="request()->is('terms-of-service')">
                        {{ __('app.terms_of_service') }}
                    </x-nav-link-footer>
                </p>
                <p class="mb-4">
                    <x-nav-link-footer href="{{ url('privacy-policy') }}" :active="request()->is('privacy-policy')">
                        {{ __('app.privacy_policy') }}
                    </x-nav-link-footer>
                </p>
            </div>

            <!-- Contact Section -->
            <div>
                <h6 class="flex justify-center mb-4 font-semibold uppercase md:justify-start">{{ __('app.contact') }}</h6>
                <x-hr.narrow class="w-48 h-1 my-4 bg-gray-100 border-0 rounded max-md:mx-auto dark:bg-gray-700" />
                <p class="flex items-center justify-center mb-4 md:justify-start">
                    <x-ts-icon icon="tabler.home" class="mr-3 inline-block size-5" />
                    New York, NY 10012, US
                </p>
                <p class="flex items-center justify-center mb-4 md:justify-start">
                    <x-ts-icon icon="tabler.mail" class="mr-3 inline-block size-5" />
                    info@example.com
                </p>
                <p class="flex items-center justify-center mb-4 md:justify-start">
                    <x-ts-icon icon="tabler.phone" class="mr-3 inline-block size-5" />
                    + 01 234 567 88
                </p>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Copyright -->
    @include('layouts.partials.copyright')
</footer>
