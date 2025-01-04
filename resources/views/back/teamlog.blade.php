@section('title')
    &vert; {{ __('app.team_logbook') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.team_logbook') }}
    </x-slot>

    <div class="p-2 w-full">
        <livewire:teamlog />
    </div>
</x-app-layout>
