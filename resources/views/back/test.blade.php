@section('title')
    &vert; Test
@endsection

<x-app-layout>
    <x-slot name="heading">
        Test
    </x-slot>

    <div class="p-2 max-w-5xl overflow-x-auto grow dark:text-neutral-200">

        <p>Use this page to test components ...</p>
        <br />

        @php
            // ----------------------------------------------------------------------------------------------------------------------

            // $parser = new \Gedcom\Parser();
            // $gedcom = $parser->parse(storage_path('app/gedcom/demo.ged'));

            // $output = '';

            // foreach ($gedcom->getIndi() as $individual) {
            //     $names = $individual->getName();

            //     if (!empty($names)) {
            //         $name = reset($names);

            //         $output .= $individual->getId() . ': ' . $name->getSurn() . ', ' . $name->getGivn() . "\n";
            //     }
            // }

            $parser = new \PhpGedcom\Parser();
            $gedcom = $parser->parse(storage_path('app/gedcom/demo.ged'));

            $output = '';

            // dd($gedcom);

            foreach ($gedcom->getIndi() as $individual) {
                $output .= $individual->getId() . ': ' . current($individual->getName())->getSurn() . ', ' . current($individual->getName())->getGivn() . "\n";
            }

            // ----------------------------------------------------------------------------------------------------------------------
        @endphp

        <pre class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded overflow-x-auto">
            {{ $output }}
        </pre>
    </div>
</x-app-layout>
