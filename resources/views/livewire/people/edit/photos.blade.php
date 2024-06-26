<div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                {{ __('person.photos') }}
                @if (count($photos) > 0)
                    <x-ts-badge color="emerald" text="{{ count($photos) }}" />
                @endif
            </div>

            <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                <x-ts-icon icon="library-photo" class="inline-block" />
            </div>
        </div>
    </div>

    {{-- upload --}}
    <div class="print:hidden p-2">
        <x-ts-upload id="uploads" wire:model="uploads" accept=".jpeg, .jpg, .gif, .png, .svg, .webp" hint="Max: 1024 KB, Format: jpeg/jpg, gif, png, svg, webp"
            tip="{{ __('person.update_photos_tip') }} ..." multiple delete>
            <x-slot:footer when-uploaded>
                <x-ts-button class="w-full" wire:click="save()">
                    {{ __('app.save') }}
                </x-ts-button>
            </x-slot:footer>
        </x-ts-upload>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 border-neutral-100 dark:border-neutral-600 rounded-b bg-neutral-200">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            @if (count($photos) > 0)
                @foreach ($photos as $photo)
                    <x-ts-card class="!p-2">
                        <x-slot:header>
                            <div class="text-sm {{ $photo['name'] == $person->photo ? ' text-warning-500 dark:text-warning-200' : '' }}">
                                <x-ts-link href="{{ url($photo['url_original']) }}" target="_blank">{{ $photo['name'] }}</x-ts-link>
                            </div>
                        </x-slot:header>

                        <x-ts-link href="{{ $photo['url_original'] }}" target="_blank" title="{{ __('app.show') }}">
                            <img src="{{ $photo['url'] }}" alt="{{ $photo['name'] }}" class="rounded" />
                        </x-ts-link>

                        <x-slot:footer>
                            <div class="w-full">
                                @if ($photo['name'] != $person->photo)
                                    <x-ts-button color="secondary" class="!p-2" title="{{ __('person.set_primary') }}" wire:click="setPrimary('{{ $photo['name'] }}')">
                                        <x-ts-icon icon="star" class="size-5" />
                                    </x-ts-button>
                                @endif
                            </div>

                            <x-ts-button href="{{ $photo['url'] }}" color="secondary" class="!p-2" title="{{ __('app.download') }}" download="{{ $photo['name_download'] }}">
                                <x-ts-icon icon="download" class="size-5" />
                            </x-ts-button>

                            <div class="text-sm text-end">{{ $photo['size'] }}</div>

                            <x-ts-button color="danger" class="!p-2 text-white" title="{{ __('app.delete') }}" wire:click="deletePhoto('{{ $photo['name'] }}')">
                                <x-ts-icon icon="trash" class="size-5" />
                            </x-ts-button>
                        </x-slot:footer>
                    </x-ts-card>
                @endforeach
            @else
                <x-ts-alert title="{{ __('person.photos') }}" text="{{ __('app.nothing_recorded') }}" color="secondary" />
            @endif
        </div>
    </div>
</div>
