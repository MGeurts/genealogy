@section('title')
    &vert; {{ __('app.family_chart') }}
@endsection

<x-app-layout>
    <div class="sticky top-[6.5rem] z-20 bg-gray-100 p-2 pb-5 dark:bg-gray-900">
        <livewire:people::heading :person="$person" />
    </div>

    <div class="w-full space-y-5 p-2">
        {{-- chart --}}
        <div class="flex flex-col rounded-sm bg-white text-neutral-800 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 dark:text-neutral-50">
            <div class="flex h-14 min-h-min flex-col rounded-t border-b-2 border-neutral-100 p-2 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50">
                <div class="flex flex-wrap items-start justify-center gap-2">
                    <div class="max-w-full min-w-max flex-1 grow">{{ __('app.family_chart') }}</div>

                    <div class="max-w-full min-w-max flex-1 grow text-end">
                        <x-ts-icon icon="tabler.social" class="inline-block size-5" />
                    </div>
                </div>
            </div>

            {{-- grandparents --}}
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end">
                    {{ trans('person.grandfather') }} & {{ trans('person.grandmother') }} :
                </div>

                <div class="basis-1/5 items-center border p-2 text-center">
                    @if ($person->father and $person->father->father)
                        <x-link
                            href="/people/{{ $person->father->father->id }}/chart"
                            @class(['text-red-600 dark:text-red-400' => $person->father->father->isDeceased()])
                        >
                            {{ $person->father->father->name }}
                        </x-link>
                        <x-ts-icon
                            icon="tabler.{{ $person->father->father->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                            class="inline-block size-5"
                        />
                    @else
                        <x-ts-icon icon="tabler.user-question" class="inline-block size-5" />
                    @endif
                </div>
                <div class="basis-1/5 border p-2 text-center">
                    @if ($person->father and $person->father->mother)
                        <x-link
                            href="/people/{{ $person->father->mother->id }}/chart"
                            @class(['text-red-600 dark:text-red-400' => $person->father->mother->isDeceased()])
                        >
                            {{ $person->father->mother->name }}
                        </x-link>
                        <x-ts-icon
                            icon="tabler.{{ $person->father->mother->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                            class="inline-block size-5"
                        />
                    @else
                        <x-ts-icon icon="tabler.user-question" class="inline-block size-5" />
                    @endif
                </div>
                <div class="basis-1/5 border p-2 text-center">
                    @if ($person->mother and $person->mother->father)
                        <x-link
                            href="/people/{{ $person->mother->father->id }}/chart"
                            @class(['text-red-600 dark:text-red-400' => $person->mother->father->isDeceased()])
                        >
                            {{ $person->mother->father->name }}
                        </x-link>
                        <x-ts-icon
                            icon="tabler.{{ $person->mother->father->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                            class="inline-block size-5"
                        />
                    @else
                        <x-ts-icon icon="tabler.user-question" class="inline-block size-5" />
                    @endif
                </div>
                <div class="basis-1/5 border p-2 text-center">
                    @if ($person->mother and $person->mother->mother)
                        <x-link
                            href="/people/{{ $person->mother->mother->id }}/chart"
                            @class(['text-red-600 dark:text-red-400' => $person->mother->mother->isDeceased()])
                        >
                            {{ $person->mother->mother->name }}
                        </x-link>
                        <x-ts-icon
                            icon="tabler.{{ $person->mother->mother->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                            class="inline-block size-5"
                        />
                    @else
                        <x-ts-icon icon="tabler.user-question" class="inline-block size-5" />
                    @endif
                </div>
            </div>

            {{-- uncles/ants & cousins --}}
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium">
                    {{ trans('person.uncles') }} & {{ trans('person.aunts') }} :<br />
                    {{ trans('person.cousins') }} :
                </div>

                <div class="basis-2/5 border p-2">
                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                        @if ($person->father)
                            @foreach ($person->father->siblings(true) as $index => $sibling)
                                <div>
                                    <x-link
                                        href="/people/{{ $sibling->id }}/chart"
                                        @class(['text-red-600 dark:text-red-400' => $sibling->isDeceased()])
                                    >
                                        {{ $sibling->name }}
                                    </x-link>
                                    <x-ts-icon
                                        icon="tabler.{{ $sibling->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                                        class="inline-block size-5"
                                    />

                                    <ul class="ml-8 list-disc">
                                        @foreach ($sibling->children as $child)
                                            <li>
                                                <x-link
                                                    href="/people/{{ $child->id }}/chart"
                                                    @class(['text-red-600 dark:text-red-400' => $child->isDeceased()])
                                                >
                                                    {{ $child->name }}
                                                </x-link>
                                                <x-ts-icon
                                                    icon="tabler.{{ $child->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                                                    class="inline-block size-5"
                                                />
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="basis-2/5 border p-2">
                    <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
                        @if ($person->mother)
                            @foreach ($person->mother->siblings(true) as $index => $sibling)
                                <div>
                                    <x-link
                                        href="/people/{{ $sibling->id }}/chart"
                                        @class(['text-red-600 dark:text-red-400' => $sibling->isDeceased()])
                                    >
                                        {{ $sibling->name }}
                                    </x-link>
                                    <x-ts-icon
                                        icon="tabler.{{ $sibling->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                                        class="inline-block size-5"
                                    />

                                    <ul class="ml-8 list-disc">
                                        @foreach ($sibling->children as $child)
                                            <li>
                                                <x-link
                                                    href="/people/{{ $child->id }}/chart"
                                                    @class(['text-red-600 dark:text-red-400' => $child->isDeceased()])
                                                >
                                                    {{ $child->name }}
                                                </x-link>
                                                <x-ts-icon
                                                    icon="tabler.{{ $child->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                                                    class="inline-block size-5"
                                                />
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            {{-- parents --}}
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium">
                    {{ trans('person.father') }} & {{ trans('person.mother') }} :
                </div>

                <div class="basis-2/5 border p-2 text-center">
                    @if ($person->father)
                        <x-link
                            href="/people/{{ $person->father->id }}/chart"
                            @class(['text-red-600 dark:text-red-400' => $person->father->isDeceased()])
                        >
                            {{ $person->father->name }}
                        </x-link>
                        <x-ts-icon
                            icon="tabler.{{ $person->father->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                            class="inline-block size-5"
                        />
                    @else
                        <x-ts-icon icon="tabler.user-question" class="inline-block size-5" />
                    @endif
                </div>
                <div class="basis-2/5 border p-2 text-center">
                    @if ($person->mother)
                        <x-link
                            href="/people/{{ $person->mother->id }}/chart"
                            @class(['text-red-600 dark:text-red-400' => $person->mother->isDeceased()])
                        >
                            {{ $person->mother->name }}
                        </x-link>
                        <x-ts-icon
                            icon="tabler.{{ $person->mother->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                            class="inline-block size-5"
                        />
                    @else
                        <x-ts-icon icon="tabler.user-question" class="inline-block size-5" />
                    @endif
                </div>
            </div>

            {{-- person --}}
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium"></div>

                <div class="basis-4/5 border p-2 text-center">
                    <x-link
                        href="/people/{{ $person->id }}/chart"
                        @class(['text-red-600 dark:text-red-400' => $person->isDeceased()])
                    >
                        {{ $person->name }}
                    </x-link>
                    <x-ts-icon
                        icon="tabler.{{ $person->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                        class="inline-block size-5"
                    />
                </div>
            </div>

            {{-- children & grandchildren --}}
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium">
                    {{ trans('person.children') }} :<br />
                    {{ trans('person.grandchildren') }} :
                </div>

                <div class="basis-4/5 border p-2">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($person->children_with_children as $index => $child)
                            <div>
                                <x-link
                                    href="/people/{{ $child->id }}/chart"
                                    @class(['text-red-600 dark:text-red-400' => $child->isDeceased()])
                                >
                                    {{ $child->name }}
                                </x-link>
                                <x-ts-icon
                                    icon="tabler.{{ $child->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                                    class="inline-block size-5"
                                />

                                <ul class="ml-8 list-disc">
                                    @foreach ($child->children as $grandchild)
                                        <li>
                                            <x-link
                                                href="/people/{{ $grandchild->id }}/chart"
                                                @class(['text-red-600 dark:text-red-400' => $grandchild->isDeceased()])
                                            >
                                                {{ $grandchild->name }}
                                            </x-link>
                                            <x-ts-icon
                                                icon="tabler.{{ $grandchild->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                                                class="inline-block size-5"
                                            />
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- siblings & nephews/nieces --}}
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium">
                    {{ trans('person.siblings') }} :<br />
                    {{ trans('person.nephews') }} & {{ trans('person.nieces') }} :
                </div>

                <div class="basis-4/5 border p-2">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                        @foreach ($person->siblings(true) as $index => $sibling)
                            <div>
                                <x-link
                                    href="/people/{{ $sibling->id }}/chart"
                                    @class(['text-red-600 dark:text-red-400' => $sibling->isDeceased()])
                                >
                                    {{ $sibling->name }}
                                </x-link>
                                <x-ts-icon
                                    icon="tabler.{{ $sibling->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                                    class="inline-block size-5"
                                />
                                <span class="text-yellow-500">{{ $sibling->type }}</span>

                                <ul class="ml-8 list-disc">
                                    @foreach ($sibling->children as $child)
                                        <li>
                                            <x-link
                                                href="/people/{{ $child->id }}/chart"
                                                @class(['text-red-600 dark:text-red-400' => $child->isDeceased()])
                                            >
                                                {{ $child->name }}
                                            </x-link>
                                            <x-ts-icon
                                                icon="tabler.{{ $child->sex === 'm' ? 'gender-male' : 'gender-female' }}"
                                                class="inline-block size-5"
                                            />
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
