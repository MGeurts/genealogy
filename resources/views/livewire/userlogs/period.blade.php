<div class="w-full" x-data="{
    init() {
        let cData = {{ $chart_data }};

        let data = {
            labels: cData.labels,
            datasets: [{
                label: 'Visitors',
                borderWidth: 1,
                data: cData.data,
            }]
        };

        let config = {
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
        {{ __('userlog.users_stats') }}
    </x-slot>

    <div class="max-w-7xl py-5 overflow-x-auto grow dark:text-neutral-200">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-18 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="flex-1 flex-grow max-w-full min-w-max">
                        {{ __('userlog.period') }}
                    </div>

                    <div class="w-32">
                        <x-ts-select.styled wire:model.live="year" name="year" id="year" :options="$years" select="label:label|value:value" required />
                    </div>

                    <div class="w-32">
                        <x-ts-select.styled wire:model.live="period" name="period" id="period" :options="$periods" select="label:label|value:value" required />
                    </div>

                    <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                        <x-ts-icon icon="chart-bar" class="inline-block" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="overflow-x-auto rounded-b">
                <div class="min-w-full p-5">
                    <canvas id="mainChart" x-ref="canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
