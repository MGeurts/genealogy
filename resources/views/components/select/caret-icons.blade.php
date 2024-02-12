<span x-show="!open">
    @isset($customCaretDownIcon)
        {{ $customCaretDownIcon }}
    @else
        <x-icon.tabler icon="chevron-down" class="!size-6" />
    @endisset
</span>

<span x-show="open">
    @isset($customCaretUpIcon)
        {{ $customCaretUpIcon }}
    @else
        <x-icon.tabler icon="chevron-up" class="!size-6" />
    @endisset
</span>
