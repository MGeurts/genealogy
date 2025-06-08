@section('title')
    &vert; {{ $exception->getStatuscode() }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ $exception->getStatuscode() }}
    </x-slot>

    <div class="p-5 max-w-5xl overflow-x-auto grow">
        <div class="flex">
            <x-ts-alert icon="tabler.bug" ext="Neutral" color="neutral">
                <x-slot:title>
                    {{ $exception->getStatuscode() }}
                </x-slot:title>

                <div class="my-10">
                    {{ $exception->getMessage() ? $exception->getMessage() : __('app.unauthorized_access') }}
                </div>

                <x-slot:footer>
                    <div class="flex justify-end">
                        <x-ts-button href="/" color="primary">
                            <x-ts-icon icon="tabler.home" class="size-5" />
                            {{ __('app.home') }}
                        </x-ts-button>
                    </div>
                </x-slot:footer>
            </x-ts-alert>
        </div>
    </div>
</x-app-layout>
