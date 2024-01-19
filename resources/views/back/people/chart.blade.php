@section('title')
    &vert; {{ __('app.family_chart') }}
@endsection

<x-app-layout>
    <x-slot name="heading">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">
            {{ __('app.family_chart') }}
        </h2>
    </x-slot>

    <div class="w-full py-5 space-y-5">
        <!-- heading -->
        <livewire:people.heading :person="$person" />

        <!-- chart -->
        <div class="flex flex-col rounded bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] dark:bg-neutral-700 text-neutral-800 dark:text-neutral-50"">
            <div class="h-14 min-h-min flex flex-col p-2 border-b-2 border-neutral-100 text-lg font-medium dark:border-neutral-600 dark:text-neutral-50 rounded-t">
                <div class="flex flex-wrap gap-2 justify-center items-start">
                    <div class="flex-grow min-w-max max-w-full flex-1">
                        {{ __('app.family_chart') }}
                    </div>
                </div>
            </div>

            <!-- grandparents -->
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium bg-white-500">{{ trans('person.grandfather') }} & {{ trans('person.grandmother') }} :</div>

                <div class="basis-1/5 border p-2 text-center">
                    @if ($person->father && $person->father->father)
                        <x-link wire:navigate href="/people/{{ $person->father->father->id }}/chart" class="{{ $person->father->father->isDeceased() ? '!text-danger' : '' }}">
                            <b>{{ $person->father->father->name }}</b>
                        </x-link>
                        <x-icon.tabler icon="{{ $person->father->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @else
                        <x-icon.tabler icon="user-question" />
                    @endif
                </div>
                <div class="basis-1/5 border p-2 text-center">
                    @if ($person->father && $person->father->mother)
                        <x-link wire:navigate href="/people/{{ $person->father->mother->id }}/chart" class="{{ $person->father->mother->isDeceased() ? '!text-danger' : '' }}">
                            <b>{{ $person->father->mother->name }}</b>
                        </x-link>
                        <x-icon.tabler icon="{{ $person->father->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @else
                        <x-icon.tabler icon="user-question" />
                    @endif
                </div>
                <div class="basis-1/5 border p-2 text-center">
                    @if ($person->mother && $person->mother->father)
                        <x-link wire:navigate href="/people/{{ $person->mother->father->id }}/chart" class="{{ $person->mother->father->isDeceased() ? '!text-danger' : '' }}">
                            <b>{{ $person->mother->father->name }}</b>
                        </x-link>
                        <x-icon.tabler icon="{{ $person->mother->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @else
                        <x-icon.tabler icon="user-question" />
                    @endif
                </div>
                <div class="basis-1/5 border p-2 text-center">
                    @if ($person->mother && $person->mother->mother)
                        <x-link wire:navigate href="/people/{{ $person->mother->mother->id }}/chart" class="{{ $person->mother->mother->isDeceased() ? '!text-danger' : '' }}">
                            <b>{{ $person->mother->mother->name }}</b>
                        </x-link>
                        <x-icon.tabler icon="{{ $person->mother->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @else
                        <x-icon.tabler icon="user-question" />
                    @endif
                </div>
            </div>

            <!-- uncles/ants & cousins -->
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium">
                    {{ trans('person.uncles') }} & {{ trans('person.aunts') }} :<br />
                    {{ trans('person.cousins') }} :
                </div>

                <div class="basis-2/5 border p-2">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        @if ($person->father)
                            @php $no = 0; @endphp
                            @foreach ($person->father->siblings() as $sibling)
                                <div>
                                    {{ ++$no }}.
                                    <x-link wire:navigate href="/people/{{ $sibling->id }}/chart" class="{{ $sibling->isDeceased() ? '!text-danger' : '' }}">
                                        <b>{{ $sibling->name }}</b>
                                    </x-link>
                                    <x-icon.tabler icon="{{ $sibling->sex == 'm' ? 'gender-male' : 'gender-female' }}" />

                                    <ol class="ml-8 list-decimal">
                                        @foreach ($sibling->children as $child)
                                            <li>
                                                <x-link wire:navigate href="/people/{{ $child->id }}/chart" class="{{ $child->isDeceased() ? '!text-danger' : '' }}">
                                                    <b>{{ $child->name }}</b>
                                                </x-link>
                                                <x-icon.tabler icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="basis-2/5 border p-2">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        @if ($person->mother)
                            @php $no = 0; @endphp

                            @foreach ($person->mother->siblings() as $sibling)
                                <div>
                                    {{ ++$no }}.
                                    <x-link wire:navigate href="/people/{{ $sibling->id }}/chart" class="{{ $sibling->isDeceased() ? '!text-danger' : '' }}">
                                        <b>{{ $sibling->name }}</b>
                                    </x-link>
                                    <x-icon.tabler icon="{{ $sibling->sex == 'm' ? 'gender-male' : 'gender-female' }}" />

                                    <ol class="ml-8 list-decimal">
                                        @foreach ($sibling->children as $child)
                                            <li>
                                                <x-link wire:navigate href="/people/{{ $child->id }}/chart" class="{{ $child->isDeceased() ? '!text-danger' : '' }}">
                                                    <b>{{ $child->name }}</b>
                                                </x-link>
                                                <x-icon.tabler icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                                            </li>
                                        @endforeach
                                    </ol>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- parents -->
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium">{{ trans('person.father') }} & {{ trans('person.mother') }} :</div>

                <div class="basis-2/5 border p-2 text-center">
                    @if ($person->father)
                        <x-link wire:navigate href="/people/{{ $person->father->id }}/chart" class="{{ $person->father->isDeceased() ? '!text-danger' : '' }}">
                            <b>{{ $person->father->name }}</b>
                        </x-link>
                        <x-icon.tabler icon="{{ $person->father->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @else
                        <x-icon.tabler icon="user-question" />
                    @endif
                </div>
                <div class="basis-2/5 border p-2 text-center">
                    @if ($person->mother)
                        <x-link wire:navigate href="/people/{{ $person->mother->id }}/chart" class="{{ $person->mother->isDeceased() ? '!text-danger' : '' }}">
                            <b>{{ $person->mother->name }}</b>
                        </x-link>
                        <x-icon.tabler icon="{{ $person->mother->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                    @else
                        <x-icon.tabler icon="user-question" />
                    @endif
                </div>
            </div>

            <!-- user -->
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium"></div>

                <div class="basis-4/5 border p-2 text-center">
                    <x-link wire:navigate href="/people/{{ $person->id }}/chart" class="{{ $person->isDeceased() ? '!text-danger' : '' }}">
                        <b>{{ $person->name }}</b>
                    </x-link>
                    <x-icon.tabler icon="{{ $person->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                </div>
            </div>

            <!-- children & grandchildren -->
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium">
                    {{ trans('person.children') }} :<br />
                    {{ trans('person.grandchildren') }} :
                </div>

                <div class="basis-4/5 border p-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        @php $no = 0; @endphp

                        @foreach ($person->children_with_children as $child)
                            <div>
                                {{ ++$no }}.
                                <x-link wire:navigate href="/people/{{ $child->id }}/chart" class="{{ $child->isDeceased() ? '!text-danger' : '' }}">
                                    <b>{{ $child->name }}</b>
                                </x-link>
                                <x-icon.tabler icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" />

                                <ol class="ml-8 list-decimal">
                                    @foreach ($child->children as $grandchild)
                                        <li>
                                            <x-link wire:navigate href="/people/{{ $grandchild->id }}/chart" class="{{ $grandchild->isDeceased() ? '!text-danger' : '' }}">
                                                <b>{{ $grandchild->name }}</b>
                                            </x-link>
                                            <x-icon.tabler icon="{{ $grandchild->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- siblings & nephews/nieces -->
            <div class="flex flex-row">
                <div class="basis-1/5 border p-2 text-end font-medium">
                    {{ trans('person.siblings') }} :<br />
                    {{ trans('person.nephews') }} & {{ trans('person.nieces') }} :
                </div>

                <div class="basis-4/5 border p-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        @php $no = 0; @endphp

                        @foreach ($person->siblings_with_children() as $sibling)
                            <div>
                                {{ ++$no }}.
                                <x-link wire:navigate href="/people/{{ $sibling->id }}/chart" class="{{ $sibling->isDeceased() ? '!text-danger' : '' }}">
                                    <b>{{ $sibling->name }}</b>
                                </x-link>
                                <x-icon.tabler icon="{{ $sibling->sex == 'm' ? 'gender-male' : 'gender-female' }}" />

                                <ol class="ml-8 list-decimal">
                                    @foreach ($sibling->children as $child)
                                        <li>
                                            <x-link wire:navigate href="/people/{{ $child->id }}/chart" class="{{ $child->isDeceased() ? '!text-danger' : '' }}">
                                                <b>{{ $child->name }}</b>
                                            </x-link>
                                            <x-icon.tabler icon="{{ $child->sex == 'm' ? 'gender-male' : 'gender-female' }}" />
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
