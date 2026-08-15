<div>
    {{-- pagination --}}
    @if (count($images) > 1)
        <div class="mb-2 flex items-center justify-center print:hidden">
            {{-- previous page link --}}
            @if ($selected === 0)
                <button
                    type="button"
                    wire:click="selectImage({{ count($images) - 1 }})"
                    rel="prev"
                    class="focus:shadow-outline-blue relative inline-flex items-center rounded-l border border-gray-300 bg-white p-2 text-sm leading-5 font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-hidden active:bg-gray-100 active:text-gray-500"
                >
                    <svg class="size-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @else
                <button
                    type="button"
                    wire:click="selectImage({{ $selected - 1 }})"
                    rel="prev"
                    class="focus:shadow-outline-blue relative inline-flex items-center rounded-l border border-gray-300 bg-white p-2 text-sm leading-5 font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-hidden active:bg-gray-100 active:text-gray-500"
                >
                    <svg class="size-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @endif

            @if (count($images) <= 7)
                @foreach (range(0, count($images) - 1) as $i)
                    @if ($i === $selected)
                        <span class="relative -ml-px inline-flex cursor-default items-center border border-gray-300 bg-yellow-500 px-4 py-2 text-sm leading-5 font-medium text-gray-500 select-none dark:bg-yellow-200">
                            {{ $i + 1 }}
                        </span>
                    @else
                        <button
                            type="button"
                            wire:click="selectImage({{ $i }})"
                            class="focus:shadow-outline-blue relative -ml-px inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:z-10 focus:border-blue-300 focus:outline-hidden active:bg-gray-100 active:text-gray-700"
                        >
                            {{ $i + 1 }}
                        </button>
                    @endif
                @endforeach
            @else
                {{-- pagination elements --}}
                @if ($selected >= 2)
                    <button
                        type="button"
                        wire:click="selectImage(0)"
                        class="focus:shadow-outline-blue relative -ml-px inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:z-10 focus:border-blue-300 focus:outline-hidden active:bg-gray-100 active:text-gray-700"
                    >
                        1
                    </button>
                @endif

                @if ($selected >= 3)
                    <span
                        aria-disabled="true"
                        class="relative -ml-px inline-flex cursor-default items-center border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 select-none"
                    >
                        ...
                    </span>
                @endif

                @foreach (range(0, count($images) - 1) as $i)
                    @if ($i >= $selected - 1 and $i <= $selected + 1)
                        @if ($i === $selected)
                            <span class="relative -ml-px inline-flex cursor-default items-center border border-gray-300 bg-yellow-500 px-4 py-2 text-sm leading-5 font-medium text-gray-500 select-none dark:bg-yellow-200">
                                {{ $i + 1 }}
                            </span>
                        @else
                            <button
                                type="button"
                                wire:click="selectImage({{ $i }})"
                                class="focus:shadow-outline-blue relative -ml-px inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:z-10 focus:border-blue-300 focus:outline-hidden active:bg-gray-100 active:text-gray-700"
                            >
                                {{ $i + 1 }}
                            </button>
                        @endif
                    @endif
                @endforeach

                @if ($selected <= count($images) - 4)
                    <span
                        aria-disabled="true"
                        class="relative -ml-px inline-flex cursor-default items-center border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 select-none"
                    >
                        ...
                    </span>
                @endif

                @if ($selected <= count($images) - 3)
                    <button
                        type="button"
                        wire:click="selectImage({{ count($images) - 1 }})"
                        class="focus:shadow-outline-blue relative -ml-px inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm leading-5 font-medium text-gray-700 transition duration-150 ease-in-out hover:text-gray-500 focus:z-10 focus:border-blue-300 focus:outline-hidden active:bg-gray-100 active:text-gray-700"
                    >
                        {{ count($images) }}
                    </button>
                @endif
            @endif

            {{-- next page link --}}
            @if ($selected === count($images) - 1)
                <button
                    type="button"
                    wire:click="selectImage(0)"
                    rel="next"
                    class="focus:shadow-outline-blue relative -ml-px inline-flex items-center rounded-r border border-gray-300 bg-white p-2 text-sm leading-5 font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-hidden active:bg-gray-100 active:text-gray-500"
                >
                    <svg class="size-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @else
                <button
                    type="button"
                    wire:click="selectImage({{ $selected + 1 }})"
                    rel="next"
                    class="focus:shadow-outline-blue relative -ml-px inline-flex items-center rounded-r border border-gray-300 bg-white p-2 text-sm leading-5 font-medium text-gray-500 transition duration-150 ease-in-out hover:text-gray-400 focus:z-10 focus:border-blue-300 focus:outline-hidden active:bg-gray-100 active:text-gray-500"
                >
                    <svg class="size-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @endif
        </div>
    @endif

    {{-- image --}}
    <div class="user-image flex items-center justify-center">
        <div class="relative inline-block">
            @if (count($images) > 0 && $images[$selected]['large'] && $images[$selected]['medium'])
                <x-ts-link href="{{ $images[$selected]['large'] }}" target="_blank">
                    <img
                        class="max-w-sm rounded-sm shadow-lg dark:shadow-black/30"
                        src="{{ $images[$selected]['medium'] }}"
                        alt="{{ $person->name }}"
                        title="{{ $person->name }}"
                    />
                </x-ts-link>
            @else
                <x-svg.person-no-image
                    class="w-full max-w-sm rounded-sm fill-neutral-400 shadow-lg dark:shadow-black/30"
                    alt="no-image-found"
                />
            @endif

            @if ($person->isDeceased())
                <div class="ribbon">{{ __('person.deceased') }}</div>
            @endif
        </div>
    </div>
</div>
