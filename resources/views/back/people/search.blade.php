@section('title')
    &vert; {{ __('app.search') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.search') }}
        </h2>
    </x-slot>

    <div class="w-full py-5 space-y-5">
        <livewire:people.search />
    </div>
</x-app-layout>
