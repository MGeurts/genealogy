@section('title')
    &vert; {{ __('app.password_generator') }}
@endsection

<x-app-layout>
    <div class="p-2 overflow-x-auto max-w-7xl grow dark:text-neutral-200">
        <livewire:password-generator/>
    </div>
</x-app-layout>
