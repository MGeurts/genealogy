@props(['name', 'title'])

<div x-data="{ show: false, name: '{{ $name }}' }" x-show="show" x-on:open-modal.window="show = ($event.detail.name === name)" x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false"
    style="display:none;" class="fixed z-50 inset-0" x-transition.duration>


    {{-- Gray Background --}}
    <div x-on:click="show = false" class="fixed inset-0 bg-gray-300 opacity-40"></div>

    {{-- Modal Body --}}
    <div class="bg-white rounded m-auto fixed inset-0 max-w-2xl overflow-y-auto max-h-fit">
        @if (isset($title))
            <div class="px-4 py-3 flex items-center justify-between border-b border-gray-300">
                <div class="text-xl text-gray-800">{{ $title }}</div>
                <button x-on:click="$dispatch('close-modal')">
                    <x-ts-icon icon="x" />
                </button>
            </div>
        @endif

        <div class="p-4">
            {{ $body }}
        </div>
    </div>
</div>
