@section('title')
    &vert; {{ __('person.edit_relationship') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ $person->name }}
        <x-ts-icon icon="tabler.arrow-right-circle" class="inline-block" alt="Arrow Icon" />
        {{ __('person.edit_relationship') }}
    </x-slot>

    <div class="p-2 pb-5 sticky top-[6.5rem] z-20 bg-gray-100 dark:bg-gray-900">
        <livewire:people.heading :person="$person" />
    </div>

    <div class="w-full p-2 space-y-5 overflow-x-auto">
        <div class="flex flex-wrap gap-5">
            <div class="flex flex-col grow gap-5 min-w-100 md:max-w-max">
                <livewire:people.profile :person="$person" />
            </div>

            <div class="flex flex-col grow gap-5 min-w-100 md:max-w-max">
                <livewire:people.family :person="$person" />
                <livewire:people.partners :person="$person" />
                <livewire:people.children :person="$person" />
                <livewire:people.siblings :person="$person" />
                <livewire:people.files :person="$person" />
            </div>

            <div class="flex flex-col grow gap-5 overflow-x-auto min-w-100 md:max-w-max">
                <livewire:people.edit.partner :person="$person" :couple="$couple" />
            </div>
        </div>
    </div>
</x-app-layout>
