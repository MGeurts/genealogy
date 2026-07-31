<div>
    @section('title')
        &vert; {{ __('gedcom.import') }}
    @endsection

    <div class="w-full space-y-5 p-2">
        <div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
            <x-form-section submit="importteam">
                <x-slot name="title">
                    <div class="dark:text-gray-400">{{ __('gedcom.import') }}</div>
                </x-slot>

                <x-slot name="description">
                    <div class="dark:text-gray-100">{{ __('gedcom.team_create_new_gedcom') }}</div>

                    <div class="dark:text-gray-100">
                        <br />
                        <p>
                            {{ __('gedcom.team_gedcom_reference') }} :
                            <x-link
                                href="https://gedcom.io/specs/"
                                target="_blank"
                                title="{{ __('gedcom.team_gedcom_specifications') }}"
                            >
                                <x-svg.gedcom
                                    class="hover:fill-primary-300 dark:hover:fill-primary-300 size-36 dark:fill-white"
                                    alt="gedcom"
                                />
                            </x-link>
                        </p>
                    </div>

                    <x-ts-alert color="cyan">
                        <x-slot:title>{{ __('gedcom.before_import') }} ...</x-slot:title>

                        {{ __('gedcom.validate') }} :
                        <x-ts-link class="text-lg text-white" href="https://ged-inline.org/" target="_blank" />
                    </x-ts-alert>

                    <x-under-construction />
                </x-slot>

                <x-slot name="form" enctype="multipart/form-data">
                    {{-- TallStackUI Loading component --}}
                    <x-ts-loading
                        wire:loading
                        wire:target="importteam"
                        text="{{ __('gedcom.importing_please_wait') }}"
                        description="{{ __('gedcom.import_in_progress') }}"
                    />

                    <div class="col-span-6">
                        <x-label value="{{ __('team.owner') }} :" />

                        <div class="mt-2 flex items-center">
                            <img
                                class="h-12 w-12 rounded-full object-cover"
                                src="{{ $user->profile_photo_url }}"
                                alt="{{ $user->name }}"
                            />

                            <div class="ms-4 leading-tight">
                                <div class="text-sm text-gray-700">{{ $user->name }}</div>
                                <div class="text-sm text-gray-700">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6">
                        <hr class="my-2 h-0.5 border-t-0 bg-neutral-600 opacity-100 dark:bg-neutral-400 dark:opacity-75" />
                    </div>

                    {{-- team name --}}
                    <div class="col-span-6">
                        <x-ts-input
                            id="name"
                            name="name"
                            type="text"
                            class="mt-1 block w-full"
                            wire:model="name"
                            label="{{ __('team.name') }} : *"
                            required
                            autofocus
                        />
                    </div>

                    {{-- team description --}}
                    <div class="col-span-6">
                        <div class="relative mt-1 mb-3 block w-full">
                            <x-ts-textarea
                                id="description"
                                name="description"
                                wire:model="description"
                                label="{{ __('team.description') }} :"
                            />
                        </div>
                    </div>

                    {{-- upload --}}
                    <div class="col-span-6">
                        <x-ts-upload
                            id="file"
                            wire:model="file"
                            label="{{ __('gedcom.gedcom_file') }} : *"
                            hint="{{ __('gedcom.team_gedcom_hint') }}"
                            tip="{{ __('gedcom.team_gedcom_tip') }}"
                            placeholder="{{ __('gedcom.gedcom_import_placeholder') }}"
                            accept=".ged,.zip"
                            :preview="false"
                            close-after-upload
                        >
                            <x-slot:footer>
                                <x-ts-alert text="{{ __('gedcom.team_gedcom_version') }}" color="cyan" />
                            </x-slot:footer>
                        </x-ts-upload>
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <x-ts-button
                        type="submit"
                        color="primary"
                        wire:loading.attr="disabled"
                        wire:target="importteam"
                        loading="importteam"
                    >
                        {{ __('gedcom.import') }}
                    </x-ts-button>
                </x-slot>
            </x-form-section>
        </div>
    </div>
</div>
