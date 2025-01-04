@section('title')
    &vert; {{ $exception->getStatuscode() }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ $exception->getStatuscode() }}
    </x-slot>

    <div class="p-2 max-w-5xl overflow-x-auto grow dark:text-neutral-200">
        <div class="hidden dark:flex">
            <x-ts-alert icon="bug" color="white">
                <x-slot:title>
                    {{ $exception->getStatuscode() }}
                </x-slot:title>

                <div class="my-10">
                    {{ $exception->getMessage() }}
                </div>

                <x-slot:footer>
                    <div class="flex justify-end">
                        <x-ts-button href="/" color="slate" class="text-sm">
                            <x-ts-icon icon="home" class="size-5" />
                            {{ __('app.home') }}
                        </x-ts-button>
                    </div>
                </x-slot:footer>
            </x-ts-alert>
        </div>

        <div class="flex dark:hidden">
            <x-ts-alert icon="bug" color="black">
                <x-slot:title>
                    {{ $exception->getStatuscode() }}
                </x-slot:title>

                <div class="my-10">
                    {{ $exception->getMessage() }}
                </div>

                <x-slot:footer>
                    <div class="flex justify-end">
                        <x-ts-button href="/" color="slate" class="text-sm">
                            <x-ts-icon icon="home" class="size-5" />
                            {{ __('app.home') }}
                        </x-ts-button>
                    </div>
                </x-slot:footer>
            </x-ts-alert>
        </div>
    </div>
</x-app-layout>
