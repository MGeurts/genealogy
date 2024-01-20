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

                <h2>1. Concept</h2>
                <x-hr.narrow />
                <p></p>
                <br />

                <h2>2. Models & relationships</h2>
                <x-hr.narrow />
                <h3>a. People</h3>
                <h3>b. Couples</h3>
                <p></p>
                <br />

                <h2>3. Teams : Multi-tenancy and security</h2>
                <x-hr.narrow />
                <h3>a. Users</h3>
                <h3>b. Teams</h3>
                <p></p>
                <br />

            </div>
        </div>
    </div>
</x-app-layout>
