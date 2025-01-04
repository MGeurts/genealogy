@section('title')
    &vert; {{ __('api.api_tokens') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('api.api_tokens') }}
    </x-slot>

    <div class="w-full p-2 space-y-5">
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @livewire('api.api-token-manager')
        </div>
    </div>
</x-app-layout>
