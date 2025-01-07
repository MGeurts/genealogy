@section('title')
    &vert; {{ __('app.family_chart') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        {{ __('app.family_chart') }}
    </x-slot>

    <div class="p-2 pb-5 sticky top-[6.5rem] z-20 bg-gray-100 dark:bg-gray-900">
        <livewire:people.heading :person="$person" />
    </div>

    <div class="w-full p-2 space-y-5">
        {{-- chart --}}
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50">
            <div class="flex flex-col p-2 text-lg font-medium border-b-2 rounded-t h-14 min-h-min border-neutral-100 dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="flex-1 flex-grow max-w-full min-w-max">
                        {{ __('app.family_chart') }}
                    </div>

                    <div class="flex-1 flex-grow max-w-full min-w-max text-end">
                        <x-ts-icon icon="social" class="inline-block size-5" />
                    </div>
                </div>
            </div>

            {{-- grandparents --}}
            <div class="flex flex-row">
                <div class="p-2 border basis-1/5 text-end">{{ trans('person.grandfather') }} & {{ trans('person.grandmother') }} :</div>

                <div class="items-center p-2 text-center border basis-1/5">
                    @if ($person->father and $person->father->father)
                        <x-link href="/people/{{ $person->father->father->id }}/chart" @class([
                            'text-danger-600 dark:text-danger-400' => $person->father->father->isDeceased(),
                        ])>
                            {{ $person->father->father->name }}
                        </x-link>
                        <x-ts-icon icon="{{ $person->father->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    @else
                        <x-ts-icon icon="user-question" class="inline-block" />
                    @endif
                </div>
                <div class="p-2 text-center border basis-1/5">
                    @if ($person->father and $person->father->mother)
                        <x-link href="/people/{{ $person->father->mother->id }}/chart" @class([
                            'text-danger-600 dark:text-danger-400' => $person->father->mother->isDeceased(),
                        ])>
                            {{ $person->father->mother->name }}
                        </x-link>
                        <x-ts-icon icon="{{ $person->father->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    @else
                        <x-ts-icon icon="user-question" class="inline-block" />
                    @endif
                </div>
                <div class="p-2 text-center border basis-1/5">
                    @if ($person->mother and $person->mother->father)
                        <x-link href="/people/{{ $person->mother->father->id }}/chart" @class([
                            'text-danger-600 dark:text-danger-400' => $person->mother->father->isDeceased(),
                        ])>
                            {{ $person->mother->father->name }}
                        </x-link>
                        <x-ts-icon icon="{{ $person->mother->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    @else
                        <x-ts-icon icon="user-question" class="inline-block" />
                    @endif
                </div>
                <div class="p-2 text-center border basis-1/5">
                    @if ($person->mother and $person->mother->mother)
                        <x-link href="/people/{{ $person->mother->mother->id }}/chart" @class([
                            'text-danger-600 dark:text-danger-400' => $person->mother->mother->isDeceased(),
                        ])>
                            {{ $person->mother->mother->name }}
                        </x-link>
                        <x-ts-icon icon="{{ $person->mother->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    @else
                        <x-ts-icon icon="user-question" class="inline-block" />
                    @endif
                </div>
            </div>

            {{-- uncles/ants & cousins --}}
            <div class="flex flex-row">
                <div class="p-2 font-medium border basis-1/5 text-end">
                    {{ trans('person.uncles') }} & {{ trans('person.aunts') }} :<br />
                    {{ trans('person.cousins') }} :
                </div>

                <div class="p-2 border basis-2/5">
                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                        @if ($person->father)
                            @foreach ($person->father->siblings(true) as $index => $sibling)
                                <div>
                                    {{ $index + 1 }}.
                                    <x-link href="/people/{{ $sibling->id }}/chart" @class([
                                        'text-danger-600 dark:text-danger-400' => $sibling->isDeceased(),
                                    ])>
                                        {{ $sibling->name }}
                                    </x-link>
                                    <x-ts-icon icon="{{ $sibling->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />

                                    <ol class="ml-8 list-decimal">
                                        @foreach ($sibling->children as $child)
                                            <li>
                                                <x-link href="/people/{{ $child->id }}/chart" @class([
                                                    'text-danger-600 dark:text-danger-400' => $child->isDeceased(),
                                                ])>
                                                    {{ $child->name }}
                                                </x-link>
                                                <x-ts-icon icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="p-2 border basis-2/5">
                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                        @if ($person->mother)
                            @foreach ($person->mother->siblings(true) as $index => $sibling)
                                <div>
                                    {{ $index + 1 }}.
                                    <x-link href="/people/{{ $sibling->id }}/chart" @class([
                                        'text-danger-600 dark:text-danger-400' => $sibling->isDeceased(),
                                    ])>
                                        {{ $sibling->name }}
                                    </x-link>
                                    <x-ts-icon icon="{{ $sibling->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />

                                    <ol class="ml-8 list-decimal">
                                        @foreach ($sibling->children as $child)
                                            <li>
                                                <x-link href="/people/{{ $child->id }}/chart" @class([
                                                    'text-danger-600 dark:text-danger-400' => $child->isDeceased(),
                                                ])>
                                                    {{ $child->name }}
                                                </x-link>
                                                <x-ts-icon icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- parents --}}
            <div class="flex flex-row">
                <div class="p-2 font-medium border basis-1/5 text-end">{{ trans('person.father') }} & {{ trans('person.mother') }} :</div>

                <div class="p-2 text-center border basis-2/5">
                    @if ($person->father)
                        <x-link href="/people/{{ $person->father->id }}/chart" @class([
                            'text-danger-600 dark:text-danger-400' => $person->father->isDeceased(),
                        ])>
                            {{ $person->father->name }}
                        </x-link>
                        <x-ts-icon icon="{{ $person->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    @else
                        <x-ts-icon icon="user-question" class="inline-block" />
                    @endif
                </div>
                <div class="p-2 text-center border basis-2/5">
                    @if ($person->mother)
                        <x-link href="/people/{{ $person->mother->id }}/chart" @class([
                            'text-danger-600 dark:text-danger-400' => $person->mother->isDeceased(),
                        ])>
                            {{ $person->mother->name }}
                        </x-link>
                        <x-ts-icon icon="{{ $person->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                    @else
                        <x-ts-icon icon="user-question" class="inline-block" />
                    @endif
                </div>
            </div>

            {{-- person --}}
            <div class="flex flex-row">
                <div class="p-2 font-medium border basis-1/5 text-end"></div>

                <div class="p-2 text-center border basis-4/5">
                    <x-link href="/people/{{ $person->id }}/chart" @class([
                        'text-danger-600 dark:text-danger-400' => $person->isDeceased(),
                    ])>
                        {{ $person->name }}
                    </x-link>
                    <x-ts-icon icon="{{ $person->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                </div>
            </div>

            {{-- children & grandchildren --}}
            <div class="flex flex-row">
                <div class="p-2 font-medium border basis-1/5 text-end">
                    {{ trans('person.children') }} :<br />
                    {{ trans('person.grandchildren') }} :
                </div>

                <div class="p-2 border basis-4/5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($person->children_with_children as $index => $child)
                            <div>
                                {{ $index + 1 }}.
                                <x-link href="/people/{{ $child->id }}/chart" @class([
                                    'text-danger-600 dark:text-danger-400' => $child->isDeceased(),
                                ])>
                                    {{ $child->name }}
                                </x-link>
                                <x-ts-icon icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />

                                <ol class="ml-8 list-decimal">
                                    @foreach ($child->children as $grandchild)
                                        <li>
                                            <x-link href="/people/{{ $grandchild->id }}/chart" @class([
                                                'text-danger-600 dark:text-danger-400' => $grandchild->isDeceased(),
                                            ])>
                                                {{ $grandchild->name }}
                                            </x-link>
                                            <x-ts-icon icon="{{ $grandchild->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- siblings & nephews/nieces --}}
            <div class="flex flex-row">
                <div class="p-2 font-medium border basis-1/5 text-end">
                    {{ trans('person.siblings') }} :<br />
                    {{ trans('person.nephews') }} & {{ trans('person.nieces') }} :
                </div>

                <div class="p-2 border basis-4/5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($person->siblings(true) as $index => $sibling)
                            <div>
                                {{ $index + 1 }}.
                                <x-link href="/people/{{ $sibling->id }}/chart" @class([
                                    'text-danger-600 dark:text-danger-400' => $sibling->isDeceased(),
                                ])>
                                    {{ $sibling->name }}
                                </x-link>
                                <x-ts-icon icon="{{ $sibling->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />

                                <ol class="ml-8 list-decimal">
                                    @foreach ($sibling->children as $child)
                                        <li>
                                            <x-link href="/people/{{ $child->id }}/chart" @class([
                                                'text-danger-600 dark:text-danger-400' => $child->isDeceased(),
                                            ])>
                                                {{ $child->name }}
                                            </x-link>
                                            <x-ts-icon icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
