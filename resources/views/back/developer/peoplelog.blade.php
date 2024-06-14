@section('title')
    &vert; {{ __('person.people_log') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('person.people_log') }}
    </x-slot>

    <div class="py-5 w-full">
        <livewire:developer.peoplelog />
    </div>
</x-app-layout>
