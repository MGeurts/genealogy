@section('title')
    &vert; {{ __('person.people') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('person.people') }}
        </h2>
    </x-slot>

    <div class="py-10 w-full">
        <livewire:persons />
    </div>
</x-app-layout>
