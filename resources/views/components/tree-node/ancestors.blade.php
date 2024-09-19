@props(['person', 'ancestors', 'level_current' => 0, 'level_max'])

@php
    $level_current++;

    $person_sequence = $ancestors->firstWhere('id', $person->id)->sequence;

    $ancestors_next = $ancestors->where('degree', $level_current)->filter(function ($item) use ($person_sequence) {
        return strpos($item->sequence, $person_sequence) !== false;
    });
@endphp

<li>
    @if ($person)
        <x-link href="/people/{{ $person->id }}" title="{{ $person->sex === 'm' ? __('app.male') : __('app.female') }}">
            <figure class="w-24">
                <div class="user-image">
                    @if ($person->photo and Storage::exists('public/photos/' . $person->team_id . '/' . $person->photo))
                        <img src="{{ asset('storage/photos-096/' . $person->team_id . '/' . $person->photo) }}" class="w-full rounded shadow-lg dark:shadow-black/30" alt="{{ $person->id }}" />
                    @else
                        <x-svg.person-no-image class="w-full rounded shadow-lg dark:shadow-black/30 fill-neutral-400" alt="no-image-found" />
                    @endif

                    @if ($person->dod or $person->yod)
                        <div class="ribbon">{{ __('person.deceased') }}</div>
                    @endif
                </div>

                <figcaption @class([
                    'text-danger-600 dark:text-danger-400' => ($person->dod or $person->yod),
                    'text-primary-500 dark:text-primary-300' => !($person->dod or $person->yod)
                ])>
                    {{ implode(' ', array_filter([$person->firstname, $person->surname])) }}
                </figcaption>
            </figure>
        </x-link>

        {{-- ancestors (recursive) --}}
        @if ($level_current < $level_max)
            @if (count($ancestors_next) > 0)
                <ul>
                    @foreach ($ancestors_next as $ancestor)
                        <x-tree-node.ancestors :person="$ancestor" :ancestors="$ancestors" :level_current="$level_current" :level_max="$level_max" />
                    @endforeach
                </ul>
            @endif
        @endif
    @endif
</li>
