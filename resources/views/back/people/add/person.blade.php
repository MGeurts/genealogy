@section('title')
    &vert; {{ __('person.add_person') }}
@endsection

<x-app-layout>
    <div class="w-full space-y-5 p-2">
        <livewire:people::add.person />
    </div>
</x-app-layout>
