@section('title')
    &vert; {{ __('gedcom.export') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('gedcom.export') }}
    </x-slot>

    <div class="w-full p-2 space-y-5">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:gedcom.exportteam />
        </div>
    </div>
</x-app-layout>
