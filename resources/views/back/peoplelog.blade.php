@section('title')
    &vert; {{ __('app.people_logbook') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.people_logbook') }}
    </x-slot>

    <div class="py-5 w-full">
        <livewire:peoplelog />
    </div>
</x-app-layout>
