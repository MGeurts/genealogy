@section('title')
    &vert; {{ __('team.settings') }}
@endsection

<x-app-layout>
    <div class="w-full space-y-5 p-2">
        <div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
            @livewire('teams.update-team-name-form', ['team' => $team])

            @livewire('teams.team-member-manager', ['team' => $team])

            @if (auth()->user()->ownsTeam($team) and ! $team->personal_team)
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @include('teams.transfer-ownership')
                </div>
            @endif

            @if (Gate::check('delete', $team) and ! $team->personal_team)
                <x-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('teams.delete-team-form', ['team' => $team])
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
