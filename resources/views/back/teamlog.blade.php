@section('title')
    &vert; {{ __('app.team_logbook') }}
@endsection

<x-app-layout>
    <div class="p-2 w-full">
        <livewire:teamlog />
    </div>
</x-app-layout>
