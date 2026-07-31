@section('title')
    &vert; {{ __('user.users') }}
@endsection

<x-app-layout>
    <div class="max-w-7xl grow overflow-x-auto p-2 dark:text-neutral-200">
        <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="max-w-full min-w-max flex-1 grow">{{ __('userlog.countries') }}</div>

                    <div class="max-w-min min-w-max flex-1 grow text-end">
                        <x-ts-icon icon="tabler.chart-bar" class="inline-block size-5" />
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('mainChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @js($labels),
                datasets: [
                    {
                        label: @js($title),
                        data: @js($values),
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                scaleIntegersOnly: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });
    </script>
</x-app-layout>
