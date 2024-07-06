<div>
    <div class="overflow-x-auto bg-white rounded dark:bg-neutral-700">
        <table class="min-w-full text-sm text-left whitespace-nowrap">
            <thead class="tracking-wider uppercase border-t border-b-2 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <tr>
                    <th scope="col" class="p-4 border-x dark:border-neutral-600">Team</th>
                    <th scope="col" class="p-4 border-x dark:border-neutral-600">Users</th>
                </tr>
            </thead>

            <tbody>
                @php
                    foreach ($user->teams as $team) {
                        echo '<tr class="border-b dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-600">';
                            echo '<th scope="row" class="p-4 align-top border-x dark:border-neutral-600">';
                                echo $team->name;
                            echo '</th>';

                            echo '<td class="p-4 border-x dark:border-neutral-600">';
                                foreach ($team->users as $member) {
                                    echo $member->name . '<br />';
                                }   
                            echo '</td>';
                        echo '</tr>';
                    }
                @endphp
            </tbody>
        </table>
    </div>

    <div class="mt-5 overflow-x-auto bg-white rounded dark:bg-neutral-700">
        <table class="min-w-full text-sm text-left whitespace-nowrap">
            <thead class="tracking-wider uppercase border-t border-b-2 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <tr>
                    <th scope="col" class="p-4 border-x dark:border-neutral-600">Team</th>
                    <th scope="col" class="p-4 border-x dark:border-neutral-600">People</th>
                </tr>
            </thead>

            <tbody>
                @php
                    foreach ($user->teams as $team) {
                        echo '<tr class="border-b dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-600">';
                            echo '<th scope="row" class="p-4 align-top border-x dark:border-neutral-600">';
                                echo $team->name;
                            echo '</th>';

                            echo '<td class="p-4 border-x dark:border-neutral-600">';
                                foreach ($team->persons as $person) {
                                    echo $person->name . '<br />';
                                }   
                            echo '</td>';
                        echo '</tr>';
                    }
                @endphp
            </tbody>
        </table>
    </div>

    <div class="mt-5 overflow-x-auto bg-white rounded dark:bg-neutral-700">
        <table class="min-w-full text-sm text-left whitespace-nowrap">
            <thead class="tracking-wider uppercase border-t border-b-2 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-800">
                <tr>
                    <th scope="col" class="p-4 border-x dark:border-neutral-600">Team</th>
                    <th scope="col" class="p-4 border-x dark:border-neutral-600">Couples</th>
                </tr>
            </thead>

            <tbody>
                @php
                    foreach ($user->teams as $team) {
                        echo '<tr class="border-b dark:border-neutral-600 hover:bg-neutral-100 dark:hover:bg-neutral-600">';
                            echo '<th scope="row" class="p-4 align-top border-x dark:border-neutral-600">';
                                echo $team->name;
                            echo '</th>';

                            echo '<td class="p-4 border-x dark:border-neutral-600">';
                                foreach ($team->couples as $couple) {
                                    echo $couple->person1_id . ' - ' . $couple->person2_id . '<br />';
                                }   
                            echo '</td>';
                        echo '</tr>';
                    }
                @endphp
            </tbody>
        </table>
    </div>

    @php
        // dump($user);
    @endphp
</div>
