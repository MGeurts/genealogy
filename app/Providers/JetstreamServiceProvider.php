<?php

namespace App\Providers;

use App\Actions\Fortify\UserLoggedIn;
use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);

        // -----------------------------------------------------------------------------------------------
        // Making Laravel Jetstream Team Invitations Better
        // Ref : https://mariogiancini.com/making-laravel-jetstream-team-invitations-better
        // -----------------------------------------------------------------------------------------------
        Fortify::authenticateThrough(function (Request $request) {
            return array_filter([
                config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
                RedirectIfTwoFactorAuthenticatable::class,
                AttemptToAuthenticate::class,
                PrepareAuthenticatedSession::class,
                UserLoggedIn::class,
            ]);
        });

        // ----------------------------------------------------------------------------------------------------
        // Let uss redirect the user to the page he was before he tried to log in
        // Ref: https://laracasts.com/discuss/channels/laravel/redirect-to-intended-url-jetstream-fortify
        // ----------------------------------------------------------------------------------------------------
        // Get Session Link for Login View
        Fortify::loginView(function () {
            if (session('link')) {
                $myPath = session('link');
                $loginPath = url('/login');
                $previous = url()->previous();

                if ($previous = $loginPath) {
                    session(['link' => $myPath]);
                } else {
                    session(['link' => $previous]);
                }
            } else {
                session(['link' => url()->previous()]);
            }

            return view('auth.login');
        });

        // register new LoginResponse
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class,
        );
        // ----------------------------------------------------------------------------------------------------
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('administrator', 'Administrator', [
            'user:create',
            'user:read',
            'user:update',
            'user:delete',

            'person:create',
            'person:read',
            'person:update',
            'person:delete',

            'couple:create',
            'couple:read',
            'couple:update',
            'couple:delete',
        ])->description('Administrators can perform any action and manage the application.');

        Jetstream::role('manager', 'Manager', [
            'person:create',
            'person:read',
            'person:update',
            'person:delete',

            'couple:create',
            'couple:read',
            'couple:update',
            'couple:delete',
        ])->description('Managers can perform any action on people.');

        Jetstream::role('editor', 'Editor', [
            'person:create',
            'person:read',
            'person:update',

            'couple:create',
            'couple:read',
            'couple:update',
        ])->description('Editors have the ability to create, read and update people.');

        Jetstream::role('member', 'Member', [
            'person:read',

            'couple:read',
        ])->description('Members have the ability to read people.');
    }
}
