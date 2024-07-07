<div class="overflow-x-auto bg-white rounded dark:bg-neutral-700">
    <table class="min-w-full text-left whitespace-nowrap">
        <thead class="tracking-wider uppercase border-t border-b-2 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
            <tr>
                <th scope="col" class="p-2 border-x dark:border-neutral-600">{{ __('team.team') }}</th>
                <th scope="col" class="p-2 border-x dark:border-neutral-600">{{ __('team.users') }}</th>
                <th scope="col" class="p-2 border-x dark:border-neutral-600">{{ __('team.persons') }}</th>
                <th scope="col" class="p-2 border-x dark:border-neutral-600">{{ __('team.couples') }}</th>
            </tr>
        </thead>

        <tbody>
            <tr class="border-b dark:border-neutral-600">
                @php
                    echo '<td class="p-2 align-top border-x dark:border-neutral-600">';
                        echo $user->currentTeam->name;
                    echo '</td>';

                    echo '<td class="p-2 align-top border-x dark:border-neutral-600">';
                        foreach ($user->currentTeam->users->sortBy('name') as $member) {
                            echo $member->name . '<br/>';
                        }   
                    echo '</td>';

                    echo '<td class="p-2 align-top border-x dark:border-neutral-600">';
                        foreach ($user->currentTeam->persons->sortBy('name') as $person) {
                            echo $person->name . '<br/>';
                        }   
                    echo '</td>';

                    echo '<td class="p-2 align-top border-x dark:border-neutral-600">';
                        foreach ($user->currentTeam->couples as $couple) {
                            echo $couple->person_1->name . ' - ' . $couple->person_2->name . '<br/>';
                        }   
                    echo '</td>';
                @endphp
            </tr>
        </tbody>
    </table>
</div>

