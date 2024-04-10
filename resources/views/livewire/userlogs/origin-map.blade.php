<div class="w-full">
    @section('title')
        &vert; {{ __('userlog.users_log') }}
    @endsection

    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('userlog.users_origin') }}
        </h2>
    </x-slot>

    <div class="grow max-w-5xl overflow-x-auto py-5 dark:text-neutral-200">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            {{-- card header --}}
            <div class="h-18 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
                <div class="flex flex-wrap gap-2 justify-center items-start">
                    <div class="flex-grow min-w-max max-w-full flex-1">
                        {{ __('userlog.worldmap') }}
                    </div>

                    <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                        <x-icon.tabler icon="world-pin" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="rounded-b overflow-x-auto">
                <div class="min-w-full" id="map_div">
                    <?= $lava->render('GeoChart', 'Visitors', 'map_div') ?>
                </div>
            </div>
        </div>
    </div>
</div>
