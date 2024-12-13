@section('title')
    &vert; {{ __('user.users') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('userlog.users_stats') }}
    </x-slot>

    <div class="max-w-7xl py-5 overflow-x-auto grow dark:text-neutral-200">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-18 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="flex-1 flex-grow max-w-full min-w-max">
                        {{ __('userlog.period') }} : {{ __('userlog.month') }} ({{ date('Y') }})
                    </div>

                    <div class="flex-1 flex-grow min-w-max max-w-min text-end">
                        <x-ts-icon icon="chart-bar" class="inline-block" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="overflow-x-auto rounded-b">
                <div class="min-w-full p-5">
                    <canvas id="visitorChartMonth"></canvas>
                </div>
            </div>
        </div>

        <br /><br />

        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-18 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="flex-1 flex-grow max-w-full min-w-max">
                        {{ __('userlog.period') }} : {{ __('userlog.year') }}
                    </div>

                    <div class="flex-1 flex-grow min-w-max max-w-min text-end">
                        <x-ts-icon icon="chart-bar" class="inline-block" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="overflow-x-auto rounded-b">
                <div class="min-w-full p-5">
                    <canvas id="visitorChartYear"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let ctxMonth = document.getElementById('visitorChartMonth').getContext('2d');

        let chartMonth = new Chart(ctxMonth, {
            type: 'bar',
            data: {
                labels: @json($statistics_month_labels),
                datasets: [{
                    label: @json($title),
                    data: @json($statistics_month_values),
                    borderWidth: 1
                }]
            },
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
        });

        let ctxYear = document.getElementById('visitorChartYear').getContext('2d');

        let chartYear = new Chart(ctxYear, {
            type: 'bar',
            data: {
                labels: @json($statistics_year_labels),
                datasets: [{
                    label: @json($title),
                    data: @json($statistics_year_values),
                    borderWidth: 1
                }]
            },
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
        });
    </script>
</x-app-layout>
