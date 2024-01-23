<div>
    @if (auth()->user()->hasPermission('person:update'))
        <div class="py-1 h-10 flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                @if (count($images) > 0)
                    <x-button.danger class="!p-2" wire:click="deleteImage()" title="{{ __('app.delete_photo') }}">
                        <x-icon.tabler icon="trash" class="!size-4" />
                    </x-button.danger>
                @endif
            </div>

            <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                @if (count($images) < $maxImages)
                    <a wire:navigate href="/people/{{ $person->id }}/add-photo">
                        <x-button.success class="!p-2" title="{{ __('person.add_photo') }}">
                            <x-icon.tabler icon="photo-plus" class="!size-4" />
                        </x-button.success>
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="py-1 h-10">&nbsp</div>
    @endif

    <div class="user-image">
        @if (count($images) > 0)
            <img class="w-96 rounded shadow-lg dark:shadow-black/30" src="{{ asset('storage/photos/' . $images[$selected]) }}" alt="image {{ $person->name }}" />
        @else
            <x-svg.person-no-image class="w-full rounded shadow-lg dark:shadow-black/30 fill-neutral-400" alt="no-image-found" />
        @endif

        @if ($person->isDeceased())
            <div class="ribbon">{{ __('person.deceased') }}</div>
        @endif
    </div>

    @if (count($images) > 1)
        <div class="py-1 h-10 flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                <x-button.primary class="!p-2" wire:click="previousImage()">
                    <x-icon.tabler icon="chevron-left" class="!size-4" />
                </x-button.primary>
            </div>

            <div class="flex justify-center py-1 gap-5">
                @foreach ($images as $key => $item)
                    <span wire:click="selectImage({{ $key }})" class="size-5 border border-primary rounded-full cursor-pointer {{ $key == $selected ? 'bg-primary' : '' }}">
                    </span>
                @endforeach
            </div>

            <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                <x-button.primary class="!p-2" wire:click="nextImage()">
                    <x-icon.tabler icon="chevron-right" class="!size-4" />
                </x-button.primary>
            </div>
        </div>
    @else
        <div class="py-1 h-10">&nbsp</div>
    @endif
</div>
