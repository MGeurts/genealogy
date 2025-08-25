@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        {{-- Mobile (sm:hidden) --}}
        <div class="flex justify-between flex-1 sm:hidden">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <x-ts-button disabled size="sm" color="secondary">
                    {!! __('pagination.previous') !!}
                </x-ts-button>
            @else
                <x-ts-button tag="a" href="{{ $paginator->previousPageUrl() }}" rel="prev" size="sm" color="secondary">
                    {!! __('pagination.previous') !!}
                </x-ts-button>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <x-ts-button tag="a" href="{{ $paginator->nextPageUrl() }}" rel="next" size="sm" color="secondary">
                    {!! __('pagination.next') !!}
                </x-ts-button>
            @else
                <x-ts-button disabled size="sm" color="secondary">
                    {!! __('pagination.next') !!}
                </x-ts-button>
            @endif
        </div>

        {{-- Desktop (sm:flex) --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-center">
            <div class="inline-flex space-x-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <x-ts-button disabled size="sm" icon="chevron-left" color="secondary" />
                @else
                    <x-ts-button tag="a" href="{{ $paginator->previousPageUrl() }}" rel="prev" size="sm" icon="chevron-left" color="secondary" />
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <x-ts-button disabled size="sm" color="secondary">{{ $element }}</x-ts-button>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <x-ts-button size="sm" color="primary">{{ $page }}</x-ts-button>
                            @else
                                <x-ts-button tag="a" href="{{ $url }}" size="sm" color="secondary">{{ $page }}</x-ts-button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <x-ts-button tag="a" href="{{ $paginator->nextPageUrl() }}" rel="next" size="sm" icon="chevron-right" color="secondary" />
                @else
                    <x-ts-button disabled size="sm" icon="chevron-right" color="secondary" />
                @endif
            </div>
        </div>
    </nav>
@endif
