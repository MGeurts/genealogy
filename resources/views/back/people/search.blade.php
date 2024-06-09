@section('title')
    &vert; {{ __('app.search') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.search') }}
    </x-slot>

    <div class="w-full py-5 space-y-5">
        <livewire:people.search />
    </div>
</x-app-layout>
