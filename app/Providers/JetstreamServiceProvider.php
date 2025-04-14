<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use Override;

final class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
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
