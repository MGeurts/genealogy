@section('title')
    &vert; {{ __('team.export') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('team.export') }}
    </x-slot>

    <div class="w-full py-5 space-y-5">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:gedcom.export />
        </div>
    </div>
</x-app-layout>
