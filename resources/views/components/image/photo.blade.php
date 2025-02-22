@props(['person' => null])

<div class="user-image">
    @if ($person->photo and Storage::exists('public/photos/' . $person->team_id . '/' . $person->photo))
        <img {{ $attributes->merge(['class' => 'w-full rounded-sm shadow-lg dark:shadow-black/30']) }} src="{{ asset('storage/photos/' . $person->team_id . '/' . $person->photo) }}"
            alt="{{ $person->name }}" title="{{ $person->name }}" />
    @else
        <x-svg.person-no-image {{ $attributes->merge(['class' => 'w-full rounded-sm shadow-lg dark:shadow-black/30 fill-neutral-400']) }} alt="no-image-found" />
    @endif

    @if ($person->isDeceased())
        <div class="ribbon">{{ __('person.deceased') }}</div>
    @endif
</div>
