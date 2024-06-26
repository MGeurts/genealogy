@section('title')
    &vert; {{ __('api.api_tokens') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('api.api_tokens') }}
    </x-slot>

    <div class="w-full py-5 space-y-5">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('api.api-token-manager')
        </div>
    </div>
</x-app-layout>
