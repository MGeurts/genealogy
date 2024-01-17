<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Couple;
use App\Models\Person;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\View\View;

//use App\Jobs\People\DeleteAndReplacePersonr;

class PeopleController extends Controller
{
    /* -------------------------------------------------------------------------------------------- */
    /* OK
    /* -------------------------------------------------------------------------------------------- */
    public function search(): View
    {
        return view('front.people.search');
    }

    public function birthdays($months = 2): View
    {
        $people = Person::whereNotNull('dob')
            ->whereRaw('CASE WHEN MONTH(NOW()) +' . $months . " > 12 THEN date_format(dob, '%m-%d') >= date_format(NOW(), '%m-%d') OR date_format(dob, '%m-%d') <= date_format(NOW() + INTERVAL " . $months . " MONTH, '%m-%d') ELSE date_format(dob, '%m-%d') >= date_format(NOW(), '%m-%d') AND date_format(dob, '%m-%d') <= date_format(NOW() + INTERVAL " . $months . " MONTH, '%m-%d') END")
            ->orderByRaw("(case when date_format(dob, '%m-%d') >= date_format(now(), '%m-%d') then 0 else 1 end), date_format(dob, '%m-%d')")
            ->get();

        return view('front.people.birthdays')->with(compact(
            'months',
            'people'
        ));
    }

    public function add(): View
    {
        return view('front.people.add');
    }

    public function store(Request $request)
    {
        try {
        } catch (QueryException $e) {
        }
    }

    public function show(Person $person): View
    {
        return view('front.people.show')->with(compact('person'));
    }

    public function ancestors(Person $person): View
    {
        return view('front.people.ancestors')->with(compact('person'));
    }

    public function descendants(Person $person): View
    {
        return view('front.people.descendants')->with(compact('person'));
    }

    public function chart(Person $person): View
    {
        return view('front.people.chart')->with(compact('person'));
    }

    public function death(Person $person): View
    {
        return view('front.people.show.death')->with(compact('person'));
    }

    public function addChild(Person $person): View
    {
        return view('front.people.add.child')->with(compact('person'));
    }

    public function addPartner(Person $person): View
    {
        return view('front.people.add.partner')->with(compact('person'));
    }

    public function editContact(Person $person): View
    {
        return view('front.people.edit.contact')->with(compact('person'));
    }

    public function editFamily(Person $person): View
    {
        return view('front.people.edit.family')->with(compact('person'));
    }

    public function editDeath(Person $person): View
    {
        return view('front.people.edit.death')->with(compact('person'));
    }

    public function editPartner(Couple $couple, Person $person): View
    {
        return view('front.people.edit.partner')->with(compact('couple', 'person'));
    }

    public function editProfile(Person $person): View
    {
        return view('front.people.edit.profile')->with(compact('person'));
    }
}
