<div class="min-w-100 max-w-192 flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
    <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
        <div class="flex flex-wrap items-start justify-center gap-2">
            <div class="items-center justify-center flex-1 flex-grow max-w-full align-middle min-w-max">
                {{ __('person.profile') }}
            </div>

            @if (auth()->user()->hasPermission('person:update') or auth()->user()->hasPermission('person:delete'))
                <div class="flex-1 flex-grow min-w-max max-w-min text-end">
                    <x-ts-dropdown icon="menu-2" position="bottom-end">
                        @if (auth()->user()->hasPermission('person:update'))
                            <a href="/people/{{ $person->id }}/edit-profile">
                                <x-ts-dropdown.items>
                                    <x-ts-icon icon="id" class="mr-2" />
                                    {{ __('person.edit_profile') }}
                                </x-ts-dropdown.items>
                            </a>

                            <a href="/people/{{ $person->id }}/edit-contact">
                                <x-ts-dropdown.items>
                                    <x-ts-icon icon="address-book" class="mr-2" />
                                    {{ __('person.edit_contact') }}
                                </x-ts-dropdown.items>
                            </a>

                            <a href="/people/{{ $person->id }}/edit-death">
                                <x-ts-dropdown.items>
                                    <x-ts-icon icon="grave-2" class="mr-2" />
                                    {{ __('person.edit_death') }}
                                </x-ts-dropdown.items>
                            </a>

                            <hr />
                            <a href="/people/{{ $person->id }}/edit-photos">
                                <x-ts-dropdown.items>
                                    <x-ts-icon icon="photo" class="mr-2" />
                                    {{ __('person.edit_photos') }}
                                </x-ts-dropdown.items>
                            </a>
                        @endif

                        @if (auth()->user()->hasPermission('person:delete') and $person->isDeletable())
                            <hr />

                            <x-ts-dropdown.items separator class="!text-danger-600 dark:!text-danger-400" wire:click="confirmDeletion()">
                                <x-ts-icon icon="trash" class="mr-2" />
                                {{ __('person.delete_person') }}
                            </x-ts-dropdown.items>
                        @endif
                    </x-ts-dropdown>
                </div>
            @endif
        </div>
    </div>

    {{-- image --}}
    <div class="grid justify-center px-2 pt-2">
        <livewire:people.gallery :person="$person" class="max-w-96" />
    </div>

    {{-- lifetime & age --}}
    <div class="flex px-2">
        <div class="flex-grow">{!! $person->lifetime ? $person->lifetime : '' !!}</div>
        <div class="flex-grow text-end">{!! isset($person->age) ? $person->age . ' ' . trans_choice('person.years', $person->age) : '' !!}</div>
    </div>

    {{-- data --}}
    <div class="p-2">
        <table class="w-full">
            <tbody>
                <tr class="align-top">
                    <td class="pr-2 border-t-2 border-r-2">{{ __('person.firstname') }}</td>
                    <td class="pl-2 break-words border-t-2 max-w-96">{{ $person->firstname }}</td>
                </tr>
                <tr class="align-top">
                    <td class="pr-2 border-r-2">{{ __('person.surname') }}</td>
                    <td class="pl-2 break-words max-w-96">{{ $person->surname }}</td>
                </tr>
                <tr class="align-top">
                    <td class="pr-2 border-r-2">{{ __('person.birthname') }}</td>
                    <td class="pl-2 break-words max-w-96">{{ $person->birthname }}</td>
                </tr>
                <tr class="align-top border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.nickname') }}</td>
                    <td class="pl-2 break-words max-w-96">{{ $person->nickname }}</td>
                </tr>

                <tr class="align-top">
                    <td class="pr-2 border-r-2">{{ __('person.sex') }} ({{ __('person.biological') }})</td>
                    <td class="pl-2">
                        {{ $person->sex == 'm' ? __('app.male') : __('app.female') }}
                        <x-ts-icon icon="{{ $person->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    </td>
                </tr>
                <tr class="align-top border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.gender') }}</td>
                    <td class="pl-2 break-words max-w-96">{{ $person->gender ? $person->gender->name : '' }}</td>
                </tr>

                <tr class="align-top">
                    <td class="pr-2 border-r-2">{{ __('person.dob') }}</td>
                    <td class="pl-2">
                        {{ $person->birth_formatted }}
                        @if ($person->isBirthdayToday())
                            <x-ts-icon icon="cake" class="inline-block size-5 text-danger-600 dark:text-danger-400" />
                        @endif
                    </td>
                </tr>
                <tr class="align-top border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.pob') }}</td>
                    <td class="pl-2 break-words max-w-96">{{ $person->pob }}</td>
                </tr>

                @if ($person->isDeceased())
                    <tr class="align-top">
                        <td class="pr-2 border-r-2">{{ __('person.dod') }}</td>
                        <td class="pl-2">
                            {{ $person->death_formatted }}
                            @if ($person->isDeathdayToday())
                                <x-ts-icon icon="cake" class="inline-block size-5 text-danger-600 dark:text-danger-400" />
                            @endif
                        </td>
                    </tr>
                    <tr class="align-top border-b-2">
                        <td class="pr-2 border-r-2">{{ __('person.pod') }}</td>
                        <td class="pl-2 break-words max-w-96">{{ $person->pod }}</td>
                    </tr>
                    <tr class="align-top">
                        <td class="pr-2 border-r-2">{{ __('person.cemetery') }}</td>
                        <td class="pl-2 break-words max-w-96">{{ $person->getMetadataValue('cemetery_location_name') }}</td>
                    </tr>
                    <tr class="align-top">
                        <td class="pr-2 border-b-2 border-r-2">
                            @if ($person->cemetery_google)
                                <a target="_blank" href="{{ $person->cemetery_google }}">
                                    <x-ts-button color="info" class="!p-2 mb-2 text-white" title="{{ __('app.show_on_google_maps') }}">
                                        <x-ts-icon icon="brand-google-maps" class="size-5" />
                                    </x-ts-button>
                                </a>
                            @endif
                        </td>
                        <td class="pl-2 break-words border-b-2 max-w-96">{!! nl2br(e($person->getMetadataValue('cemetery_location_address'))) !!}</td>
                    </tr>
                @else
                    <tr class="align-top">
                        <td class="pr-2 border-b-2 border-r-2">
                            {{ __('person.address') }}<br />
                            @if ($person->address)
                                <a target="_blank" href="{{ $person->address_google }}">
                                    <x-ts-button color="info" class="!p-2 mb-2 text-white" title="{{ __('app.show_on_google_maps') }}">
                                        <x-ts-icon icon="brand-google-maps" class="size-5" />
                                    </x-ts-button>
                                </a>
                            @endif
                        </td>
                        <td class="pl-2 break-words border-b-2 max-w-96">{!! nl2br(e($person->address)) !!}</td>
                    </tr>
                    <tr class="align-top">
                        <td class="pr-2 border-b-2 border-r-2">{{ __('person.phone') }}</td>
                        <td class="pl-2 break-words border-b-2 max-w-96">{{ $person->phone }}</td>
                    </tr>
                @endif

                <tr class="align-top border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.summary') }}</td>
                    <td class="pl-2 break-words whitespace-pre-line max-w-96">{{ $person->summary }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if (auth()->user()->hasPermission('person:delete') and $person->isDeletable())
        {{-- delete modal --}}
        <x-confirmation-modal wire:model.live="deleteConfirmed">
            <x-slot name="title">
                {{ __('app.delete') }}
            </x-slot>

            <x-slot name="content">
                <p>{{ __('app.delete_question', ['model' => __('app.delete_person')]) }}</p>
                <p class="text-lg font-medium text-gray-900">{{ $person->name }}</p>
            </x-slot>

            <x-slot name="footer">
                <x-ts-button color="secondary" wire:click="$toggle('deleteConfirmed')" wire:loading.attr="disabled">
                    {{ __('app.abort_no') }}
                </x-ts-button>

                <x-ts-button color="danger" class="ml-3" wire:click="deletePerson()" wire:loading.attr="disabled">
                    {{ __('app.delete_yes') }}
                </x-ts-button>
            </x-slot>
        </x-confirmation-modal>
    @endif
</div>
