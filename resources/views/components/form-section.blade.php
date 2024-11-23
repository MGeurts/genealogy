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

            <div class="px-4 py-5 bg-white sm:p-6 {{ isset($actions) ? 'sm:rounded-tl sm:rounded-tr' : 'sm:rounded' }}">
                <div class="grid grid-cols-6 gap-5">
                    {{ $form }}
                </div>
            </div>

            @if (isset($actions))
                <div class="flex items-center justify-end px-4 py-3 text-right bg-gray-200 sm:px-6 sm:rounded-bl sm:rounded-br">
                    {{ $actions }}
                </div>
            @endif
        </form>
    </div>
</div>
