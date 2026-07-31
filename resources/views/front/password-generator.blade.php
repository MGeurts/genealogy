@section('title')
    &vert; {{ __('app.password_generator') }}
@endsection

<x-app-layout>
    <div class="max-w-7xl grow overflow-x-auto p-2 dark:text-neutral-200">
        <livewire:password-generator />
    </div>
</x-app-layout>
