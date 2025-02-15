@section('title')
    &vert; {{ __('user.users') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('userlog.users_log') }}
    </x-slot>

    <div class="p-2 max-w-5xl overflow-x-auto grow dark:text-neutral-200">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-18 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="flex-1 flex-grow max-w-full min-w-max">
                        {{ __('userlog.log') }}
                    </div>

                    <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                        <x-ts-icon icon="tabler.calendar-user" class="inline-block" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm font-light text-left">
                    <thead class="font-medium border-b dark:border-neutral-500">
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
                                <td class="p-2 whitespace-nowrap" colspan="6">
                                    <b>{{ $day }}</b> ({{ count($userlogs) }})
                                </td>
                            </tr>

                            @foreach ($userlogs as $userlog)
                                <tr>
                                    <td></td>
                                    <td class="p-2 whitespace-nowrap">{{ $userlog->time }}</td>
                                    <td class="p-2 whitespace-nowrap">{{ $userlog->surname }} {{ $userlog->firstname }}</td>
                                    <td class="p-2 whitespace-nowrap">{{ $userlog->country_name }}</td>
                                    <td class="p-2 whitespace-nowrap">{{ $userlog->country_code }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td class="p-2 whitespace-nowrap" colspan="6">No data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- card footer --}}
            <div class="p-2 text-sm border-t-2 rounded-b border-neutral-100 dark:border-neutral-600">
                {{ __('userlog.timespan', ['months' => $months]) }}.
            </div>
        </div>
    </div>
</x-app-layout>
