@section('title')
    &vert; {{ __('user.users') }}
@endsection

<x-app-layout>
    <div class="max-w-5xl grow overflow-x-auto p-2 dark:text-neutral-200">
        <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50">
            {{-- card header --}}
            <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="max-w-full min-w-max flex-1 grow">{{ __('userlog.log') }}</div>

                    <div class="max-w-full min-w-max flex-1 grow text-end">
                        <x-ts-icon icon="tabler.calendar-user" class="inline-block size-5" />
                    </div>
                </div>
            </div>

            {{-- card body --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm font-light">
                    <thead class="border-b font-medium dark:border-neutral-500">
                        <tr>
                            <th scope="col" class="p-2">{{ __('userlog.date') }}</th>
                            <th scope="col" class="p-2">{{ __('userlog.time') }}</th>
                            <th scope="col" class="p-2">{{ __('userlog.user') }}</th>
                            <th scope="col" class="p-2">{{ __('userlog.country_name') }}</th>
                            <th scope="col" class="p-2">{{ __('userlog.country_code') }}</th>
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
                                    <td class="p-2 whitespace-nowrap">
                                        {{ $userlog->surname }} {{ $userlog->firstname }}
                                    </td>
                                    <td class="p-2 whitespace-nowrap">{{ $userlog->country_name }}</td>
                                    <td class="p-2 whitespace-nowrap">{{ $userlog->country_code }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td class="p-2 whitespace-nowrap" colspan="6">{{ __('app.nothing_recorded') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- card footer --}}
            <div class="rounded-b border-t-2 border-neutral-100 p-2 text-sm dark:border-neutral-600">
                {{ __('userlog.timespan', ['months' => $months]) }}.
            </div>
        </div>
    </div>
</x-app-layout>
