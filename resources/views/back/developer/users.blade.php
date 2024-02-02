@section('title')
    &vert; {{ __('user.users') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('user.users') }}
        </h2>
    </x-slot>

    <div class="py-10 w-full">
        <livewire:developer.users />
    </div>
</x-app-layout>
