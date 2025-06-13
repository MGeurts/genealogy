@section('title')
    &vert; {{ __('person.add_person') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('person.add_person_in_team', ['team' => auth()->user()->currentTeam->name]) }}
    </x-slot>

    <div class="w-full p-2 space-y-5">
        <livewire:people.add.person />
    </div>
</x-app-layout>
