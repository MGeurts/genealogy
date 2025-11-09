<div class="min-w-sm max-w-3xl flex flex-col rounded-sm bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="items-center justify-center flex-1 grow max-w-full align-middle min-w-max">
                {{ __('person.profile') }}
            </div>

            @if (auth()->user()->hasPermission('person:update') or auth()->user()->hasPermission('person:delete'))
                <div class="flex-1 grow min-w-max max-w-min text-end">
                    <x-ts-dropdown icon="tabler.menu-2" position="bottom-end">
                        @if (auth()->user()->hasPermission('person:update'))
                            <a href="/people/{{ $person->id }}/edit-profile">
                                <x-ts-dropdown.items>
                                    <x-ts-icon icon="tabler.id" class="inline-block size-5 mr-2" />
                                    {{ __('person.edit_profile') }}
                                </x-ts-dropdown.items>
                            </a>

                            <a href="/people/{{ $person->id }}/edit-contact">
                                <x-ts-dropdown.items>
                                    <x-ts-icon icon="tabler.address-book" class="inline-block size-5 mr-2" />
                                    {{ __('person.edit_contact') }}
                                </x-ts-dropdown.items>
                            </a>

                            <a href="/people/{{ $person->id }}/edit-death">
                                <x-ts-dropdown.items>
                                    <x-ts-icon icon="tabler.grave-2" class="inline-block size-5 mr-2" />
                                    {{ __('person.edit_death') }}
                                </x-ts-dropdown.items>
                            </a>

                            <hr />
                            <a href="/people/{{ $person->id }}/edit-photos">
                                <x-ts-dropdown.items>
                                    <x-ts-icon icon="tabler.photo" class="inline-block size-5 mr-2" />
                                    {{ __('person.edit_photos') }}
                                </x-ts-dropdown.items>
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('person:delete') and $person->isDeletable())
                            <hr />

                            <x-ts-dropdown.items separator class="text-red-600! dark:text-red-400!" wire:click="confirm()">
                                <x-ts-icon icon="tabler.trash" class="inline-block size-5 mr-2" />
                                {{ __('person.delete_person') }}
                            </x-ts-dropdown.items>
                        @endif
                    </x-ts-dropdown>
                </div>
            @endif
        </div>
    </div>

    {{-- image --}}
    <div class="grid justify-center pt-2">
        <livewire:people.gallery :person="$person" class="max-w-sm" />
    </div>

    {{-- lifetime & age --}}
    <div class="flex px-2">
        <div class="grow">
            {!! isset($person->lifetime) ? $person->lifetime : '&nbsp;' !!}
        </div>

        <div class="grow text-end">
            {!! isset($person->age) ? $person->age . ' ' . trans_choice('person.years', $person->age) : '&nbsp;' !!}
        </div>
    </div>

    {{-- data --}}
    <div class="p-2">
        <table class="w-full">
            <tbody>
                <tr class="align-top">
                    <td class="pr-2 border-t-2 border-r-2">{{ __('person.firstname') }}</td>
                    <td class="pl-2 wrap-break-word border-t-2 max-w-sm">{{ $person->firstname }}</td>
                </tr>
                <tr class="align-top">
                    <td class="pr-2 border-r-2">{{ __('person.surname') }}</td>
                    <td class="pl-2 wrap-break-word max-w-sm">{{ $person->surname }}</td>
                </tr>
                <tr class="align-top">
                    <td class="pr-2 border-r-2">{{ __('person.birthname') }}</td>
                    <td class="pl-2 wrap-break-word max-w-sm">{{ $person->birthname }}</td>
                </tr>
                <tr class="align-top border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.nickname') }}</td>
                    <td class="pl-2 wrap-break-word max-w-sm">{{ $person->nickname }}</td>
                </tr>

                <tr class="align-top">
                    <td class="pr-2 border-r-2">{{ __('person.sex') }} ({{ __('person.biological') }})</td>
                    <td class="pl-2">
                        {{ $person->sex === 'm' ? __('app.male') : __('app.female') }}
                        <x-ts-icon icon="tabler.{{ $person->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    </td>
                </tr>
                <tr class="align-top border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.gender') }}</td>
                    <td class="pl-2 wrap-break-word max-w-sm">{{ $person->gender ? $person->gender->name : '' }}</td>
                </tr>

                <tr class="align-top">
                    <td class="pr-2 border-r-2">{{ __('person.dob') }}</td>
                    <td class="pl-2">
                        {{ $person->birth_formatted }}
                        @if ($person->isBirthdayToday())
                            <x-ts-icon icon="tabler.cake" class="inline-block size-5 text-red-600 dark:text-red-400" />
                        @endif
                    </td>
                </tr>
                <tr class="align-top border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.pob') }}</td>
                    <td class="pl-2 wrap-break-word max-w-sm">{{ $person->pob }}</td>
                </tr>

                @if ($person->isDeceased())
                    <tr class="align-top">
                        <td class="pr-2 border-r-2">{{ __('person.dod') }}</td>
                        <td class="pl-2">
                            {{ $person->death_formatted }}
                            @if ($person->isDeathdayToday())
                                <x-ts-icon icon="tabler.cake" class="inline-block size-5 text-red-600 dark:text-red-400" />
                            @endif
                        </td>
                    </tr>
                    <tr class="align-top border-b-2">
                        <td class="pr-2 border-r-2">{{ __('person.pod') }}</td>
                        <td class="pl-2 wrap-break-word max-w-sm">{{ $person->pod }}</td>
                    </tr>
                    <tr class="align-top">
                        <td class="pr-2 border-r-2">{{ __('person.cemetery') }}</td>
                        <td class="pl-2 wrap-break-word max-w-sm">{{ $person->getMetadataValue('cemetery_location_name') }}</td>
                    </tr>
                    <tr class="align-top">
                        <td class="pr-2 border-b-2 border-r-2">
                            @if ($person->cemetery_google)
                                <a target="_blank" href="{{ $person->cemetery_google }}">
                                    <x-ts-button color="cyan" class="p-2! mb-2 text-white" title="{{ __('app.show_on_google_maps') }}">
                                        <x-ts-icon icon="tabler.brand-google-maps" class="inline-block size-5" />
                                    </x-ts-button>
                                </a>
                            @endif
                        </td>
                        <td class="pl-2 wrap-break-word whitespace-pre-line border-b-2 max-w-sm">{{ $person->getMetadataValue('cemetery_location_address') }}</td>
                    </tr>
                @else
                    <tr class="align-top">
                        <td class="pr-2 border-b-2 border-r-2">
                            {{ __('person.address') }}<br />
                            @if ($person->address)
                                <a target="_blank" href="{{ $person->address_google }}">
                                    <x-ts-button color="cyan" class="p-2! mb-2 text-white" title="{{ __('app.show_on_google_maps') }}">
                                        <x-ts-icon icon="tabler.brand-google-maps" class="inline-block size-5" />
                                    </x-ts-button>
                                </a>
                            @endif
                        </td>
                        <td class="pl-2 wrap-break-word whitespace-pre-line border-b-2 max-w-sm">{{ $person->address }}</td>
                    </tr>
                    <tr class="align-top">
                        <td class="pr-2 border-b-2 border-r-2">{{ __('person.phone') }}</td>
                        <td class="pl-2 wrap-break-word border-b-2 max-w-sm">{{ $person->phone }}</td>
                    </tr>
                @endif

                <tr class="align-top border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.summary') }}</td>
                    <td class="pl-2 wrap-break-word whitespace-pre-line max-w-sm">{{ $person->summary }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
