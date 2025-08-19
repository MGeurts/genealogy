@section('title')
    &vert; {{ __('person.profile') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ $person->name }}
        <x-ts-icon icon="tabler.arrow-right-circle" class="inline-block size-5" alt="Arrow Icon" />
        {{ __('person.profile') }}
    </x-slot>

    <div class="p-2 pb-5 sticky top-[6.5rem] z-20 bg-gray-100 dark:bg-gray-900">
        <livewire:people.heading :person="$person" />
    </div>

    <div class="w-full p-2 space-y-5 overflow-x-auto">
        <div class="flex flex-wrap gap-5">
            <div class="flex flex-col gap-5 grow md:max-w-max">
                <livewire:people.profile :person="$person" />
            </div>

            <div class="flex flex-col gap-5 grow md:max-w-max">
                <livewire:people.family :person="$person" />
                <livewire:people.partners :person="$person" />
                <livewire:people.children :person="$person" />
                <livewire:people.siblings :person="$person" />
                <livewire:people.files :person="$person" />
            </div>

            <div class="flex flex-col gap-5 grow md:max-w-max overflow-x-auto">
                <livewire:people.ancestors :person="$person" />
                <livewire:people.descendants :person="$person" />
            </div>
        </div>
    </div>
</x-app-layout>
