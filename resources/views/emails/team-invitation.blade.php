@component('mail::message')
{{ __('You have been invited to join the :team team!', ['team' => $invitation->team->name]) }}

{{ __('You may accept this invitation by clicking the button below and logging in:') }}

@component('mail::button', ['url' => $acceptUrl])
{{ __('Accept Invitation') }}
@endcomponent

@if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::registration()))
{{ __('If you do not have an account yet, click the register link on the login page. After creating an account, you will be automatically added to the team.') }}
@endif

{{ __('If you did not expect to receive an invitation to this team, you may discard this email.') }}
@endcomponent