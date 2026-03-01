@section('title')
    &vert; {{ __('user.users') }}
@endsection

<x-app-layout>
    <div class="p-2 w-full">
        <livewire:developer.users />
    </div>
</x-app-layout>
