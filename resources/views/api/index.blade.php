@section('title')
    &vert; {{ __('api.api_tokens') }}
@endsection

<x-app-layout>
    <div class="w-full space-y-5 p-2">
        <div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
            @livewire('api.api-token-manager')
        </div>
    </div>
</x-app-layout>
