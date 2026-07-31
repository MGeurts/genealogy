@section('title')
    &vert; {{ __('app.home') }}
@endsection

<x-app-layout>
    <div class="w-full space-y-5 p-2">
        <div class="pb-10 dark:text-neutral-200">
            <div class="flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <x-authentication-card-logo />
                </div>

                <div class="prose mt-6 w-full overflow-hidden rounded-sm bg-white p-6 shadow-md sm:max-w-5xl">
                    {!! $home !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
