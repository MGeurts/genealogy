@section('title')
    &vert; {{ __('app.datasheet') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.datasheet') }}
    </x-slot>

    <div class="w-full py-5 space-y-5 overflow-x-auto">
        <livewire:people.heading :person="$person" />
        
        <livewire:people.datasheet :person="$person" />
    </div>
</x-app-layout>
