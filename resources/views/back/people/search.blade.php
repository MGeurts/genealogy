@section('title')
    &vert; {{ __('app.search') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.search') }}
    </x-slot>

    <livewire:people.search />
</x-app-layout>
