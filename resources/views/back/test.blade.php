@section('title')
    &vert; Test
@endsection

<x-app-layout>
    <x-slot name="heading">
        Test
    </x-slot>

    <div class="max-w-5xl py-5 overflow-x-auto grow dark:text-neutral-200">

        <p>Use this page to test components ...</p>
        <br />

        @php
            // ----------------------------------------------------------------------------------------------------------------------

            // ----------------------------------------------------------------------------------------------------------------------
        @endphp
    </div>
</x-app-layout>
