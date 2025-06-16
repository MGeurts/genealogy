<div class="md:w-192 flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="flex-1 grow max-w-full min-w-max">
                {{ __('person.photos') }}
                @if (count($photos) > 0)
                    <x-ts-badge color="emerald" text="{{ count($photos) }}" />
                @endif
            </div>

            <div class="flex-1 grow max-w-full min-w-max text-end">
                <x-ts-icon icon="tabler.library-photo" class="inline-block size-5" />
            </div>
        </div>
    </div>

    {{-- uploads --}}
    <div class="p-2 print:hidden">
        <x-ts-upload id="uploads" wire:model="uploads"
            label="{{ __('person.photos') }} :"
            accept="{{ implode(',', array_keys(config('app.upload_photo_accept'))) }}"
            hint="{{ __('person.upload_max_size', ['max' => config('app.upload_max_size')]) }}<br/>{{ __('person.upload_accept_types', ['types' => implode(', ', array_values(config('app.upload_photo_accept')))]) }}"
            tip="{{ __('person.upload_photos_tip') }}"
            multiple delete>
            <x-slot:footer when-uploaded>
                <x-ts-button class="w-full" wire:click="save()">
                    {{ __('app.save') }}
                </x-ts-button>
            </x-slot:footer>
        </x-ts-upload>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 rounded-b border-neutral-100 dark:border-neutral-600 bg-neutral-200">
            @if (count($photos) > 0)
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach ($photos as $photo)
                        <x-ts-card class="p-2!">
                            <x-slot:header>
                                <div class="text-sm {{ $photo['name'] === $person->photo ? ' text-yellow-500 dark:text-yellow-200' : '' }}">
                                    <x-ts-link href="{{ url($photo['url_original']) }}" target="_blank">{{ $photo['name'] }}</x-ts-link>
                                </div>
                            </x-slot:header>

                            <x-ts-link href="{{ $photo['url_original'] }}" target="_blank" title="{{ __('app.show') }}">
                                <img src="{{ $photo['url'] }}" alt="{{ $photo['name'] }}" class="rounded-sm" />
                            </x-ts-link>

                            <x-slot:footer>
                                <div class="w-full">
                                    @if ($photo['name'] != $person->photo)
                                        <x-ts-button color="secondary" class="p-2!" title="{{ __('person.set_primary') }}" wire:click="setPrimary('{{ $photo['name'] }}')">
                                            <x-ts-icon icon="tabler.star" class="inline-block size-5" />
                                        </x-ts-button>
                                    @endif
                                </div>

                                <x-ts-button href="{{ $photo['url'] }}" color="secondary" class="p-2!" title="{{ __('app.download') }}" download="{{ $photo['name_download'] }}">
                                    <x-ts-icon icon="tabler.download" class="inline-block size-5" />
                                </x-ts-button>

                                <div class="text-sm text-end">{{ $photo['size'] }}</div>

                                <x-ts-button color="red" class="p-2! text-white" title="{{ __('app.delete') }}" wire:click="deletePhoto('{{ $photo['name'] }}')">
                                    <x-ts-icon icon="tabler.trash" class="inline-block size-5" />
                                </x-ts-button>
                            </x-slot:footer>
                        </x-ts-card>
                    @endforeach
                </div>
            @else
                <div>
                    <x-ts-alert title="{{ __('person.photos') }}" text="{{ __('app.nothing_recorded') }}" color="cyan" />
                </div>
            @endif
    </div>
</div>
