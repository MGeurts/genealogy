@props(['submit'])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-5']) }}>
    <x-section-title>
        @if (isset($title))
            <x-slot name="title">{{ $title }}</x-slot>
        @endif

        @if (isset($description))
            <x-slot name="description">{{ $description }}</x-slot>
        @endif
    </x-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <form wire:submit="{{ $submit }}">
            @csrf

            <div class="p-4 bg-white sm:p-6 {{ isset($actions) ? 'rounded-tl rounded-tr' : 'rounded-sm' }}">
                <div class="grid grid-cols-6 gap-5">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end p-4 text-right bg-gray-200 sm:px-6 rounded-b">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
