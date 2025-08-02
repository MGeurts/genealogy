@props(['person' => null])

<div class="user-image">
    @if ($tempUrl = $person->getFirstTemporaryUrl(now()->addHour()))
        <img {{ $attributes->merge(['class' => 'w-full rounded-sm shadow-lg dark:shadow-black/30']) }} src="{{ $tempUrl }}"
            alt="{{ $person->name }}" title="{{ $person->name }}" />
    @else
        <x-svg.person-no-image {{ $attributes->merge(['class' => 'w-full rounded-sm shadow-lg dark:shadow-black/30 fill-neutral-400']) }} alt="no-image-found" />
    @endif

    @if ($person->isDeceased())
        <div class="ribbon">{{ __('person.deceased') }}</div>
    @endif
</div>
