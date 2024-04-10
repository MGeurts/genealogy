<form>
    <div class="md:w-192 flex flex-col rounded mb-5 bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
        <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
            <div class="flex flex-wrap gap-2 justify-center items-start">
                <div class="flex-grow min-w-max max-w-full flex-1">
                    {{ __('person.upload_photos') }}
                </div>

                <div class="flex-grow min-w-max max-w-full flex-1 text-end"></div>
            </div>
        </div>

        <div class="p-4 bg-neutral-200 rounded-b">
            {{-- image --}}
            <x-ts-upload id="photos" wire:model="photos" accept=".jpeg, .jpg, gif, .png, .svg, .webp"
                hint="Format: <b>jpeg/jpg</b>, <b>gif</b>, <b>png</b> ,<b>svg</b> or <b>webp</b><br/>Max: <b>1024 KB</b>" tip="{{ __('person.update_photo_tip') }} ..." multiple delete>
                <x-slot:footer when-uploaded>
                    <x-ts-button class="w-full" wire:click="savePhotos()">
                        {{ __('app.save') }}
                    </x-ts-button>
                </x-slot:footer>
            </x-ts-upload>
        </div>
    </div>

    @if (count($images) > 0)
        <div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
                <div class="flex flex-wrap gap-2 justify-center items-start">
                    <div class="flex-grow min-w-max max-w-full flex-1">
                        {{ __('person.photos_existing') }} <x-ts-badge color="emerald" text="{{ count($images) }}" />
                    </div>

                    <div class="flex-grow min-w-max max-w-full flex-1 text-end"></div>
                </div>
            </div>

            <div class="p-4 bg-neutral-200 rounded-b">
                <div class="grid grid-cols-3 gap-2">
                    @foreach ($images as $image)
                        <x-ts-card class="!p-2">
                            <x-slot:header>
                                <div class="text-sm {{ $image['name'] == $person->photo ? ' text-primary-500' : '' }}">
                                    {{ $image['name'] }}
                                </div>
                            </x-slot:header>

                            <x-slot:body>
                                {{ $image['name'] }}
                            </x-slot:body>

                            <img class="rounded" src="{{ $image['url'] }}" alt="{{ $image['name'] }}" />

                            <x-slot:footer>
                                @if ($image['name'] != $person->photo)
                                    <x-ts-button color="secondary" class="!p-2" title="{{ __('person.set_primary') }}" wire:click="setPrimary('{{ $image['name'] }}')">
                                        <x-icon.tabler icon="number-1" class="!size-4" />
                                    </x-ts-button>
                                @endif

                                <x-ts-button href="{{ $image['url'] }}" color="secondary" class="!p-2" title="{{ __('app.download') }}" download="{{ $image['name_download'] }}">
                                    <x-icon.tabler icon="download" class="!size-4" />
                                </x-ts-button>

                                <div class="text-sm">{{ $image['size'] }}</div>

                                <x-ts-button color="danger" class="!p-2" title="{{ __('app.delete') }}" wire:click="deletePhoto('{{ $image['name'] }}')">
                                    <x-icon.tabler icon="trash" class="!size-4" />
                                </x-ts-button>
                            </x-slot:footer>
                        </x-ts-card>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</form>
