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

            // ----------------------------------------------------------------------------------------------------------------------
        @endphp
    </div>
</x-app-layout>
