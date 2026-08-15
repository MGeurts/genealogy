@section('title')
    &vert; {{ __('team.create') }}
@endsection

<x-app-layout>
    <div class="w-full space-y-5 p-2">
        <div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
            @livewire('teams.create-team-form')
        </div>
    </div>
</x-app-layout>
