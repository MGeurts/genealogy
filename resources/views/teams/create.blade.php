@section('title')
    &vert; {{ __('team.create') }}
@endsection

<x-app-layout>
    <div class="w-full p-2 space-y-5">
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @livewire('teams.create-team-form')
        </div>
    </div>
</x-app-layout>
