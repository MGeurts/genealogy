@section('title')
    &vert; {{ __('app.privacy_policy') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.privacy_policy') }}
        </h2>
    </x-slot>

    <div class="w-full py-5 space-y-5">
        <div class="pb-10 dark:text-neutral-200">
            <div class="flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <x-authentication-card-logo />
                </div>

                <div class="w-full sm:max-w-5xl mt-6 p-6 bg-white shadow-md overflow-hidden rounded prose">
                    {!! $policy !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
