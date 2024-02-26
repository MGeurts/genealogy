<div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
    <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
        <div class="flex flex-wrap gap-2 justify-center items-start">
            <div class="flex-grow min-w-max max-w-full flex-1 align-middle justify-center items-center">
                {{ __('person.profile') }}
            </div>

            @if (auth()->user()->hasPermission('person:delete'))
                @if ($person->isDeletable())
                    <div class="flex-grow min-w-max max-w-min flex-1 text-end">
                        <x-button.danger class="!p-2" title="{{ __('person.delete_person') }}" wire:click="confirmDeletion()">
                            <x-icon.tabler icon="trash" class="!size-4" />
                        </x-button.danger>
                    </div>
                @endif
            @endif

            @if (auth()->user()->hasPermission('person:update'))
                <div class="flex-grow min-w-max max-w-min flex-1 text-end">
                    <div class="relative" data-te-dropdown-ref>
                        <a href="#" class="pb-1" id="dropdownMenuButton2" data-te-dropdown-toggle-ref aria-expanded="false" data-te-ripple-init data-te-ripple-color="light">
                            <x-button.primary class="!p-2" title="{{ __('person.edit_person') }}">
                                <x-icon.tabler icon="edit" class="!size-4" />
                            </x-button.primary>
                        </a>

                        <ul class="absolute z-[1000] float-left m-0 hidden min-w-max list-none overflow-hidden rounded border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:bg-neutral-700 [&[data-te-dropdown-show]]:block"
                            aria-labelledby="dropdownMenuButton2" data-te-dropdown-menu-ref>
                            <li>
                                <a class="block w-full whitespace-nowrap bg-transparent px-2 py-2 text-sm font-normal text-neutral-700 hover:bg-neutral-100 active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-neutral-200 dark:hover:bg-neutral-600"
                                    href="/people/{{ $person->id }}/edit-profile" data-te-dropdown-item-ref>
                                    <x-icon.tabler icon="id" class="mr-2" />
                                    {{ __('person.profile') }}
                                </a>
                            </li>
                            <li>
                                <a class="block w-full whitespace-nowrap bg-transparent px-2 py-2 text-sm font-normal text-neutral-700 hover:bg-neutral-100 active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-neutral-200 dark:hover:bg-neutral-600"
                                    href="/people/{{ $person->id }}/edit-contact" data-te-dropdown-item-ref>
                                    <x-icon.tabler icon="address-book" class="mr-2" />
                                    {{ __('app.contact') }}
                                </a>
                            </li>
                            <hr class="my-1 h-0 border border-t-0 border-solid border-neutral-700 opacity-25 dark:border-neutral-200" />
                            <li>
                                <a class="block w-full whitespace-nowrap bg-transparent px-2 py-2 text-sm font-normal text-neutral-700 hover:bg-neutral-100 active:text-neutral-800 active:no-underline disabled:pointer-events-none disabled:bg-transparent disabled:text-neutral-400 dark:text-neutral-200 dark:hover:bg-neutral-600"
                                    href="/people/{{ $person->id }}/edit-death" data-te-dropdown-item-ref>
                                    <x-icon.tabler icon="coffin" class="mr-2" />
                                    {{ __('person.death') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- image --}}
    <div class="grid p-2 justify-center">
        <livewire:people.photos :person="$person" class="max-w-96" />
    </div>

    {{-- lifetime --}}
    <div class="px-2 text-center">
        {!! $person->lifetime ? $person->lifetime : '&nbsp' !!}
    </div>

    {{-- age --}}
    <div class="px-2 text-center">
        {!! isset($person->age) ? $person->age . ' ' . trans_choice('person.years', $person->age) : '&nbsp' !!}
    </div>

    {{-- data --}}
    <div class="p-2">
        <table class="w-full">
            <tbody>
                <tr>
                    <td class="pr-2 border-t-2 border-r-2">{{ __('person.name') }}</td>
                    <td class="pl-2 border-t-2">{{ $person->name }}</td>
                </tr>
                <tr>
                    <td class="pr-2 border-r-2">{{ __('person.birthname') }}</td>
                    <td class="pl-2">{{ $person->birthname }}</td>
                </tr>
                <tr class="border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.nickname') }}</td>
                    <td class="pl-2">{{ $person->nickname }}</td>
                </tr>

                <tr>
                    <td class="pr-2 border-r-2">{{ __('person.sex') }} ({{ __('person.biological') }})</td>
                    <td class="pl-2">{{ $person->sex == 'm' ? __('app.male') : __('app.female') }} <x-icon.tabler icon="{{ $person->sex == 'm' ? 'gender-male' : 'gender-female' }}" /></td>
                </tr>
                <tr class="border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.gender') }}</td>
                    <td class="pl-2">{{ $person->gender ? $person->gender->name : '' }}</td>
                </tr>

                <tr>
                    <td class="pr-2 border-r-2">{{ __('person.dob') }}</td>
                    <td class="pl-2">
                        {{ $person->birth_formatted }}
                        @if ($person->isBirthdayToday())
                            <x-icon.tabler icon="cake" class="!size-4 text-warning" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="pr-2 border-r-2">{{ __('person.pob') }}</td>
                    <td class="pl-2">{{ $person->pob }}</td>
                </tr>
                <tr class="border-b-2">
                    <td class="pr-2 border-r-2">{{ __('person.birth_order') }}</td>
                    <td class="pl-2">{{ $person->birth_order }}</td>
                </tr>

                @if ($person->isDeceased())
                    <tr>
                        <td class="pr-2 border-r-2">{{ __('person.dod') }}</td>
                        <td class="pl-2">
                            {{ $person->death_formatted }}
                            @if ($person->isDeathdayToday())
                                <x-icon.tabler icon="cake" class="text-warning size-4 " />
                            @endif
                        </td>
                    </tr>
                    <tr class="border-b-2">
                        <td class="pr-2 border-r-2">{{ __('person.pod') }}</td>
                        <td class="pl-2">{{ $person->pod }}</td>
                    </tr>
                    <tr>
                        <td class="pr-2 border-r-2">{{ __('person.cemetery') }}</td>
                        <td class="pl-2">{{ $person->getMetadataValue('cemetery_location_name') }}</td>
                    </tr>
                    <tr class="align-top">
                        <td class="pr-2 border-b-2 border-r-2">
                            @if ($person->cemetery_google)
                                <a target="_blank" href="{{ $person->cemetery_google }}">
                                    <x-button.info class="!p-2 mb-2" title="{{ __('app.show_on_google_maps') }}">
                                        <x-icon.tabler icon="brand-google-maps" class="size-4" />
                                    </x-button.info>
                                </a>
                            @endif
                        </td>
                        <td class="pl-2 border-b-2">{!! nl2br(e($person->getMetadataValue('cemetery_location_address'))) !!}</td>
                    </tr>
                @else
                    <tr class="align-top">
                        <td class="pr-2 border-b-2 border-r-2">
                            {{ __('person.address') }}<br />
                            @if ($person->address)
                                <a target="_blank" href="{{ $person->address_google }}">
                                    <x-button.info class="!p-2 mb-2" title="{{ __('app.show_on_google_maps') }}">
                                        <x-icon.tabler icon="brand-google-maps" class="size-4" />
                                    </x-button.info>
                                </a>
                            @endif
                        </td>
                        <td class="pl-2 border-b-2">{!! nl2br(e($person->address)) !!}</td>
                    </tr>
                    <tr>
                        <td class="pr-2 border-b-2 border-r-2">{{ __('person.phone') }}</td>
                        <td class="pl-2 border-b-2">{{ $person->phone }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if ($person->isDeletable())
        {{-- delete modal --}}
        <x-confirmation-modal wire:model.live="deleteConfirmed">
            <x-slot name="title">
                {{ __('app.delete') }}
            </x-slot>

            <x-slot name="content">
                <h1>{{ __('app.delete_question', ['model' => __('app.delete_person')]) }}</h1>
                <br />
                <h3 class="text-lg font-medium text-gray-900">{{ $person->name }}</h3>
            </x-slot>

            <x-slot name="footer">
                <x-button.secondary wire:click="$toggle('deleteConfirmed')" wire:loading.attr="disabled">
                    {{ __('app.abort_no') }}
                </x-button.secondary>

                <x-button.danger class="ml-3" wire:click="deletePerson()" wire:loading.attr="disabled">
                    {{ __('app.delete_yes') }}
                </x-button.danger>
            </x-slot>
        </x-confirmation-modal>
    @endif
</div>
