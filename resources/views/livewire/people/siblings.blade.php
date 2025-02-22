<div class="min-w-80 flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="flex-1 grow max-w-full min-w-max">
                {{ __('person.siblings') }}
                @if (count($siblings) > 0)
                    <x-ts-badge color="emerald" text="{{ count($siblings) }}" />
                @endif
            </div>
        </div>
    </div>

    @if (count($siblings) > 0)
        @foreach ($siblings as $sibling)
            <p @if ($loop->last) class="p-2" @else class="p-2 border-b" @endif>
                <x-link href="/people/{{ $sibling->id }}" @class(['text-red-600 dark:text-red-400' => $sibling->isDeceased()])>
                    {{ $sibling->name }}
                </x-link>
                <x-ts-icon icon="tabler.{{ $sibling->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                <span class="text-yellow-500">{{ $sibling->type }}</span>
            </p>
        @endforeach
    @else
        <p class="p-2">{{ __('app.nothing_recorded') }}</p>
    @endif
</div>
