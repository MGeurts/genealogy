<div class="w-full" x-data="{
    init() {
        const cData = {{ $chart_data }};

        const data = {
            labels: cData.labels,
            datasets: [{
                label: 'Visitors',
                borderWidth: 1,
                data: cData.data,
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                scaleIntegersOnly: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        }
                    }
                }
            }
        };

        // destroy any previous chart
        if (Chart.getChart('mainChart')) {
            Chart.getChart('mainChart').destroy();
        }

        let myChart = new Chart(this.$refs.canvas, config);
    }
}">
    @section('title')
        &vert; {{ __('user.users') }}
    @endsection

    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('userlog.users_stats') }}
        </h2>
    </x-slot>

    <div class="grow max-w-5xl overflow-x-auto py-5 dark:text-neutral-200">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            {{-- card header --}}
            <div class="h-18 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
                <div class="flex flex-wrap gap-2 justify-center items-start">
                    <div class="flex-grow min-w-max max-w-full flex-1">
                        {{ __('userlog.period') }}
                    </div>

                    <div class="w-48">
                        <x-ts-select.styled wire:model.live="period" name="period" id="period" :options="$options" select="label:label|value:value" required />
                    </div>

                    <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                        <x-ts-icon icon="chart-bar" class="inline-block" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="rounded-b overflow-x-auto">
                <div class="min-w-full p-5 h-128">
                    <canvas id="mainChart" x-ref="canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
