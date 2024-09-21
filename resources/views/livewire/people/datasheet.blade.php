<div class="max-w-5xl grow dark:text-neutral-200">
    <table class="gap-5 table-auto">
        <tbody>
            {{-- names --}}
            <tr class="border-t-2 border-b-2 border-gray-600 border-solid">
                <td>{{ __('person.person') }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td class="border-b-2 border-gray-600 border-solid">{{ __('person.names') }}</td>
                <td class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
                <td class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.firstname') }} :</td>
                <td>{{ $person->firstname }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.surname') }} :</td>
                <td>{{ $person->surname }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.birthname') }} :</td>
                <td>{{ $person->birthname }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.nickname') }} :</td>
                <td>{{ $person->nickname }}</td>
            </tr>

            {{-- sex & gender --}}
            <tr>
                <td>&nbsp;</td>
                <td class="border-b-2 border-gray-600 border-solid">{{ __('person.sex') }} & {{ __('person.gender') }}</td>
                <td class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
                <td class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.sex') }} :</td>
                <td>
                    {{ $person->sex == 'm' ? __('app.male') : __('app.female') }}
                    <x-ts-icon icon="{{ $person->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.gender') }} :</td>
                <td>{{ $person->gender }}</td>
            </tr>

            {{-- birth --}}
            <tr>
                <td>&nbsp;</td>
                <td class="border-b-2 border-gray-600 border-solid">{{ __('person.birth') }}</td>
                <td class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
                <td class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.dob') }} :</td>
                <td>{{ $person->birth_formatted }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.pob') }} :</td>
                <td>{{ $person->pob }}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.age') }} :</td>
                <td>{{ $person->age }}</td>
            </tr>

            @if ($person->isDeceased())
                {{-- death --}}
                <tr>
                    <td>&nbsp;</td>
                    <td class="border-b-2 border-gray-600 border-solid">{{ __('person.death') }}</td>
                    <td class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
                    <td class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>{{ __('person.dod') }} :</td>
                    <td>{{ $person->death_formatted }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>{{ __('person.pod') }} :</td>
                    <td>{{ $person->pod }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td valign="top">{{ __('person.cemetery') }} :</td>
                    <td>{!! implode('<br/>', array_filter([$person->getMetadataValue('cemetery_location_name'), nl2br(e($person->getMetadataValue('cemetery_location_address')))])) !!}</td>
                </tr>
            @else
                {{-- contact --}}
                <tr>
                    <td>&nbsp;</td>
                    <td class="border-b-2 border-gray-600 border-solid">{{ __('person.contact') }}</td>
                    <td class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
                    <td class="border-b-2 border-gray-600 border-solid">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td valign="top">{{ __('person.address') }} :</td>
                    <td>{!! nl2br(e($person->address)) !!}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>{{ __('person.phone') }} :</td>
                    <td>{{ $person->phone }}</td>
                </tr>
            @endif
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            {{-- family --}}
            <tr class="border-t-2 border-b-2 border-gray-600 border-solid">
                <td>{{ __('person.family') }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.father') }} :</td>
                <td>
                    {{ $person->father->name }} 
                    <x-ts-icon icon="{{ $person->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    {{ $person->father->birth_year }} 
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.mother') }} :</td>
                <td>
                    {{ $person->mother->name }}
                    <x-ts-icon icon="{{ $person->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    {{ $person->mother->birth_year }} 
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td valign="top">{{ __('person.parents') }} :</td>
                <td>
                    {{ $person->parents->person_1->name }} <x-ts-icon icon="{{ $person->parents->person_1->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" /><br/>
                    {{ $person->parents->person_2->name }} <x-ts-icon icon="{{ $person->parents->person_2->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>{{ __('person.partner') }} :</td>
                <td>
                    {{ $person->currentPartner()->name }}
                    <x-ts-icon icon="{{ $person->currentPartner()->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    {{ $person->currentPartner()->birth_year }} 
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            {{-- partners --}}
            <tr class="border-t-2 border-b-2 border-gray-600 border-solid">
                <td>
                    {{ __('person.partners') }}
                    @if (count($person->couples) > 0)
                        <x-ts-badge color="emerald" text="{{ count($person->couples) }}" />
                    @endif
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            @foreach ($person->couples->sortBy('date_start') as $couple)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
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

                        <p>
                            <x-ts-icon icon="hearts" class="inline-block size-5 text-emerald-600" />
                            {{ $couple->date_start ? $couple->date_start->isoFormat('LL') : '??' }}

                            @if ($couple->date_end or $couple->has_ended)
                                <br />
                                <x-ts-icon icon="hearts-off" class="inline-block size-5 text-danger-600 dark:text-danger-400" />
                                {{ $couple->date_end ? $couple->date_end->isoFormat('LL') : '??' }}
                            @endif
                        </p>
                    </td>
                </tr> 
            @endforeach

            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            {{-- children --}}
            <tr class="border-t-2 border-b-2 border-gray-600 border-solid">
                <td>
                    {{ __('person.children') }}
                    @if (count($person->childrenNaturalAll()) > 0)
                        <x-ts-badge color="emerald" text="{{ count($person->childrenNaturalAll()) }}" />
                    @endif
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            @foreach ($person->childrenNaturalAll() as $child)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        {{ $child->name }}
                        <x-ts-icon icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        {{ $child->birth_year }} 
                    </td>
                </tr> 
            @endforeach
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            {{-- siblings --}}
            <tr class="border-t-2 border-b-2 border-gray-600 border-solid">
                <td>
                    {{ __('person.siblings') }}
                    @if (count($person->siblings()) > 0)
                        <x-ts-badge color="emerald" text="{{ count($person->siblings()) }}" />
                    @endif
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            @foreach ($person->siblings() as $sibling)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        {{ $sibling->name }}
                        <x-ts-icon icon="{{ $sibling->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        {{ $sibling->birth_year }} 
                        <span class="text-warning-500">{{ $sibling->type }}</span>
                    </td>
                </tr> 
            @endforeach
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            {{-- files --}}
            <tr class="border-t-2 border-b-2 border-gray-600 border-solid">
                <td>
                    {{ __('person.files') }}
                    @if ($this->person->countFiles() > 0)
                        <x-ts-badge color="emerald" text="{{ $this->person->countFiles() }}" />
                    @endif
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            @foreach ($person->getMedia('files') as $file)
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    {{ $file->name }}
                </td>
            </tr> 
        @endforeach
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            
            {{-- photos --}}
            <tr class="border-t-2 border-b-2 border-gray-600 border-solid">
                <td>{{ __('person.photos') }}</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
                        
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>
</div>
