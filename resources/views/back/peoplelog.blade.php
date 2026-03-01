@section('title')
    &vert; {{ __('app.people_logbook') }}
@endsection

<x-app-layout>
    <div class="p-2 w-full">
        <livewire:peoplelog />
    </div>
</x-app-layout>
