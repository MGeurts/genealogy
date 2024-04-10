<div class="flex text-xs justify-between p-2 md:px-5 bg-neutral-200 dark:bg-neutral-700">
    <div class="text-left">
        Copyright © {{ date('Y') }} | <x-link href="https://www.kreaweb.be/" target="_blank">KREAWEB</x-link>.<br />
        {{ __('app.open_source') }} <x-link href="/about">{{ __('app.licence') }}</x-link>.<br />
        {{ __('app.free_use') }}.
    </div>

    <div class="flex items-center">
        <div class="text-right px-2">
            {{ __('app.design_development') }}<br />
            {{ __('app.by') }} <x-link href="https://www.kreaweb.be/" target="_blank">KREAWEB</x-link>
        </div>

        <a href="https://www.kreaweb.be/" target="_blank" title="Kreaweb">
            <x-svg.kreaweb class="size-11 dark:fill-white hover:fill-primary-300 dark:hover:fill-primary-300" alt="kreaweb" />
        </a>
    </div>
</div>
