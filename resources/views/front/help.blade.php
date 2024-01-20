@section('title')
    &vert; {{ __('app.help') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.help') }}
        </h2>
    </x-slot>

    <div class="py-10 dark:text-neutral-200">
        <div class="flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-5xl mt-6 p-6 bg-white shadow-md overflow-hidden rounded prose">
                <h1>Help</h1>

                <!-- concept -->
                <h2>1. Concept</h2>
                <x-hr.narrow />

                <p></p>
                <br>

                <!-- models -->
                <h2>2. Models &amp; relationships</h2>

                <x-hr.narrow />
                <h3>a. People</h3>
                <h3>b. Couples</h3>
                <p></p>
                <br>


                <!-- teams -->
                <h2>3. Authentification, multi-tenancy and data accessibility</h2>
                <x-hr.narrow />

                <h3>a. Users</h3>
                <p>
                    Users can <b>register</b> themselves. At least a surname, a valid e-mail address and a password is needed.
                </p>

                <p>
                    After registration and optional e-mail verification, users can <b>login</b> and manage their own user profile and teams settings by using the dropdown menus in the top right-hand
                    corner of the menu bar. Authenticated users, without invitation, by default belong to (and own) their own <b>personal team</b>.
                </p>

                <div class="col-span-6 text-sm rounded bg-info-200 p-3 text-info-700" role="alert">
                    Tip : <b>Two Factor Authentification</b> (2FA) and <b>E-mail Verification</b> are enabled by default but can be configured in <b>config/fortify.php</b>.
                </div>

                <h3>b. Teams</h3>
                <p>This application uses <a href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream 4</a> with the <a href="https://jetstream.laravel.com/features/teams.html"
                        target="_blank">Teams</a> option to implement and enforce <a href="https://en.wikipedia.org/wiki/Multitenancy" target="_blank">multi-tenancy</a>. This ensures that
                    registered users can see and manage <span class="text-danger"><b>only the people and couples they own</b></span>.

                <ul>
                    <li>people created while the users personal team is active</li>
                    <li>people created while the a global team is active</li>
                </ul>
                </p>

                <p>Users can manage either their personal team or create new teams.
                    Users the can invite other (new or already registered) users (by e-mail) to join.</p>
                <img src="https://genealogy.kreaweb.be/img/help/genealogy-001.webp" class="rounded" alt="Manage Teams">
                <br>

            </div>
        </div>
    </div>
</x-app-layout>
