<div>
    {{-- pagination --}}
    <div class="flex items-center justify-center mb-2 print:hidden">
        {{ $photos->links() }}
    </div>

    {{-- image --}}
    <div class="user-image">
        @if ($tempUrl = $photos->first()?->getTemporaryUrl(now()->addDay()))
            <x-ts-link href="{{ $tempUrl  }}" target="_blank">
                <img class="rounded-sm shadow-lg w-96 dark:shadow-black/30" src="{{ $tempUrl }}" alt="{{ $person->name }}"
                    title="{{ $person->name }}" />
            </x-ts-link>
        @else
            <x-svg.person-no-image class="w-full rounded-sm shadow-lg dark:shadow-black/30 fill-neutral-400" alt="no-image-found" />
        @endif

        @if ($person->isDeceased())
            <div class="ribbon">{{ __('person.deceased') }}</div>
        @endif
    </div>
</div>
