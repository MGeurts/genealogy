@props(['team', 'component' => 'dropdown-link'])

<form method="POST" action="{{ route('current-team.update') }}" x-data>
    @method('PUT')
    @csrf

    <!-- Hidden Team ID -->
    <input type="hidden" name="team_id" value="{{ $team->id }}">

    <x-dynamic-component :component="$component" href="#" x-on:click.prevent="$root.submit();">
        <div class="flex items-center">
            <div class="truncate @if (Auth::user()->isCurrentTeam($team)) text-warning dark:text-warning-600 @endif">
                {{ $team->name }}
            </div>

            @if (Auth::user()->isCurrentTeam($team))
                <x-icon.tabler icon="circle-check" class="ms-2 text-success" />
            @endif
        </div>
    </x-dynamic-component>
</form>
