<div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1">
                {{ __('person.siblings') }} <x-ts-badge color="emerald" text="{{ count($siblings) }}" />
            </div>
        </div>
    </div>

    @if (count($siblings) > 0)
        @foreach ($siblings as $sibling)
            <p @if ($loop->last) class="p-2" @else class="p-2 border-b" @endif>
                <x-link href="/people/{{ $sibling->id }}" class="{{ $sibling->isDeceased() ? 'text-danger-600 dark:!text-danger-400' : '' }}">
                    {{ $sibling->name }}
                </x-link>
                <x-icon.tabler icon="{{ $sibling->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                <span class="text-warning-500">{{ $sibling->type }}</span>
            </p>
        @endforeach
    @else
        <p class="p-2">{{ __('app.nothing_recorded') }}</p>
    @endif
</div>
