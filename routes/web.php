<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

// -----------------------------------------------------------------------------------------------
// Frontend routes
// -----------------------------------------------------------------------------------------------
// Home
// -----------------------------------------------------------------------------------------------
Route::get('/', App\Http\Controllers\Front\HomeController::class)->name('home');

// -----------------------------------------------------------------------------------------------
// Pages
// -----------------------------------------------------------------------------------------------
Route::controller(App\Http\Controllers\Front\PageController::class)->group(function () {
    Route::get('about', 'about')->name('about');
    Route::get('help', 'help')->name('help');
    Route::get('test', 'test')->name('test');
});

// -----------------------------------------------------------------------------------------------
// Language
// -----------------------------------------------------------------------------------------------
Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    Carbon::setLocale($locale);
    session()->put('locale', $locale);

    // session()->flash('flash.banner', 'Language changed.');
    // session()->flash('flash.bannerStyle', 'danger');

    toast()
        ->info(__('app.language_set') . ' <b>' . strtoupper($locale) . '</b>.', __('app.language'))
        ->doNotSanitize()
        //->duration(10000)
        //->sticky()
        //->push();
        ->pushOnNextPage();

    return redirect()->back();
});

// -----------------------------------------------------------------------------------------------
// Backend routes
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
        Route::get('people/{person}/death', 'death')->name('people.death');
        Route::get('people/{person}/add-child', 'addChild')->name('people.add-child');
        Route::get('people/{person}/add-partner', 'addPartner')->name('people.add-partner');
        Route::get('people/{person}/edit-contact', 'editContact')->name('people.edit-contact');
        Route::get('people/{person}/edit-death', 'editDeath')->name('people.edit-death');
        Route::get('people/{person}/edit-family', 'editFamily')->name('people.edit-family');
        Route::get('people/{person}/edit-profile', 'editProfile')->name('people.edit-profile');

        Route::get('people/{couple}/{person}/edit-partner', 'editPartner')->name('people.edit-partner');

        Route::patch('people/{person}', 'update')->name('people.update');
        Route::delete('people/{person}', 'destroy')->name('people.destroy');
    });

    Route::get('dependencies', function () {
        return view('back.dependencies');
    })->name('dependencies');

    Route::get('session', function () {
        return view('back.session');
    })->name('session');

    // -----------------------------------------------------------------------------------------------
    // backups
    // -----------------------------------------------------------------------------------------------
    Route::get('backups', App\Livewire\Backups\Manage::class)->name('backups');

    // -----------------------------------------------------------------------------------------------
    // userlog
    // -----------------------------------------------------------------------------------------------
    Route::get('userlogs/log', App\Livewire\Userlogs\Log::class)->name('userlogs.log');
    Route::get('userlogs/origin', App\Livewire\Userlogs\Origin::class)->name('userlogs.origin');
    Route::get('userlogs/originMap', App\Livewire\Userlogs\OriginMap::class)->name('userlogs.origin-map');
    Route::get('userlogs/period', App\Livewire\Userlogs\Period::class)->name('userlogs.period');
});
// -----------------------------------------------------------------------------------------------
