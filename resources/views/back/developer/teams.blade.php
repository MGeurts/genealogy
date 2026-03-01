@section('title')
    &vert; {{ __('team.teams') }}
@endsection

<x-app-layout>
    <div class="p-2 w-full">
        <livewire:developer.teams />
    </div>
</x-app-layout>
