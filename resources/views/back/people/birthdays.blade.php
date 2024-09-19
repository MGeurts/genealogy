@section('title')
    &vert; {{ __('birthday.birthdays') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('birthday.birthdays') }}
    </x-slot>

    <div class="py-5 space-y-5 overflow-x-auto">
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            <div class="flex flex-col p-2 text-lg border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="flex-1 flex-grow max-w-full min-w-max">
                        {{ __('birthday.upcoming_birthdays') }}
                    </div>

                    <div class="flex-1 flex-grow min-w-max max-w-min text-end">
                        <x-ts-icon icon="cake" />
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
                        @forelse($people as $key => $person)
                            <tr class="align-top transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600">
                                <td class="p-2 whitespace-nowrap text-end">{{ $key + 1 }}.</td>
                                <td class="p-2 whitespace-nowrap">
                                    <x-link href="/people/{{ $person->id }}" @class(['text-danger-600 dark:text-danger-400' =>$person->isDeceased()])>
                                        {{ $person->name }}
                                    </x-link>
                                    <x-ts-icon icon="{{ $person->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                                </td>
                                <td class="p-2 whitespace-nowrap text-end">
                                    {{ $person->dob ? $person->dob->isoFormat('LL') : '' }}
                                </td>
                                <td class="p-2 whitespace-nowrap text-end">
                                    {{ $person->next_birthday->isoFormat('LL') }}
                                </td>
                                <td class="p-2 whitespace-nowrap text-end">
                                    {!! $person->next_birthday_remaining_days . ' ' . trans_choice('birthday.days', $person->next_birthday_remaining_days) !!}
                                </td>
                                <td class="p-2 whitespace-nowrap text-end">
                                    {{ $person->next_birthday_age }}

                                    @if ($person->isDeceased())
                                        <br />
                                        <span class="text-danger-600 dark:text-danger-400">
                                            <x-ts-icon icon="coffin" class="inline-block mr-1" /> {{ $person->age }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-2 whitespace-nowrap">{{ __('birthday.no_upcoming_birthdays', ['months' => $months]) }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- footer --}}
            <div class="h-12 p-2 text-sm rounded-b">
                {{ __('birthday.upcoming_months', ['months' => $months]) }}
            </div>
        </div>
    </div>
</x-app-layout>
