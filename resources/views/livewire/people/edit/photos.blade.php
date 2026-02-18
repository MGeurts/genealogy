<div class="md:w-3xl flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="flex-1 grow max-w-full min-w-max">
                {{ __('person.photos') }}
                @if ($this->photos->count() > 0)
                    <x-ts-badge color="emerald" sm text="{{ $this->photos->count() }}" />
                @endif
            </div>

            <div class="flex-1 grow max-w-full min-w-max text-end">
                <x-ts-icon icon="tabler.library-photo" class="inline-block size-5" />
            </div>
        </div>
    </div>

    <div class="print:hidden">
        <x-ts-card>
            <div>
                {{-- uploads --}}
                <x-ts-upload id="uploads" wire:model="uploads" label="{{ __('person.photos') }} :" accept="{{ $acceptMimes }}"
                    hint="{{ __('person.upload_max_size', ['max' => $maxSize]) }}<br/>{{ __('person.upload_accept_types', ['types' => $this->acceptedFormats]) }}"
                    tip="{{ __('person.upload_photos_tip') }}"
                    multiple delete>
                </x-ts-upload>
            </div>

            <x-slot:footer>
                <div class="flex justify-end">
                    <x-ts-button wire:click="save()" wire:loading.attr="disabled" :disabled="empty($uploads)">
                        <span wire:loading.remove wire:target="save">{{ __('app.save') }}</span>
                        <span wire:loading wire:target="save">
                            <x-ts-icon icon="tabler.loader-2" class="inline-block size-5 animate-spin" />
                            {{ __('app.saving') ?? 'Saving...' }}
                        </span>
                    </x-ts-button>
                </div>
            </x-slot:footer>
        </x-ts-card>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 rounded-b border-neutral-100 dark:border-neutral-600 bg-neutral-200">
        @if ($this->photos->count() > 0)
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                @foreach ($this->photos as $photo)
                    <x-ts-card>
                        <x-slot:header>
                            <div class="p-4">
                                <x-ts-link href="{{ url($photo['url_original']) }}" target="_blank" class="text-sm {{ $photo['name'] === $person->photo ? ' text-yellow-500 dark:text-yellow-200' : '' }}">
                                    {{ $photo['name'] }}
                                </x-ts-link>
                            </div>
                        </x-slot:header>

                        <x-ts-link href="{{ $photo['url_original'] }}" target="_blank" title="{{ __('app.show') }}">
                            <img src="{{ $photo['url_medium'] }}" alt="{{ $photo['name'] }}" class="rounded-sm" />
                        </x-ts-link>

                        <x-slot:footer>
                            <div class="flex w-full items-center justify-between">
                                {{-- Left side --}}
                                <div class="flex items-center gap-2">
                                    @if ($photo['name'] != $person->photo)
                                        <x-ts-button color="secondary" class="p-2!" title="{{ __('person.photo_set_primary') }}" wire:click="setPrimary('{{ $photo['name'] }}')" wire:loading.attr="disabled"
                                            wire:target="setPrimary('{{ $photo['name'] }}')">
                                            <span wire:loading.remove wire:target="setPrimary('{{ $photo['name'] }}')">
                                                <x-ts-icon icon="tabler.star" class="inline-block size-5" />
                                            </span>
                                            <span wire:loading wire:target="setPrimary('{{ $photo['name'] }}')">
                                                <x-ts-icon icon="tabler.loader-2" class="inline-block size-5 animate-spin" />
                                            </span>
                                        </x-ts-button>
                                    @else
                                        <x-ts-icon icon="tabler.star-filled" class="inline-block size-5 text-yellow-500 dark:text-yellow-200" />
                                    @endif
                                </div>

                                {{-- Right side --}}
                                <div class="flex items-center gap-2">
                                    <x-ts-button href="{{ $photo['url_original'] }}" color="secondary" class="p-2!" title="{{ __('app.download') }}" download="{{ $photo['name_download'] }}">
                                        <x-ts-icon icon="tabler.download" class="inline-block size-5" />
                                    </x-ts-button>

                                    <span class="text-sm leading-none min-w-12.5 text-center">{{ $photo['size'] }}</span>

                                    <x-ts-button color="red" class="p-2! text-white" title="{{ __('app.delete') }}" wire:click="delete('{{ $photo['name'] }}')" wire:loading.attr="disabled"
                                        wire:target="delete('{{ $photo['name'] }}')">
                                        <span wire:loading.remove wire:target="delete('{{ $photo['name'] }}')">
                                            <x-ts-icon icon="tabler.trash" class="inline-block size-5" />
                                        </span>
                                        <span wire:loading wire:target="delete('{{ $photo['name'] }}')">
                                            <x-ts-icon icon="tabler.loader-2" class="inline-block size-5 animate-spin" />
                                        </span>
                                    </x-ts-button>
                                </div>
                            </div>
                        </x-slot:footer>
                    </x-ts-card>
                @endforeach
            </div>
        @else
            <div class="flex justify-center" title="{{ __('app.nothing_recorded') }}">
                <x-svg.empty-state alt="{{ __('app.nothing_recorded') }}" />
            </div>
        @endif
    </div>
</div>
