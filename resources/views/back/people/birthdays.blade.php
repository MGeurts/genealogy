@section('title')
    &vert; {{ __('birthday.birthdays') }}
@endsection

<x-app-layout>
    <div class="mx-auto flex grow">
        <div class="space-y-5 overflow-x-auto p-2">
            <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50">
                <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg dark:border-neutral-600 dark:text-neutral-50">
                    <div class="flex flex-wrap items-start justify-center gap-2">
                        <div class="max-w-full min-w-max flex-1 grow">{{ __('birthday.upcoming_birthdays') }}</div>

                        <div class="max-w-min min-w-max flex-1 grow text-end">
                            <x-ts-icon icon="tabler.cake" class="inline-block size-5" />
                        </div>
                    </div>
                </div>

                {{-- body --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="border-b dark:border-neutral-500">
                            <tr>
                                <th scope="col" class="p-2 text-end">#</th>
                                <th scope="col" class="p-2">{{ __('person.person') }}</th>
                                <th scope="col" class="p-2 text-end">{{ __('person.dob') }}</th>
                                <th scope="col" class="p-2 text-end">{{ __('birthday.birthday') }}</th>
                                <th scope="col" class="p-2 text-end">{{ __('birthday.in') }}</th>
                                <th scope="col" class="p-2 text-end">{{ __('birthday.age') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($people as $key => $person)
                                <tr class="border-b align-top transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600">
                                    <td class="p-2 text-end whitespace-nowrap">{{ $key + 1 }}.</td>
                                    <td class="p-2 whitespace-nowrap">
                                        <x-link
                                            href="/people/{{ $person->id }}"
                                            @class(['text-red-600 dark:text-red-400' => $person->isDeceased()])
                                        >
                                            {{ $person->name }}
                                        </x-link>
                                        <x-ts-icon
                                            icon="tabler.{{ $person->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                                            class="inline-block size-5"
                                        />
                                    </td>
                                    <td class="p-2 text-end whitespace-nowrap">
                                        {{ $person->dob ? $person->dob->timezone(session('timezone') ?? 'UTC')->isoFormat('LL') : '' }}
                                    </td>
                                    <td class="p-2 text-end whitespace-nowrap">
                                        {{ $person->next_birthday->isoFormat('LL') }}
                                    </td>
                                    <td class="p-2 text-end whitespace-nowrap">
                                        {{ $person->next_birthday_remaining_days . ' ' . trans_choice('birthday.days', $person->next_birthday_remaining_days) }}
                                    </td>
                                    <td class="p-2 text-end whitespace-nowrap">
                                        {{ $person->next_birthday_age }}

                                        @if ($person->isDeceased())
                                            <br />
                                            <span class="text-red-600 dark:text-red-400">
                                                <x-ts-icon icon="tabler.grave-2" class="mr-1 inline-block size-5" />
                                                {{ $person->age }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-2 whitespace-nowrap">
                                        {{ __('birthday.no_upcoming_birthdays', ['months' => $months]) }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- footer --}}
                <div class="h-12 rounded-b p-2 text-sm">
                    {{ __('birthday.upcoming_months', ['months' => $months]) }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
