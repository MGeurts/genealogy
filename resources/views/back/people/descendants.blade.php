@section('title')
    &vert; {{ __('person.descendants') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ $person->name }}
        <x-ts-icon icon="tabler.arrow-right-circle" class="inline-block size-5" alt="Arrow Icon" />
        {{ __('person.descendants') }}
    </x-slot>

    <div class="p-2 pb-5 sticky top-[6.5rem] z-20 bg-gray-100 dark:bg-gray-900">
        <livewire:people.heading :person="$person" />
    </div>

    <div class="w-full p-2 space-y-5">
        <div class="md:min-w-max md:max-w-sm">
            <div class="overflow-x-auto">
                <livewire:people.descendants :person="$person" />
            </div>
        </div>
    </div>
</x-app-layout>
