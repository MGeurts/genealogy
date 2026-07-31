@section('title')
    &vert; {{ __('person.ancestors') }}
@endsection

<x-app-layout>
    <div class="sticky top-[6.5rem] z-20 bg-gray-100 p-2 pb-5 dark:bg-gray-900">
        <livewire:people::heading :person="$person" />
    </div>

    <div class="w-full space-y-5 p-2">
        <div class="md:max-w-sm md:min-w-max">
            <div class="overflow-x-auto">
                <livewire:people::ancestors :person="$person" />
            </div>
        </div>
    </div>
</x-app-layout>
