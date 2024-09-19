@section('title')
    &vert; {{ __('team.team') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('team.team') }}
    </x-slot>

    <div class="max-w-5xl py-5 overflow-x-auto grow dark:text-neutral-200">
        <livewire:team />
    </div>
</x-app-layout>