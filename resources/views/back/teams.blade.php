@section('title')
    &vert; {{ __('team.teams') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('team.teams') }}
    </x-slot>

    <div class="max-w-5xl py-5 overflow-x-auto grow dark:text-neutral-200">
        <livewire:teams />
    </div>
</x-app-layout>