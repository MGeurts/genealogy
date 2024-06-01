<div>
    {{-- pagination --}}
    @if (count($images) > 1)
        <div class="print:hidden mb-2 flex items-center justify-center">
            {{-- previous page link --}}
            @if ($selected == 0)
                <button type="button" wire:click="selectImage({{ count($images) - 1 }})" rel="prev"
                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @else
                <button type="button" wire:click="selectImage({{ $selected - 1 }})" rel="prev"
                    class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @endif

            @if (count($images) <= 7)
                @foreach (range(0, count($images) - 1) as $i)
                    @if ($i == $selected)
                        <span
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-warning-500 dark:bg-warning-200 border border-gray-300 cursor-default leading-5 select-none">
                            {{ $i + 1 }}
                        </span>
                    @else
                        <button type="button" wire:click="selectImage({{ $i }})"
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            {{ $i + 1 }}
                        </button>
                    @endif
                @endforeach
            @else
                {{-- pagination elements --}}
                @if ($selected >= 2)
                    <button type="button" wire:click="selectImage(0)"
                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                        1
                    </button>
                @endif

                @if ($selected >= 3)
                    <span aria-disabled="true"
                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5 select-none">
                        ...
                    </span>
                @endif

                @foreach (range(0, count($images) - 1) as $i)
                    @if ($i >= $selected - 1 && $i <= $selected + 1)
                        @if ($i == $selected)
                            <span
                                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-warning-500 dark:bg-warning-200 border border-gray-300 cursor-default leading-5 select-none">
                                {{ $i + 1 }}
                            </span>
                        @else
                            <button type="button" wire:click="selectImage({{ $i }})"
                                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                {{ $i + 1 }}
                            </button>
                        @endif
                    @endif
                @endforeach

                @if ($selected <= count($images) - 4)
                    <span aria-disabled="true"
                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5 select-none">
                        ...
                    </span>
                @endif

                @if ($selected <= count($images) - 3)
                    <button type="button" wire:click="selectImage({{ count($images) - 1 }})"
                        class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                        {{ count($images) }}
                    </button>
                @endif
            @endif

            {{-- next page link --}}
            @if ($selected == count($images) - 1)
                <button type="button" wire:click="selectImage(0)" rel="next"
                    class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @else
                <button type="button" wire:click="selectImage({{ $selected + 1 }})" rel="next"
                    class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @endif
        </div>
    @endif

    {{-- image --}}
    <div class="user-image">
        @if (count($images) > 0)
            <img class="w-96 rounded shadow-lg dark:shadow-black/30" src="{{ asset('storage/photos/' . $person->team_id . '/' . $images[$selected]) }}" alt="image {{ $person->name }}" />
        @else
            <x-svg.person-no-image class="w-full rounded shadow-lg dark:shadow-black/30 fill-neutral-400" alt="no-image-found" />
        @endif

        @if ($person->isDeceased())
            <div class="ribbon">{{ __('person.deceased') }}</div>
        @endif
    </div>
</div>
