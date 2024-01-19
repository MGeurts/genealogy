@section('title')
    &vert; {{ __('person.ancestors') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('person.ancestors') }}
        </h2>
    </x-slot>

    <div class="w-full py-5 space-y-5">
        <livewire:people.heading :person="$person" />

        <div class="md:min-w-max md:max-w-sm">
            <div class="overflow-x-auto">
                <livewire:people.ancestors :person="$person" />
            </div>
        </div>
    </div>
</x-app-layout>
