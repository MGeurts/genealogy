@section('title')
    &vert; {{ __('person.ancestors') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('person.ancestors') }}
    </x-slot>

    <div class="w-full p-2 space-y-5">
        <livewire:people.heading :person="$person" />

        <div class="md:min-w-max md:max-w-sm">
            <div class="overflow-x-auto">
                <livewire:people.ancestors :person="$person" />
            </div>
        </div>
    </div>
</x-app-layout>
