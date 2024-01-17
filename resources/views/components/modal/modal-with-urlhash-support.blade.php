@props(['name', 'title'])

<div x-data="{ show: false, name: '{{ $name }}' }" x-show="show" x-on:hashchange.window="show = (location.hash === '#'+name)"
    x-on:open-modal.window="($event.detail.name === name) ? (show = true, location.hash = '#'+name) : '';" x-on:close-modal.window="location.hash = '#'" x-on:keydown.escape.window="location.hash = '#'"
    style="display:none;" class="fixed z-50 inset-0" x-transition.duration>

    {{-- Gray Background --}}
    <div x-on:click="location.hash = '#'" class="fixed inset-0 bg-gray-300 opacity-40"></div>

    {{-- Modal Body --}}
    <div class="bg-white rounded m-auto fixed inset-0 max-w-2xl overflow-y-auto max-h-fit">
        @if (isset($title))
            <div class="px-4 py-3 flex items-center justify-between border-b border-gray-300">
                <div class="text-xl text-gray-800">{{ $title }}</div>
                <a href="#">
                    <x-icon.tabler icon="x" class="!size-6" />
                </a>
            </div>
        @endif
        <div class="p-4">
            {{ $body }}
        </div>
    </div>
</div>
