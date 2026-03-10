<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// -----------------------------------------------------------------------------------
// frontend routes
// -----------------------------------------------------------------------------------
Route::livewire('password-generator', 'livewire::password-generator')->name('password.generator');

Route::controller(App\Http\Controllers\Front\PageController::class)->group(function (): void {
    Route::get('/', 'home')->name('home');
    Route::get('about', 'about')->name('about');
    Route::get('help', 'help')->name('help');
});

// -----------------------------------------------------------------------------------
// backend routes
// -----------------------------------------------------------------------------------
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function (): void {
    // -----------------------------------------------------------------------------------
    // pages
    // -----------------------------------------------------------------------------------
    Route::livewire('team', 'livewire::team')->name('team');
    Route::livewire('teamlog', 'livewire::teamlog')->name('teamlog');
    Route::livewire('peoplelog', 'livewire::peoplelog')->name('peoplelog');
    Route::livewire('test', 'livewire::test')->name('test');

    Route::controller(App\Http\Controllers\Back\TeamController::class)->group(function (): void {
        Route::put('/teams/{team}/transfer-ownership', 'transferOwnership')->name('teams.transfer-ownership');
    });

    // -----------------------------------------------------------------------------------
    // people
    // -----------------------------------------------------------------------------------
    Route::controller(App\Http\Controllers\Back\PeopleController::class)->group(function (): void {
        Route::get('search', 'search')->name('people.search');
        Route::get('birthdays', 'birthdays')->name('people.birthdays');

        Route::get('people/add', 'add')->name('people.add');
        Route::get('people/{person}', 'show')->name('people.show');
        Route::get('people/{person}/ancestors', 'ancestors')->name('people.ancestors');
        Route::get('people/{person}/descendants', 'descendants')->name('people.descendants');
        Route::get('people/{person}/chart', 'chart')->name('people.chart');
        Route::get('people/{person}/history', 'history')->name('people.history');
        Route::get('people/{person}/datasheet', 'datasheet')->name('people.datasheet');
        Route::get('people/{person}/timeline', 'timeline')->name('people.timeline');
        Route::get('people/{person}/add-father', 'addFather')->name('people.add-father');
        Route::get('people/{person}/add-mother', 'addMother')->name('people.add-mother');
        Route::get('people/{person}/add-child', 'addChild')->name('people.add-child');
        Route::get('people/{person}/add-partner', 'addPartner')->name('people.add-partner');
        Route::get('people/{person}/edit-contact', 'editContact')->name('people.edit-contact');
        Route::get('people/{person}/edit-death', 'editDeath')->name('people.edit-death');
        Route::get('people/{person}/edit-events', 'editEvents')->name('people.edit-events');
        Route::get('people/{person}/edit-family', 'editFamily')->name('people.edit-family');
        Route::get('people/{person}/edit-files', 'editFiles')->name('people.edit-files');
        Route::get('people/{person}/edit-photos', 'editPhotos')->name('people.edit-photos');
        Route::get('people/{person}/edit-profile', 'editProfile')->name('people.edit-profile');
        Route::get('people/{person}/{couple}/edit-partner', 'editPartner')->name('people.edit-partner');
    });

    // -----------------------------------------------------------------------------------
    // gedcom
    // -----------------------------------------------------------------------------------
    Route::livewire('exportteam', 'gedcom::exportteam')->name('gedcom.exportteam');
    Route::livewire('importteam', 'gedcom::importteam')->name('gedcom.importteam');

    // -----------------------------------------------------------------------------------
    // developer
    // -----------------------------------------------------------------------------------
    Route::middleware(App\Http\Middleware\IsDeveloper::class)->prefix('developer')->as('developer.')->group(function (): void {
        Route::livewire('teams', 'developer::teams')->name('teams');
        Route::livewire('people', 'developer::people')->name('people');

        Route::livewire('users', 'developer::users')->name('users');

        Route::livewire('settings', 'developer::settings')->name('settings');
        Route::livewire('backups', 'developer::backups')->name('backups');

        // -----------------------------------------------------------------------------------
        // pages
        // -----------------------------------------------------------------------------------
        Route::controller(App\Http\Controllers\Back\DeveloperController::class)->group(function (): void {
            Route::get('dependencies', 'dependencies')->name('dependencies');
            Route::get('session', 'session')->name('session');

            Route::get('userlog/log', 'userlogLog')->name('userlog.log');
            Route::get('userlog/origin', 'userlogOrigin')->name('userlog.origin');
            Route::get('userlog/originmap', 'userlogOriginMap')->name('userlog.origin-map');
            Route::get('userlog/period', 'userlogPeriod')->name('userlog.period');
        });
    });
});

// -----------------------------------------------------------------------------------
// set application language in session
// actual language switching wil be handled by App\Http\Middleware\Localization::class
// -----------------------------------------------------------------------------------
Route::get('language/{locale}', function ($locale) {
    session()->put('locale', $locale);

    return back();
});
