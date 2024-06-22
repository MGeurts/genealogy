@section('title')
    &vert; Test
@endsection

<x-app-layout>
    <x-slot name="heading">
        Test
    </x-slot>

    <div class="grow max-w-5xl overflow-x-auto py-5 dark:text-neutral-200">

        <p>Use this page to test components ...</p>
        <br />

        @php
            $gedcom = App\Person::parse('./gedcom/royals.ged');

            foreach ($gedcom['INDI'] as $person) {
                $person = new App\Person($person, $gedcom);
                echo $person->surname() . ', ' . $person->forename() . ' ' . $person->years() . "\n<br/>";
                // echo $person->link() . "\n";
                // echo $person->name() . ' father is ' . $person->father->()->name();
                // echo $person->name() . ' maternal grandfather is ' . $gedcom['INDI']['I1']->mother->()->father()->name();
            }
        @endphp

    </div>
</x-app-layout>
