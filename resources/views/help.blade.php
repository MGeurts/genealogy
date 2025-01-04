@section('title')
    &vert; {{ __('app.help') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.help') }}
    </x-slot>

    <div class="w-full p-2 space-y-5">
        <div class="pb-10 dark:text-neutral-200">
            <div class="flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <x-authentication-card-logo />
                </div>

                <div class="w-full p-6 mt-6 overflow-hidden prose bg-white rounded shadow-md sm:max-w-5xl">
                    {!! $help !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
