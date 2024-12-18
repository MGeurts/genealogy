<div class="w-full min-w-max max-w-192 grow dark:text-neutral-200">
    <table class="table-auto">
        <tbody>
            {{-- names --}}
            <tr class="border-gray-600 border-solid border-y-2">
                <td colspan="4">{{ __('person.person') }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="3" class="border-b-2 border-gray-600 border-solid">{{ __('person.names') }}</td>
            </tr>
            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.firstname') }} :</td>
                <td class="break-words max-w-96">{{ $person->firstname }}</td>
            </tr>
            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.surname') }} :</td>
                <td class="break-words max-w-96">{{ $person->surname }}</td>
            </tr>
            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.birthname') }} :</td>
                <td class="break-words max-w-96">{{ $person->birthname }}</td>
            </tr>
            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.nickname') }} :</td>
                <td class="break-words max-w-96">{{ $person->nickname }}</td>
            </tr>

            {{-- sex & gender --}}
            <tr>
                <td>&nbsp;</td>
                <td class="border-b-2 border-gray-600 border-solid">{{ __('person.sex') }} & {{ __('person.gender') }}</td>
                <td colspan="2" class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
            </tr>
            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.sex') }} :</td>
                <td>
                    {{ $person->sex == 'm' ? __('app.male') : __('app.female') }}
                    <x-ts-icon icon="{{ $person->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                </td>
            </tr>
            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.gender') }} :</td>
                <td class="break-words max-w-96">{{ $person->gender ? $person->gender->name : '' }}</td>
            </tr>

            {{-- birth --}}
            <tr>
                <td>&nbsp;</td>
                <td class="border-b-2 border-gray-600 border-solid">{{ __('person.birth') }}</td>
                <td colspan="2" class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
            </tr>
            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.dob') }} :</td>
                <td>{{ $person->birth_formatted }}</td>
            </tr>
            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.pob') }} :</td>
                <td class="break-words max-w-96">{{ $person->pob }}</td>
            </tr>
            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.age') }} :</td>
                <td>{{ $person->age }}</td>
            </tr>

            @if ($person->isDeceased())
                {{-- death --}}
                <tr>
                    <td>&nbsp;</td>
                    <td class="border-b-2 border-gray-600 border-solid">{{ __('person.death') }}</td>
                    <td colspan="2" class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
                </tr>
                <tr class="align-top">
                    <td colspan="2">&nbsp;</td>
                    <td>{{ __('person.dod') }} :</td>
                    <td>{{ $person->death_formatted }}</td>
                </tr>
                <tr class="align-top">
                    <td colspan="2">&nbsp;</td>
                    <td>{{ __('person.pod') }} :</td>
                    <td class="break-words max-w-96">{{ $person->pod }}</td>
                </tr>
                <tr class="align-top">
                    <td colspan="2">&nbsp;</td>
                    <td>{{ __('person.cemetery') }} :</td>
                    <td class="break-words max-w-96">
                        @php
                            $cemetery = array_filter([$person->getMetadataValue('cemetery_location_name'), $person->getMetadataValue('cemetery_location_address')]);
                        @endphp

                        @foreach ($cemetery as $line)
                            {{ $line }}

                            @if (!$loop->last)
                                <br />
                            @endif
                        @endforeach
                    </td>
                </tr>
            @else
                {{-- contact --}}
                <tr>
                    <td>&nbsp;</td>
                    <td class="border-b-2 border-gray-600 border-solid">{{ __('person.contact') }}</td>
                    <td colspan="2" class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
                </tr>
                <tr class="align-top">
                    <td colspan="2">&nbsp;</td>
                    <td>{{ __('person.address') }} :</td>
                    <td class="break-words whitespace-pre-line max-w-96">{{ $person->address }}</td>
                </tr>
                <tr class="align-top">
                    <td colspan="2">&nbsp;</td>
                    <td>{{ __('person.phone') }} :</td>
                    <td class="break-words max-w-96">{{ $person->phone }}</td>
                </tr>
            @endif

            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>

            <tr>
                <td>&nbsp;</td>
                <td class="border-b-2 border-gray-600 border-solid">{{ __('person.summary') }}</td>
                <td colspan="2" class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
            </tr>

            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td colspan="2" class="break-words whitespace-pre-line max-w-96">{{ $person->summary }}</td>
            </tr>

            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>

            {{-- family --}}
            <tr class="border-gray-600 border-solid border-y-2">
                <td>{{ __('person.family') }}</td>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.father') }} :</td>
                <td>
                    @if ($person->father)
                        {{ $person->father->name }}
                        <x-ts-icon icon="{{ $person->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        {{ $person->father->birth_year }}
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.mother') }} :</td>
                <td>
                    @if ($person->mother)
                        {{ $person->mother->name }}
                        <x-ts-icon icon="{{ $person->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        {{ $person->mother->birth_year }}
                    @endif
                </td>
            </tr>
            <tr class="align-top">
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.parents') }} :</td>
                <td>
                    @if ($person->parents)
                        {{ $person->parents->person_1->name }} <x-ts-icon icon="{{ $person->parents->person_1->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        {{ $person->parents->person_1->birth_year }}<br />
                        {{ $person->parents->person_2->name }} <x-ts-icon icon="{{ $person->parents->person_2->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        {{ $person->parents->person_2->birth_year }}
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td>{{ __('person.partner') }} :</td>
                <td>
                    @if ($person->currentPartner())
                        {{ $person->currentPartner()->name }}
                        <x-ts-icon icon="{{ $person->currentPartner()->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        {{ $person->currentPartner()->birth_year }}
                    @endif
                </td>
            </tr>

            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>

            {{-- partners --}}
            <tr class="border-gray-600 border-solid border-y-2">
                <td colspan="4">
                    {{ __('person.partners') }}
                    @if (count($person->couples) > 0)
                        <x-ts-badge color="emerald" text="{{ count($person->couples) }}" />
                    @endif
                </td>
            </tr>

            @foreach ($person->couples->sortBy('date_start') as $couple)
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td>
                        @if ($couple->person2_id === $person->id)
                            {{ $couple->person_1->name }}

                            <x-ts-icon icon="{{ $couple->person_1->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        @else
                            {{ $couple->person_2->name }}

                            <x-ts-icon icon="{{ $couple->person_2->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        @endif

                        @if ($couple->is_married)
                            <x-ts-icon icon="circles-relation" class="inline-block text-yellow-500 size-5" />
                        @endif
                        <br />

                        <x-ts-icon icon="hearts" class="inline-block size-5 text-emerald-600" />
                        {{ $couple->date_start ? $couple->date_start->isoFormat('LL') : '??' }}

                        @if ($couple->date_end or $couple->has_ended)
                            <br />
                            <x-ts-icon icon="hearts-off" class="inline-block size-5 text-danger-600 dark:text-danger-400" />
                            {{ $couple->date_end ? $couple->date_end->isoFormat('LL') : '??' }}
                        @endif
                    </td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>

            {{-- children --}}
            <tr class="border-gray-600 border-solid border-y-2">
                <td colspan="4">
                    {{ __('person.children') }}
                    @if (count($person->childrenNaturalAll()) > 0)
                        <x-ts-badge color="emerald" text="{{ count($person->childrenNaturalAll()) }}" />
                    @endif
                </td>
            </tr>

            @foreach ($person->childrenNaturalAll() as $child)
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td>
                        {{ $child->name }}
                        <x-ts-icon icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        {{ $child->birth_year }}
                    </td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>

            {{-- siblings --}}
            <tr class="border-gray-600 border-solid border-y-2">
                <td colspan="4">
                    {{ __('person.siblings') }}
                    @if (count($person->siblings()) > 0)
                        <x-ts-badge color="emerald" text="{{ count($person->siblings()) }}" />
                    @endif
                </td>
            </tr>

            @foreach ($person->siblings() as $sibling)
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td>
                        {{ $sibling->name }}
                        <x-ts-icon icon="{{ $sibling->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        {{ $sibling->birth_year }}
                        <span class="text-warning-500">{{ $sibling->type }}</span>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>

            {{-- files --}}
            <tr class="border-gray-600 border-solid border-y-2">
                <td colspan="4">
                    {{ __('person.files') }}
                    @if (count($files) > 0)
                        <x-ts-badge color="emerald" text="{{ count($files) }}" />
                    @endif
                </td>
            </tr>

            @foreach ($files as $file)
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td>
                        <x-link href="{{ $file->getUrl() }}" target="_blank" title="{{ __('app.download') }}">
                            {{ $file->file_name }}
                        </x-link>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>

            {{-- photos --}}
            <tr class="border-gray-600 border-solid border-y-2">
                <td colspan="4">
                    {{ __('person.photos') }}
                    @if (count($images) > 0)
                        <x-ts-badge color="emerald" text="{{ count($images) }}" />
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="grid grid-cols-3 gap-2 mt-2">
        @foreach ($images as $image)
            <div>
                <img class="rounded max-w-48" src="{{ asset('storage/photos-384/' . $person->team_id . '/' . $image) }}" alt="{{ $person->name }}" />
            </div>
        @endforeach
    </div>
</div>
