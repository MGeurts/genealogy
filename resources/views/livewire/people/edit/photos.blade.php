<div class="md:w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    {{-- card header --}}
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                {{ __('person.photos') }}
                @if (count($images) > 0)
                    <x-ts-badge color="emerald" text="{{ count($images) }}" />
                @endif
            </div>

            <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                <x-ts-icon icon="library-photo" class="inline-block" />
            </div>
        </div>
    </div>

    <div class="p-5 grid grid-cols-1 gap-5">
        {{-- image upload --}}
        <form id="form">
            <x-ts-upload id="photos" wire:model="photos" accept=".jpeg, .jpg, .gif, .png, .svg, .webp" hint="Max: 1024 KB, Format: jpeg/jpg, gif, png, svg, webp"
                tip="{{ __('person.update_photos_tip') }} ..." multiple delete>
                <x-slot:footer when-uploaded>
                    <x-ts-button class="w-full" wire:click="save()">
                        {{ __('app.save') }}
                    </x-ts-button>
                </x-slot:footer>
            </x-ts-upload>
        </form>
    </div>

    {{-- card body --}}
    <div class="p-2 text-sm border-t-2 border-neutral-100 dark:border-neutral-600 rounded-b bg-neutral-200">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
            @if (count($images) > 0)
                @foreach ($images as $image)
                    <x-ts-card class="!p-2">
                        <x-slot:header>
                            <div class="text-sm {{ $image['name'] == $person->photo ? ' text-warning-500 dark:text-warning-200' : '' }}">
                                {{ $image['name'] }}
                            </div>
                        </x-slot:header>

                        <img src="{{ $image['url'] }}" alt="{{ $image['name'] }}" class="rounded" />

                        <x-slot:footer>
                            @if ($image['name'] != $person->photo)
                                <x-ts-button color="secondary" class="!p-2" title="{{ __('person.set_primary') }}" wire:click="setPrimary('{{ $image['name'] }}')">
                                    <x-ts-icon icon="number-1" class="size-5" />
                                </x-ts-button>
                            @endif

                            <x-ts-button href="{{ $image['url'] }}" color="secondary" class="!p-2" title="{{ __('app.download') }}" download="{{ $image['name_download'] }}">
                                <x-ts-icon icon="download" class="size-5" />
                            </x-ts-button>

                            <div class="text-sm">{{ $image['size'] }}</div>

                            <x-ts-button color="danger" class="!p-2" title="{{ __('app.delete') }}" wire:click="deletePhoto('{{ $image['name'] }}')">
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
