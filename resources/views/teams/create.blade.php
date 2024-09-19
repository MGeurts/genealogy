@section('title')
    &vert; {{ __('team.create') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('team.create') }}
    </x-slot>

    <div class="w-full py-5 space-y-5">
        <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
            @livewire('teams.create-team-form')
        </div>
    </div>
</x-app-layout>
