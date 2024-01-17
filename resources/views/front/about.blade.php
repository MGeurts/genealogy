@section('title')
    &vert; {{ __('app.about') }}
@endsection

<x-guest-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.about') }}
        </h2>
    </x-slot>

    <div class="py-10 dark:text-neutral-200">
        {{ __('app.about') }}
    </div>
</x-guest-layout>
