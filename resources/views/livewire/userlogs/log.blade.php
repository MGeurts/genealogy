<div class="w-full">
    @section('title')
        &vert; {{ __('user.users') }}
    @endsection

    <x-slot name="heading">
        {{ __('userlog.users_log') }}
    </x-slot>

    <div class="grow max-w-5xl overflow-x-auto py-5 dark:text-neutral-200">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            {{-- card header --}}
            <div class="h-18 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
                <div class="flex flex-wrap gap-2 justify-center items-start">
                    <div class="flex-grow min-w-max max-w-full flex-1">
                        {{ __('userlog.log') }}
                    </div>

                    <div class="flex-grow min-w-max max-w-full flex-1 text-end">
                        <x-ts-icon icon="calendar-user" class="inline-block" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm font-light">
                    <thead class="border-b font-medium dark:border-neutral-500">
                        <tr>
                            <th scope="col" class="p-2">Date</th>
                            <th scope="col" class="p-2">Hour</th>
                            <th scope="col" class="p-2">User</th>
                            <th scope="col" class="p-2">Country name</th>
                            <th scope="col" class="p-2">Country code</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($userlogs_by_date as $day => $userlogs)
                            <tr>
                                <td class="whitespace-nowrap p-2" colspan="6">
                                    <b>{{ $day }}</b> ({{ count($userlogs) }})
                                </td>
                            </tr>

                            @foreach ($userlogs as $userlog)
                                <tr>
                                    <td></td>
                                    <td class="whitespace-nowrap p-2">{{ $userlog->time }}</td>
                                    <td class="whitespace-nowrap p-2">{{ $userlog->surname }} {{ $userlog->firstname }}</td>
                                    <td class="whitespace-nowrap p-2">{{ $userlog->country_name }}</td>
                                    <td class="whitespace-nowrap p-2">{{ $userlog->country_code }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td class="whitespace-nowrap p-2" colspan="6">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- card footer --}}
            <div class="p-2 border-t-2 text-sm border-neutral-100 dark:border-neutral-600 rounded-b">
                <p class="py-0">{{ __('userlog.timespan', ['months' => $months]) }}.</p>
            </div>
        </div>
    </div>
</div>
