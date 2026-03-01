@section('title')
    &vert; {{ __('person.people') }}
@endsection

<x-app-layout>
    <div class="p-2 w-full">
        <livewire:developer.people />
    </div>
</x-app-layout>
