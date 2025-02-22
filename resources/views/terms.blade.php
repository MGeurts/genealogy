@section('title')
    &vert; {{ __('app.terms_of_service') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.terms_of_service') }}
    </x-slot>

    <div class="w-full p-2 space-y-5">
        <div class="pb-10 dark:text-neutral-200">
            <div class="flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <x-authentication-card-logo />
                </div>

                <div class="w-full p-6 mt-6 overflow-hidden prose bg-white rounded-sm shadow-md sm:max-w-5xl">
                    {!! $terms !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
