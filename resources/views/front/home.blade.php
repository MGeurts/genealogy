<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.home') }}
        </h2>
    </x-slot>

    <div class="py-10 dark:text-neutral-200">
        <div class="flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-5xl mt-6 p-6 bg-white shadow-md overflow-hidden rounded prose">
                <h1>Welcome ...</h1>
                <x-hr.narrow class="w-full h-1 max-md:mx-auto my-4 bg-gray-100 border-0 rounded dark:bg-gray-700" />

                <h2><b>Genealogy</b> (family tree) application to record family members.</h2>
                <img src="https://genealogy.kreaweb.be/img/genealogy-001a.webp" class="rounded" alt="Genealogy-001a">
                <img src="https://genealogy.kreaweb.be/img/genealogy-001b.webp" class="rounded" alt="Genealogy-001b">
                <p>This application is build using :</p>
                <ul>
                    <li><a href="https://laravel.com/" target="_blank">Laravel 10</a> (featuring <a href="https://vitejs.dev/" target="_blank">Vite</a>)</li>
                    <li><a href="https://jetstream.laravel.com/" target="_blank">Laravel Jetstream 4</a> (featuring <a href="https://jetstream.laravel.com/features/teams.html"
                            target="_blank">Teams</a>)</li>
                    <li><a href="https://livewire.laravel.com/" target="_blank">Livewire 3</a></li>
                    <li><a href="https://tailwindcss.com/" target="_blank">Tailwind CSS</a></li>
                    <li><a href="https://tw-elements.com/" target="_blank">Tailwind Elements</a></li>
                    <li><a href="https://tabler-icons.io/" target="_blank">Tabler Icons</a></li>
                </ul>
                <h3>Logic Concept</h3>
                <ol>
                    <li>
                        <p>A person can have 1 biological father (1 person, based on <b>father_id</b>)</p>
                    </li>
                    <li>
                        <p>A person can have 1 biological mother (1 person, based on <b>mother_id</b>)</p>
                    </li>
                    <li>
                        <p>A person can have 1 set of parents, biological or not (1 couple of 2 people, based on <b>parents_id</b>)</p>
                    </li>
                    <li>
                        <p>A person can have 0 to many biological children (n people, based on father_id/mother_id)</p>
                    </li>
                    <li>
                        <p>A person can have 0 to many partners (n people), being part of 0 to many couples (opposite or same biological sex)</p>
                    </li>
                    <li>
                        <p>A person can be part of a couple with the same partner multiple times (remarriage or reunite)</p>
                    </li>
                    <li>
                        <p>A couple can have 0 to many children (based on parents_id as a couple or father_id/mother_id individually)</p>
                    </li>
                    <li>
                        <p>A couple can be married or not, still together or separated in the meantime</p>
                    </li>
                </ol>
                <h3>Requirements</h3>
                <ul>
                    <li>At least <a href="https://www.php.net/" target="_blank">PHP 8.1</a></li>
                </ul>
                <h3>License</h3>
                <p>Open source under MIT License.</p>
                <h2>Demo</h2>

                <div class="mb-4 rounded-lg bg-info-100 px-6 py-5 text-base text-info-800" role="alert">
                    <a href="https://genealogy.kreaweb.be/" target="_blank">https://genealogy.kreaweb.be/</a>
                    <br /><br />
                    e-mail : <b>administrator@genealogy.test</b><br />
                    password : <b>password</b>
                </div>

                <h2>Features</h2>

                <ul>
                    <li>Light/Dark/System theme</li>
                    <li>Fully responsive</li>
                    <li>Multi-language, English and Dutch already implemented, language setting saved in authenticated users profile</li>
                    <li>Multi-tenancy by Jetstream Teams</li>
                    <li>Security through Jetstream Teams Roles &amp; Permissions, 2FA &amp; API can be enabled</li>
                    <li>Offcanvas menu</li>
                    <li>Image upload with possibility of watermarking</li>
                </ul>
                <h3>Special features</h3>
                <p>This application has a built-in <b>Backup Manager</b> :</p>
                <ul>
                    <li>Backups can be initiated and managed manually</li>
                    <li>Backups can be scheludeld by issuing a cron job on your development or production server</li>
                    <li>An e-mail will be send after each backup</li>
                </ul>
                <p></p>
                <p>This application has a built-in <b>Log Viewer</b> showing :
                </p>
                <ul>
                    <li>INFO : All scheduled backups</li>
                    <li>DEBUG : All executed database queries, but only in local development mode</li>
                    <li>WARNING : All detected N+1 queries, but only in local development mode</li>
                    <li>ERROR : All detected errors</li>
                </ul>
                <p></p>
                <p>This application has a built-in <b>User logging</b> :</p>
                <ul>
                    <li>User statistics by country of origin</li>
                    <li>User statistics by year/month/week/day</li>
                </ul>
                <p></p>
                <h3>To do ...</h3>
                <ul>
                    <li>Allow uploading extra images for people</li>
                    <li>Write documentation</li>
                    <li>GEDCOM import and export (help needed)</li>
                </ul>
                <h2>Basic idea (2017)</h2>
                <p>
                    This application is based on an original implementation by <a href="https://github.com/nafiesl/silsilah" target="_blank">nafiesl</a>.<br />Thanks for your excellent work.
                </p>
                <h2>Design &amp; Development (2024)</h2>
                <p>This Laravel application is designed and developed by <a href="https://www.kreaweb.be" target="_blank">kreaweb.be</a>.</p>
            </div>
        </div>
    </div>
</x-app-layout>
