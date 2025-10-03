@props(['person' => null])

<div class="user-image">
    @php
        $photoPath = $person->team_id . '/' . $person->id . '/' . $person->photo . '_medium.webp';
    @endphp
    @if ($person->photo && Storage::disk('photos')->exists($photoPath))
        <img {{ $attributes->merge(['class' => 'w-full rounded-sm shadow-lg dark:shadow-black/30']) }} src="{{ Storage::disk('photos')->url($photoPath) }}" alt="{{ $person->name }}" title="{{ $person->name }}" />
    @else
        <x-svg.person-no-image {{ $attributes->merge(['class' => 'w-full rounded-sm shadow-lg dark:shadow-black/30 fill-neutral-400']) }} alt="no-image-found" />
    @endif

    @if ($person->isDeceased())
        <div class="ribbon">{{ __('person.deceased') }}</div>
    @endif
</div>
