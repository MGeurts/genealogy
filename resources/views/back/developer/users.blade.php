@section('title')
    &vert; {{ __('user.users') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('user.users') }}
    </x-slot>

    <div class="py-5 w-full">
        <livewire:developer.users />
    </div>
</x-app-layout>
