@section('title')
    &vert; {{ __('app.terms_of_service') }}
@endsection

<x-guest-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.terms_of_service') }}
        </h2>
    </x-slot>

    <div class="pt-4 bg-gray-100">
        <div class="flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded prose">
                {!! $terms !!}
            </div>
        </div>
    </div>
</x-guest-layout>
