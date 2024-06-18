<?php

declare(strict_types=1);

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Couple;
use App\Models\Person;
use Illuminate\View\View;

class PeopleController extends Controller
{
    public function search(): View
    {
        return view('back.people.search');
    }

    public function birthdays($months = 2): View
    {
        $people = Person::whereNotNull('dob')
            ->whereRaw('CASE WHEN MONTH(NOW()) +' . $months . " > 12 THEN date_format(dob, '%m-%d') >= date_format(NOW(), '%m-%d') OR date_format(dob, '%m-%d') <= date_format(NOW() + INTERVAL " . $months . " MONTH, '%m-%d') ELSE date_format(dob, '%m-%d') >= date_format(NOW(), '%m-%d') AND date_format(dob, '%m-%d') <= date_format(NOW() + INTERVAL " . $months . " MONTH, '%m-%d') END")
            ->orderByRaw("(case when date_format(dob, '%m-%d') >= date_format(now(), '%m-%d') then 0 else 1 end), date_format(dob, '%m-%d')")
            ->get();

        return view('back.people.birthdays', compact('months', 'people'));
    }

    public function add(): View
    {
        abort_unless(auth()->user()->hasPermission('person:create'), 403, __('app.unauthorized_access'));

        return view('back.people.add.person');
    }

    public function show(Person $person): View
    {
        return view('back.people.show', compact('person'));
    }

    public function ancestors(Person $person): View
    {
        return view('back.people.ancestors', compact('person'));
    }

    public function descendants(Person $person): View
    {
        return view('back.people.descendants', compact('person'));
    }

    public function chart(Person $person): View
    {
        return view('back.people.chart', compact('person'));
    }

    public function files(Person $person): View
    {
        return view('back.people.files', compact('person'));
    }

    public function history(Person $person): View
    {
        return view('back.people.history', compact('person'));
    }

    public function addFather(Person $person): View
    {
        abort_unless(auth()->user()->hasPermission('person:create'), 403, __('app.unauthorized_access'));

        return view('back.people.add.father', compact('person'));
    }

    public function addMother(Person $person): View
    {
        abort_unless(auth()->user()->hasPermission('person:create'), 403, __('app.unauthorized_access'));

        return view('back.people.add.mother', compact('person'));
    }

    public function addChild(Person $person): View
    {
        abort_unless(auth()->user()->hasPermission('person:create'), 403, __('app.unauthorized_access'));

        return view('back.people.add.child', compact('person'));
    }

    public function addPartner(Person $person): View
    {
        abort_unless(auth()->user()->hasPermission('couple:create'), 403, __('app.unauthorized_access'));

        return view('back.people.add.partner', compact('person'));
    }

    public function editContact(Person $person): View
    {
        abort_unless(auth()->user()->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.contact', compact('person'));
    }

    public function editDeath(Person $person): View
    {
        abort_unless(auth()->user()->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.death', compact('person'));
    }

    public function editFamily(Person $person): View
    {
        abort_unless(auth()->user()->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.family', compact('person'));
    }

    public function editPhotos(Person $person): View
    {
        abort_unless(auth()->user()->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.photos', compact('person'));
    }

    public function editProfile(Person $person): View
    {
        abort_unless(auth()->user()->hasPermission('person:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.profile', compact('person'));
    }

    public function editPartner(Person $person, Couple $couple): View
    {
        abort_unless(auth()->user()->hasPermission('couple:update'), 403, __('app.unauthorized_access'));

        return view('back.people.edit.partner', compact('person', 'couple'));
    }
}
