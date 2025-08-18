<div class="overflow-x-auto bg-white rounded-sm dark:bg-neutral-700">
    <table class="min-w-full text-left whitespace-nowrap">
        <thead class="tracking-wider uppercase border-t border-b-2 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
            <tr>
                <th scope="col" class="p-2 border-x dark:border-neutral-600">
                    {{ __('team.team') }}
                </th>
                <th scope="col" class="p-2 border-x dark:border-neutral-600">
                    {{ __('team.users') }}
                    <x-ts-badge color="emerald" text="{{ count($user->currentTeam->users) }}" />
                </th>
                <th scope="col" class="p-2 border-x dark:border-neutral-600">
                    {{ __('team.persons') }}
                    <x-ts-badge color="emerald" text="{{ count($user->currentTeam->persons) }}" />
                </th>
                <th scope="col" class="p-2 border-x dark:border-neutral-600">
                    {{ __('team.couples') }}
                    <x-ts-badge color="emerald" text="{{ count($user->currentTeam->couples) }}" />
                </th>
            </tr>
        </thead>

        <tbody>
            <tr class="border-b dark:border-neutral-600">
                <td class="p-2 align-top border-x dark:border-neutral-600">
                    {{ $user->currentTeam->name }}
                </td>

                <td class="p-2 align-top border-x dark:border-neutral-600">
                    @foreach ($user->currentTeam->users->sortBy('name') as $member)
                        {{ $member->name }}<br />
                    @endforeach
                </td>

                <td class="p-2 align-top border-x dark:border-neutral-600">
                    @foreach ($user->currentTeam->persons->sortBy('name') as $person)
                        <x-ts-link href="/people/{{ $person->id }}" title="{{ __('app.show') }}">{{ $person->name }}</x-ts-link>
                        <x-ts-icon icon="tabler.{{ $person->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        <br />
                    @endforeach
                </td>

                <td class="p-2 align-top border-x dark:border-neutral-600">
                    @foreach ($user->currentTeam->couples as $couple)
                        <x-ts-link href="/people/{{ $couple->person1->id }}" title="{{ __('app.show') }}">{{ $couple->person1->name }}</x-ts-link>
                        <x-ts-icon icon="tabler.{{ $couple->person1->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        &nbsp;-&nbsp;
                        <x-ts-link href="/people/{{ $couple->person2->id }}" title="{{ __('app.show') }}">{{ $couple->person2->name }}</x-ts-link>
                        <x-ts-icon icon="tabler.{{ $couple->person2->sex === 'm' ? 'gender-male' : 'gender-female' }}" class="inline-block size-5" />
                        <br />
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>
</div>
