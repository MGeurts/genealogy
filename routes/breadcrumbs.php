<?php

declare(strict_types=1);

use TallStackUi\Facades\TallStackUi;
use TallStackUi\Support\Breadcrumbs\BreadcrumbTrail;

/*
|--------------------------------------------------------------------------
| Breadcrumb Definitions
|--------------------------------------------------------------------------
|
| Register breadcrumb definitions for named routes. Each definition
| receives a BreadcrumbTrail instance and may declare a parent route
| whose items will be prepended automatically.
|
*/

TallStackUi::breadcrumbs()
    // -----------------------------------------------------------------------------------
    // frontend routes
    // -----------------------------------------------------------------------------------
    ->for('home', fn (BreadcrumbTrail $trail) => $trail
        ->add(label: __('app.home'), link: '/', icon: 'home')
    )
    ->for('password.generator', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('app.password_generator'), link: route('password.generator'))
    )
    ->for('about', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: 'About', link: route('about'))
    )
    ->for('help', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: 'Help', link: route('help'), icon: 'tabler.help')
    )
    ->for('policy.show', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('app.privacy_policy'), link: route('policy.show'))
    )
    ->for('terms.show', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('app.terms_of_service'), link: route('terms.show'))
    )
    // -----------------------------------------------------------------------------------
    // teams
    // -----------------------------------------------------------------------------------
    ->for('team', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('team.team'), link: route('team'))
    )
    ->for('teamlog', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('app.team_logbook'), link: route('teamlog'))
    )
    ->for('peoplelog', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('app.people_logbook'), link: route('peoplelog'))
    )
    // -----------------------------------------------------------------------------------
    // pages
    // -----------------------------------------------------------------------------------
    ->for('test', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: 'Test', link: route('test'))
    )
    // -----------------------------------------------------------------------------------
    // people
    // -----------------------------------------------------------------------------------
    ->for('people.search', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('app.search'), link: route('people.search'), icon: 'tabler.search')
    )
    ->for('people.birthdays', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('birthday.birthdays'), link: route('people.birthdays'), icon: 'tabler.cake')
    )
    ->for('people.add', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('person.add_person_in_team', ['team' => auth()->user()->currentTeam->name]), link: route('people.add'))
    )
    ->for('people.show', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('home')
        ->add(label: $person->name, link: route('people.show', $person))
    )
    ->for('people.ancestors', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.ancestors'), link: route('people.ancestors', $person))
    )
    ->for('people.descendants', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.descendants'), link: route('people.descendants', $person))
    )
    ->for('people.chart', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('app.family_chart'), link: route('people.chart', $person))
    )
    ->for('people.history', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('app.history'), link: route('people.history', $person))
    )
    ->for('people.datasheet', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('app.datasheet'), link: route('people.datasheet', $person))
    )
    ->for('people.timeline', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('app.timeline'), link: route('people.timeline', $person))
    )
    ->for('people.add-father', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.add_father'), link: route('people.add-father', $person))
    )
    ->for('people.add-mother', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.add_mother'), link: route('people.add-mother', $person))
    )
    ->for('people.add-child', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.add_child'), link: route('people.add-child', $person))
    )
    ->for('people.add-partner', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.add_relationship'), link: route('people.add-partner', $person))
    )
    ->for('people.edit-contact', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.edit_contact'), link: route('people.edit-contact', $person))
    )
    ->for('people.edit-death', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.edit_death'), link: route('people.edit-death', $person))
    )
    ->for('people.edit-events', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.edit_events'), link: route('people.edit-events', $person))
    )
    ->for('people.edit-family', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.edit_family'), link: route('people.edit-family', $person))
    )
    ->for('people.edit-files', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.edit_files'), link: route('people.edit-files', $person))
    )
    ->for('people.edit-photos', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.edit_photos'), link: route('people.edit-photos', $person))
    )
    ->for('people.edit-profile', fn (BreadcrumbTrail $trail, $person) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.edit_profile'), link: route('people.edit-profile', $person))
    )
    ->for('people.edit-partner', fn (BreadcrumbTrail $trail, $person, $couple) => $trail
        ->parent('people.show', $person)
        ->add(label: __('person.edit_relationship'), link: route('people.edit-partner', [$person, $couple]))
    )
    // -----------------------------------------------------------------------------------
    // gedcom
    // -----------------------------------------------------------------------------------
    ->for('gedcom.exportteam', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('gedcom.gedcom_export'), link: route('gedcom.exportteam'), icon: 'tabler.droplet-down')
    )
    ->for('gedcom.importteam', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('gedcom.gedcom_import'), link: route('gedcom.importteam'), icon: 'tabler.droplet-up')
    )
    // -----------------------------------------------------------------------------------
    // developer - pages
    // -----------------------------------------------------------------------------------
    ->for('developer.settings', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('app.settings'), link: route('developer.settings'))
    )
    ->for('developer.teams', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('team.teams'), link: route('developer.teams'))
    )
    ->for('developer.people', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('person.people'), link: route('developer.people'))
    )
    ->for('developer.users', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('user.users'), link: route('developer.users'))
    )
    ->for('developer.dependencies', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('app.dependencies'), link: route('developer.dependencies'))
    )
    ->for('developer.session', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('app.session'), link: route('developer.session'))
    )
    ->for('developer.userlog.log', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('userlog.users_log'), link: route('developer.userlog.log'))
    )->for('developer.userlog.origin', fn (BreadcrumbTrail $trail) => $trail
    ->parent('home')
    ->add(label: __('userlog.users_origin'), link: route('developer.userlog.origin'))
    )->for('developer.userlog.origin-map', fn (BreadcrumbTrail $trail) => $trail
    ->parent('home')
    ->add(label: __('userlog.users_origin'), link: route('developer.userlog.origin-map'))
    )->for('developer.userlog.period', fn (BreadcrumbTrail $trail) => $trail
    ->parent('home')
    ->add(label: __('userlog.users_stats'), link: route('developer.userlog.period'))
    )
    // -----------------------------------------------------------------------------------
    // backups
    // -----------------------------------------------------------------------------------
    ->for('developer.backups', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('backup.backups'), link: route('developer.backups'))
    )
    // -----------------------------------------------------------------------------------
    // auth
    // -----------------------------------------------------------------------------------
    ->for('login', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('auth.login'), link: route('login'))
    )
    ->for('register', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('auth.register'), link: route('register'))
    )
    ->for('profile.show', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('app.my_profile'), link: route('profile.show'), icon: 'tabler.id')
    )
    // -----------------------------------------------------------------------------------
    // team management
    // -----------------------------------------------------------------------------------
    ->for('teams.create', fn (BreadcrumbTrail $trail) => $trail
        ->parent('home')
        ->add(label: __('team.create'), link: route('teams.create'), icon: 'tabler.droplet-plus')
    )
    ->for('teams.show', fn (BreadcrumbTrail $trail, $team) => $trail
        ->parent('home')
        ->add(label: $team, link: route('teams.show', $team), icon: 'tabler.droplet-cog')
    );
