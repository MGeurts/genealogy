<div>
    @section('title')
        &vert; {{ __('gedcom.export') }}
    @endsection

    <div class="w-full space-y-5 p-2">
        <div class="mx-auto max-w-7xl py-10 sm:px-6 lg:px-8">
            <x-form-section submit="exportTeam">
                <x-slot name="title">
                    <div class="dark:text-gray-400">{{ __('gedcom.export') }}</div>
                </x-slot>

                <x-slot name="description">
                    <div class="dark:text-gray-100">{{ __('gedcom.gedcom_export') }}</div>

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
                        <x-slot:title>{{ __('gedcom.after_import') }} ...</x-slot:title>

                        {{ __('gedcom.validate') }} :
                        <x-ts-link class="text-lg text-white" href="https://ged-inline.org/" target="_blank" />
                    </x-ts-alert>

                    <x-under-construction />
                </x-slot>

                <x-slot name="form" enctype="multipart/form-data">
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

                    {{-- filename --}}
                    <div class="col-span-6">
                        <x-ts-input
                            id="filename"
                            type="text"
                            class="mt-1 block w-full"
                            wire:model="filename"
                            label="{{ __('gedcom.filename') }} : *"
                            required
                        />
                    </div>

                    {{-- format --}}
                    <div class="col-span-6">
                        <x-ts-select.styled
                            wire:model.live="format"
                            id="format"
                            :options="$formats"
                            select="label:label|value:value"
                            label="{{ __('gedcom.format') }} : *"
                            required
                        />
                    </div>
                </x-slot>

                <x-slot name="actions">
                    <x-ts-button type="submit" color="primary">
                        <x-ts-icon icon="tabler.download" class="inline-block size-5" />
                        {{ __('app.download') }}
                    </x-ts-button>
                </x-slot>
            </x-form-section>
        </div>
    </div>
</div>
