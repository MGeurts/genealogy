<div x-cloak class="relative inline-flex items-center gap-10 sm:gap-2">
    {{-- light --}}
    <button x-on:click="darkMode='light'" title="{{ __('app.enable_light') }}">
        <x-icon.tabler icon="sun" x-bind:class="{ 'border-2 border-red/50': darkMode === 'light' }"
            class="w-6 h-6 p-1 text-gray-700 transition rounded-full cursor-pointer bg-gray-50 hover:bg-gray-200" />
    </button>

    {{-- Dark --}}
    <button x-on:click="darkMode='dark'" title="{{ __('app.enable_dark') }}">
        <x-icon.tabler icon="moon" x-bind:class="{ 'border-2 border-red/50': darkMode === 'dark' }"
            class="w-6 h-6 p-1 text-gray-100 transition bg-gray-700 rounded-full cursor-pointer dark:hover:bg-gray-600" />
    </button>

    {{-- System --}}
    <button x-on:click="darkMode='system'" title="{{ __('app.enable_system') }}">
        <x-icon.tabler icon="settings" x-cloak x-bind:class="{ 'border-2 border-red/50': darkMode === 'system' }"
            class="w-6 h-6 p-1 text-gray-700 transition bg-gray-100 rounded-full cursor-pointer hover:bg-gray-200" x-show="! window.matchMedia('(prefers-color-scheme: dark)').matches" />

        <x-icon.tabler icon="settings" x-cloak x-bind:class="{ 'border-2 border-red/50': darkMode === 'system' }"
            class="w-6 h-6 p-1 text-gray-100 transition bg-gray-700 rounded-full cursor-pointer dark:hover:bg-gray-600" x-show="window.matchMedia('(prefers-color-scheme: dark)').matches" />
    </button>
</div>
