@section('title')
    &vert; {{ __('team.teams') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('team.teams') }}
    </x-slot>

    <div class="py-5 w-full">
        <livewire:developer.teams />
    </div>
</x-app-layout>
