<div class="max-w-3xl">
    <x-ts-card class="max-w-max min-w-max">
        <x-slot:header>
            <div class="flex items-center justify-between w-full">
                {{ __('person.similar_persons') }}
                <x-ts-icon icon="tabler.users" class="inline-block size-5" />
            </div>
        </x-slot:header>

        @if ($this->similarPersons->isEmpty())
            <p class="text-neutral-400">
                {{ __('person.no_similar_persons') }}
            </p>
        @else
            <div class="flex flex-col gap-2 min-w-max">
                @foreach ($this->similarPersons as $person)
                    <a href="{{ url('/people/' . $person->id) }}" @class([
                        'flex items-center gap-6 p-2 rounded hover:bg-neutral-100 dark:hover:bg-neutral-600 transition-colors group',
                        'text-red-600 dark:text-red-400' => $person->isDeceased(),
                    ])>

                        {{-- Photo / avatar --}}
                        <div class="size-10 shrink-0 overflow-hidden bg-neutral-200 dark:bg-neutral-600 flex items-center justify-center">
                            @if ($person->photo)
                                <img src="{{ Storage::url('photos/' . $person->team->id . '/' . $person->id . '/' . $person->photo . '.webp') }}" alt="{{ $person->name }}" class="w-full h-full object-cover" />
                            @else
                                <x-ts-icon icon="tabler.user" class="size-5 text-neutral-400" />
                            @endif
                        </div>

                        {{-- Name + birth info --}}
                        <div class="flex flex-col leading-tight min-w-40">
                            <span class="font-medium group-hover:underline">
                                {{ $person->name }}
                            </span>
                            <span class="text-neutral-400">
                                {{ $person->birth_formatted }}

                                @if ($person->pob)
                                    &middot; {{ $person->pob }}
                                @endif
                            </span>
                        </div>

                        {{-- Sex badge --}}
                        <div class="ml-auto shrink-0">
                            <x-ts-icon icon="tabler.{{ $person->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        <x-slot:footer>
            <p class="text-sm text-neutral-400">
                {{ __('person.similar_persons_hint') }}
            </p>
        </x-slot:footer>
    </x-ts-card>
</div>
