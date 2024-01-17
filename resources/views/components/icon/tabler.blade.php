<!-- tabler icon, pulls from public/tabler folder. Accepts:
    strokeWidth   defaults to "2", values between 1 and 2 are possible
    class         defaults to "inline-block relative size-5"

    download : https://github.com/tabler/tabler-icons/blob/master/packages/icons/tabler-sprite-nostroke.svg
-->

<svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="{{ $strokeWidth ?? 2 }}" class="inline-block relative size-5 {{ $class ?? '' }}" {{ $attributes->except(['class', 'icon']) }}>
    <use xlink:href="/tabler/tabler-sprite-nostroke.svg#tabler-{{ $icon }}" />
</svg>
