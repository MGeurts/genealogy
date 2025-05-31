<div class="mt-4 max-w-192">
    <x-ts-alert color="cyan" icon="tabler.exclamation-circle" close>
        <x-slot:title>
            {{ __('team.personal_team_caution') }}
        </x-slot:title>

        <p>{{ __('team.personal_team_avoid') }}</p>
        <x-hr.narrow class="col-span-6" />
        <p>{{ __('team.personal_team_instead') }} {{ __('team.personal_team_action') }}</p>
    </x-ts-alert>
</div>
