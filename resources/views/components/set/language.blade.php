<x-dropdown align="right" width="48">
    <x-slot name="trigger">
        <button type="button" title="{{ __('app.language_select') }}">
            {{ strtoupper(app()->getLocale()) }}
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="block px-4 py-2 text-xs text-gray-400">
            {{ __('app.language_select') }}
        </div>

        @foreach (config('app.available_locales') as $locale_name => $available_locale)
            @if ($available_locale === app()->getLocale())
                <x-dropdown-link href="#" :active="true">
                    {{ $locale_name }}
                </x-dropdown-link>
            @else
                <x-dropdown-link href="/language/{{ $available_locale }}">
                    {{ $locale_name }}
                </x-dropdown-link>
            @endif
        @endforeach
    </x-slot>
</x-dropdown>
