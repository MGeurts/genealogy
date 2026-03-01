@section('title')
    &vert; Test
@endsection

<x-app-layout>
    <div class="p-2 max-w-5xl overflow-x-auto grow dark:text-neutral-200">

        <p>Use this page to test components ...</p>
        <br />

        @php
            // ----------------------------------------------------------------------------------------------------------------------
            $output = '';

            // ----------------------------------------------------------------------------------------------------------------------
        @endphp

        <pre class="bg-neutral-100 dark:bg-neutral-800 p-4 rounded overflow-x-auto">
            {{ $output }}
        </pre>
    </div>
</x-app-layout>
