@section('title')
    &vert; {{ __('team.teams') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('team.teams') }}
        </h2>
    </x-slot>

    <div class="py-10 w-full">
        <livewire:teams />
    </div>
</x-app-layout>
