@section('title')
    &vert; {{ __('person.add_person') }}
@endsection

<x-app-layout>
    <div class="w-full p-2 space-y-5">
        <livewire:people.add.person />
    </div>
</x-app-layout>
