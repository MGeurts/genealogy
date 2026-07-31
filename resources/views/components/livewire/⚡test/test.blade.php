<div>
    @section('title')
        &vert; Test
    @endsection

    <div class="max-w-5xl grow overflow-x-auto p-2 dark:text-neutral-200">
        <p>Use this page to test components ...</p>
        <br />

        @php
            // ----------------------------------------------------------------------------------------------------------------------
            $output = '';

            // ----------------------------------------------------------------------------------------------------------------------
        @endphp

        <pre class="overflow-x-auto rounded bg-neutral-100 p-4 dark:bg-neutral-800">
            {{ $output }}
        </pre>
    </div>
</div>
