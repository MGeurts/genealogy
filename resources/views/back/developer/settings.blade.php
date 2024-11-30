@section('title')
    &vert; {{ __('app.settings') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.settings') }}
    </x-slot>

    <div class="py-5 w-full">
        <livewire:developer.settings />
    </div>
</x-app-layout>
