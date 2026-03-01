@section('title')
    &vert; {{ __('gedcom.import') }}
@endsection

<x-app-layout>
    <div class="w-full p-2 space-y-5">
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <livewire:gedcom.importteam />
        </div>
    </div>
</x-app-layout>
