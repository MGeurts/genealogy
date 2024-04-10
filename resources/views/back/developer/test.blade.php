@section('title')
    &vert; Test
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">Test</h2>
    </x-slot>

    <div class="grow max-w-5xl overflow-x-auto py-5 dark:text-neutral-200">

        <p>Use this page to test components ...</p>

    </div>
</x-app-layout>
