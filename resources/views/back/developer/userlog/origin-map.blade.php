@section('title')
    &vert; {{ __('user.users') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('userlog.users_origin') }}
    </x-slot>

    <div class="p-2 max-w-7xl overflow-x-auto grow dark:text-neutral-200">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-18 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="flex-1 flex-grow max-w-full min-w-max">
                        {{ __('userlog.worldmap') }}
                    </div>

                    <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                        <x-ts-icon icon="world-pin" class="inline-block" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="overflow-x-auto rounded-b">
                <div class="min-w-full" id="map_div">
                    <div id="svgMap"></div>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/gh/StephanWagner/svgMap@v2.12.0/dist/svgMap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/svg-pan-zoom@3.6.1/dist/svg-pan-zoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/StephanWagner/svgMap@v2.12.0/dist/svgMap.min.js"></script>

    <script>
        const map = new svgMap({
            countryNames: @json($countries),
            targetElementID: 'svgMap',
            flagType: 'emoji',
            noDataText: @json($nodata),
            data: {
                data: {
                    visitors: {
                        name: @json($title),
                        format: '{0}',
                        thousandSeparator: ',',
                    }
                },
                applyData: 'visitors',
                values: @json($data),
            },
        });
    </script>
</x-app-layout>
