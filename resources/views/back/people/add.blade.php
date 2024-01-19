@section('title')
    &vert; {{ __('person.add_person') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('person.add_person') }}
        </h2>
    </x-slot>

    <div class="w-full py-5 space-y-5">
        <livewire:people.add.person />
    </div>
</x-app-layout>
