@section('title')
    &vert; {{ __('app.session') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.session') }}
        </h2>
    </x-slot>

    <div class="grow max-w-5xl overflow-x-auto py-5 dark:text-neutral-200">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            {{-- card header --}}
            <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg dark:border-neutral-600 dark:text-neutral-50 rounded-t">
                <div class="flex flex-wrap gap-2 justify-center items-start">
                    <div class="flex-grow min-w-max max-w-full flex-1">{{ __('app.session') }} object</div>

                    <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                        <x-ts-icon icon="code" class="inline-block" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="p-5 overflow-x-auto">
                <pre>{{ print_r(session()->all(), true) }}</pre>
            </div>
        </div>
    </div>
</x-app-layout>
