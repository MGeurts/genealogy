@section('title')
    &vert; {{ __('app.help') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.help') }}
        </h2>
    </x-slot>

    <div class="py-10 dark:text-neutral-200">
        {{ __('app.help') }}
    </div>
</x-app-layout>
