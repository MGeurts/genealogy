<div class="w-full" x-data="{
    mapData: @js($countriesData),

    mapInitialized: false, // Flag to prevent re-initialization

    init() {
        if (!this.mapInitialized) {
            new svgMap({
                targetElementID: 'svgMap',
                data: {
                    data: {
                        visitors: {
                            name: 'Visitors',
                            format: '{0}',
                            thousandSeparator: ','
                        }
                    },
                    applyData: 'visitors',
                    values: this.mapData
                }
            });

            this.mapInitialized = true; // Set flag to true after initialization
        }
    }
}" x-init="init">
    @section('title')
        &vert; {{ __('user.users') }}
    @endsection

    <x-slot name="heading">
        {{ __('userlog.users_origin') }}
    </x-slot>

    <div class="max-w-7xl py-5 overflow-x-auto grow dark:text-neutral-200">
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
</div>
