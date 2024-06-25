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
            // ----------------------------------------------------------------------------------------------------------------------
            $parser = new App\GedcomParser();
            $parser->parse('./gedcom/royals.ged');

            echo '<h1>INDIVIDUALS :</h1>';
            $parser->outputIndividuals();

            echo '<h1>FAMILIES :</h1>';
            $parser->outputFamilies();
            // ----------------------------------------------------------------------------------------------------------------------
        @endphp

    </div>
</x-app-layout>
