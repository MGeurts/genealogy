<?php

declare(strict_types=1);

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Couple;
use App\Models\Person;
use Illuminate\View\View;

final class PeopleController extends Controller
{
    public function search(): View
    {
        return view('back.people.search');
    }

    public function birthdays(int $months = 2): View
    {
        $people = Person::whereNotNull('dob')
            ->whereRaw('CASE WHEN MONTH(NOW()) +' . $months . " > 12 THEN date_format(dob, '%m-%d') >= date_format(NOW(), '%m-%d') OR date_format(dob, '%m-%d') <= date_format(NOW() + INTERVAL " . $months . " MONTH, '%m-%d') ELSE date_format(dob, '%m-%d') >= date_format(NOW(), '%m-%d') AND date_format(dob, '%m-%d') <= date_format(NOW() + INTERVAL " . $months . " MONTH, '%m-%d') END")
            ->orderByRaw("(case when date_format(dob, '%m-%d') >= date_format(now(), '%m-%d') then 0 else 1 end), date_format(dob, '%m-%d')")
            ->get();

        return view('back.people.birthdays', ['months' => $months, 'people' => $people]);
    }

    public function add(): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:create'), 403, __('app.unauthorized_access'));

        return view('back.people.add.person');
    }

    public function show(Person $person): View
    {
        return view('back.people.show', ['person' => $person]);
    }

    public function ancestors(Person $person): View
    {
        return view('back.people.ancestors', ['person' => $person]);
    }

    public function descendants(Person $person): View
    {
        return view('back.people.descendants', ['person' => $person]);
    }

    public function chart(Person $person): View
    {
        return view('back.people.chart', ['person' => $person]);
    }

    public function history(Person $person): View
    {
        return view('back.people.history', ['person' => $person]);
    }

    public function datasheet(Person $person): View
    {
        return view('back.people.datasheet', ['person' => $person]);
    }

    public function timeline(Person $person): View
    {
        return view('back.people.timeline', ['person' => $person]);
    }

    public function addFather(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:create'), 403, __('app.unauthorized_access'));

        return view('back.people.add.father', ['person' => $person]);
    }

    public function addMother(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:create'), 403, __('app.unauthorized_access'));

        return view('back.people.add.mother', ['person' => $person]);
    }

    public function addChild(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:create'), 403, __('app.unauthorized_access'));

        return view('back.people.add.child', ['person' => $person]);
    }

    public function addPartner(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('couple:create'), 403, __('app.unauthorized_access'));

        return view('back.people.add.partner', ['person' => $person]);
    }

    public function editContact(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.contact', ['person' => $person]);
    }

    public function editDeath(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.death', ['person' => $person]);
    }

    public function editEvents(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.events', ['person' => $person]);
    }

    public function editFamily(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.family', ['person' => $person]);
    }

    public function editFiles(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.files', ['person' => $person]);
    }

    public function editPhotos(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.photos', ['person' => $person]);
    }

    public function editProfile(Person $person): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.profile', ['person' => $person]);
    }

    public function editPartner(Person $person, Couple $couple): View
    {
        abort_unless((bool) auth()->user()?->hasPermission('couple:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.partner', ['person' => $person, 'couple' => $couple]);
    }
}
