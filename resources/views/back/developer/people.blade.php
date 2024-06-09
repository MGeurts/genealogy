@section('title')
    &vert; {{ __('person.people') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('person.people') }}
    </x-slot>

    <div class="py-10 w-full">
        <livewire:developer.people />
    </div>
</x-app-layout>
