@section('title')
    &vert; {{ $exception->getStatuscode() }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ $exception->getStatuscode() }}
        </h2>
    </x-slot>

    <div class="grow max-w-5xl overflow-x-auto py-5 dark:text-neutral-200">
        <x-ts-alert icon="bug" color="cyan">
            <x-slot:title>
                {{ $exception->getStatuscode() }}
            </x-slot:title>

            <div class="my-10">
                {{ $exception->getMessage() }}
            </div>

            <x-slot:footer>
                <div class="flex justify-end">
                    <x-ts-button href="/" color="slate" class="text-sm">
                        <x-ts-icon icon="home" class="size-5 mr-1" />
                        {{ __('app.home') }}
                    </x-ts-button>
                </div>
            </x-slot:footer>
        </x-ts-alert>
    </div>
</x-app-layout>
