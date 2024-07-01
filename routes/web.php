<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

// -----------------------------------------------------------------------------------------------
// frontend routes
// -----------------------------------------------------------------------------------------------
Route::controller(App\Http\Controllers\Front\PageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('about', 'about')->name('about');
    Route::get('help', 'help')->name('help');
});

// -----------------------------------------------------------------------------------------------
// backend routes
// -----------------------------------------------------------------------------------------------
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // -----------------------------------------------------------------------------------------------
    // people
    // -----------------------------------------------------------------------------------------------
    Route::controller(App\Http\Controllers\Back\PeopleController::class)->group(function () {
        Route::get('search', 'search')->name('people.search');
        Route::get('birthdays', 'birthdays')->name('people.birthdays');

        Route::get('people/add', 'add')->name('people.add');
        Route::get('people/{person}', 'show')->name('people.show');
        Route::get('people/{person}/ancestors', 'ancestors')->name('people.ancestors');
        Route::get('people/{person}/descendants', 'descendants')->name('people.descendants');
        Route::get('people/{person}/chart', 'chart')->name('people.chart');
        Route::get('people/{person}/files', 'files')->name('people.files');
        Route::get('people/{person}/history', 'history')->name('people.history');
        Route::get('people/{person}/add-father', 'addFather')->name('people.add-father');
        Route::get('people/{person}/add-mother', 'addMother')->name('people.add-mother');
        Route::get('people/{person}/add-child', 'addChild')->name('people.add-child');
        Route::get('people/{person}/add-partner', 'addPartner')->name('people.add-partner');
        Route::get('people/{person}/edit-contact', 'editContact')->name('people.edit-contact');
        Route::get('people/{person}/edit-death', 'editDeath')->name('people.edit-death');
        Route::get('people/{person}/edit-family', 'editFamily')->name('people.edit-family');
        Route::get('people/{person}/edit-photos', 'editPhotos')->name('people.edit-photos');
        Route::get('people/{person}/edit-profile', 'editProfile')->name('people.edit-profile');
        Route::get('people/{person}/{couple}/edit-partner', 'editPartner')->name('people.edit-partner');
    });

    // -----------------------------------------------------------------------------------------------
    // gedcom
    // -----------------------------------------------------------------------------------------------
    Route::controller(App\Http\Controllers\Back\GedcomController::class)->group(function () {
        Route::get('export', 'export')->name('gedcom.export');
        Route::get('import', 'import')->name('gedcom.import');
    });

    // -----------------------------------------------------------------------------------------------
    // developer
    // -----------------------------------------------------------------------------------------------
    Route::middleware(App\Http\Middleware\IsDeveloper::class)->group(function () {
        // -----------------------------------------------------------------------------------------------
        // pages
        // -----------------------------------------------------------------------------------------------
        Route::controller(App\Http\Controllers\Back\DeveloperController::class)->group(function () {
            Route::get('dependencies', 'dependencies')->name('dependencies');
            Route::get('session', 'session')->name('session');
            Route::get('test', 'test')->name('test');

            Route::get('people', 'people')->name('people');
            Route::get('peoplelog', 'peoplelog')->name('peoplelog');
            Route::get('teams', 'teams')->name('teams');
            Route::get('users', 'users')->name('users');
        });

        // -----------------------------------------------------------------------------------------------
        // userlog
        // -----------------------------------------------------------------------------------------------
        Route::get('userlogs/log', App\Livewire\Userlogs\Log::class)->name('userlogs.log');
        Route::get('userlogs/origin', App\Livewire\Userlogs\Origin::class)->name('userlogs.origin');
        Route::get('userlogs/originMap', App\Livewire\Userlogs\OriginMap::class)->name('userlogs.origin-map');
        Route::get('userlogs/period', App\Livewire\Userlogs\Period::class)->name('userlogs.period');

        // -----------------------------------------------------------------------------------------------
        // backups
        // -----------------------------------------------------------------------------------------------
        Route::get('backups', App\Livewire\Backups\Manage::class)->name('backups');
    });
});

// -----------------------------------------------------------------------------------------------
// language
// -----------------------------------------------------------------------------------------------
Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    Carbon::setLocale($locale);
    session()->put('locale', $locale);

    return back();
});
