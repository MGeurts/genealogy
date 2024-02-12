@isset($customDeselectOptionIcon)
    {{ $customDeselectOptionIcon }}
@else
    <x-icon.tabler icon="circle-x-filled" class="!size-6" />
@endisset
