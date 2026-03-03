@props(['person' => null])

@inject('photoService', 'App\Contracts\PersonPhotoServiceInterface')

<div class="user-image">
    @php
        $photoUrl = $person?->photo ? $photoService->getPrimaryPhotoUrl($person, 'medium') : null;
    @endphp

    @if ($photoUrl)
        <img {{ $attributes->merge(['class' => 'w-full rounded-sm shadow-lg dark:shadow-black/30']) }} src="{{ $photoUrl }}" alt="{{ $person->name }}" title="{{ $person->name }}" />
    @else
        <x-svg.person-no-image {{ $attributes->merge(['class' => 'w-full rounded-sm shadow-lg dark:shadow-black/30 fill-neutral-400']) }} alt="no-image-found" />
    @endif

    @if ($person->isDeceased())
        <div class="ribbon">{{ __('person.deceased') }}</div>
    @endif
</div>
