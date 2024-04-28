<x-form-section submit="createTeam">
    <x-slot name="title">
        <div class="dark:text-gray-400">
            {{ __('Team Details') }}
        </div>
    </x-slot>

    <x-slot name="description">
        <div class="dark:text-gray-100">
            {{ __('Create a new team, imported from a GEDCOM file, to collaborate with others.') }}
        </div>

        <div class="dark:text-gray-100">
            <br />
            <p>Reference : <a href="https://gedcom.io/specifications/FamilySearchGEDCOMv7.html" target="_blank" title="GEDCOM">GEDCOM</a></p>
        </div>
    </x-slot>

    <x-slot name="form" enctype="multipart/form-data">
        <div class="col-span-6">
            <x-label value="{{ __('team.owner') }}" />

            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full object-cover" src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}">

                <div class="ms-4 leading-tight">
                    <div class="text-gray-900 dark:text-white">{{ $this->user->name }}</div>
                    <div class="text-gray-700 dark:text-gray-700 text-sm">{{ $this->user->email }}</div>
                </div>
            </div>
        </div>

        {{-- team name --}}
        <div class="col-span-6 sm:col-span-4">
            <x-ts-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" autofocus required label="{{ __('team.name') }}" />
        </div>

        {{-- team description --}}
        <div class="col-span-6 sm:col-span-4">
            <div class="relative mt-1 mb-3 block w-full">
                <x-ts-textarea wire:model="state.description" id="description" label="{{ __('team.description') }} *" resize-auto required />
            </div>

            <x-input-error for="description" class="mt-2" />
        </div>

        {{-- gedcom file input --}}
        <div class="col-span-6 sm:col-span-4">
            <x-label for="file" value="{{ __('team.gedcom_file') }}" />

            <x-input type="file" id="file" accept="text/ged" wire:model="file" required
                class="relative m-0 block w-full min-w-0 flex-auto cursor-pointer rounded border border-solid border-neutral-300 bg-clip-padding px-3 py-[0.32rem] text-base font-normal leading-[1.60] text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:cursor-pointer file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3 file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-800 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary" />

            <x-input-error for="file" />

            <div class="col-span-6">
                <div wire:loading wire:target="file" role="status"
                    class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]">
                    <span class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Loading...</span>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-ts-button color="primary">
            {{ __('team.create') }}
        </x-ts-button>
    </x-slot>
</x-form-section>
