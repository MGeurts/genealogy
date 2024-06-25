@section('title')
    &vert; Test
@endsection

<x-app-layout>
    <x-slot name="heading">
        Test
    </x-slot>

    <div class="grow max-w-5xl overflow-x-auto py-5 dark:text-neutral-200">

        <p>Use this page to test components ...</p>
        <br />

        @php
            // ----------------------------------------------------------------------------------------------------------------------

            /*
            // Path to your GEDCOM file
            $file = './gedcom/demo.ged';

            // Read the file into an array of lines
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Initialize variables
            $individuals = [];
            $currentIndividual = null;
            $currentFamily = null;
            $families = [];
            $inBirth = false;
            $inDeath = false;

            // Parse each line
            foreach ($lines as $line) {
                // Split the line into level, tag, and value
                preg_match('/^(\d+)\s+(@\w+@)?\s*(\w+)?\s*(.*)$/', $line, $matches);
                if (count($matches) < 4) {
                    continue;
                }

                [, $level, $xref, $tag, $value] = $matches;

                // Handle individual records
                if ($tag === 'INDI') {
                    if ($currentIndividual) {
                        $individuals[] = $currentIndividual;
                    }
                    $currentIndividual = ['ID' => $xref, 'NAME' => '', 'BIRT' => '', 'DEAT' => ''];
                    $inBirth = false;
                    $inDeath = false;
                } elseif ($tag === 'NAME' && $currentIndividual) {
                    $currentIndividual['NAME'] = $value;
                } elseif ($tag === 'BIRT' && $currentIndividual) {
                    $inBirth = true;
                    $inDeath = false;
                } elseif ($tag === 'DEAT' && $currentIndividual) {
                    $inDeath = true;
                    $inBirth = false;
                } elseif ($tag === 'DATE' && $currentIndividual) {
                    if ($inBirth) {
                        $currentIndividual['BIRT'] = $value;
                    } elseif ($inDeath) {
                        $currentIndividual['DEAT'] = $value;
                    }
                }

                // Handle family records
                if ($tag === 'FAM') {
                    if ($currentFamily) {
                        $families[] = $currentFamily;
                    }
                    $currentFamily = ['ID' => $xref, 'HUSB' => '', 'WIFE' => '', 'CHIL' => []];
                } elseif ($tag === 'HUSB' && $currentFamily) {
                    $currentFamily['HUSB'] = $value;
                } elseif ($tag === 'WIFE' && $currentFamily) {
                    $currentFamily['WIFE'] = $value;
                } elseif ($tag === 'CHIL' && $currentFamily) {
                    $currentFamily['CHIL'][] = $value;
                }
            }

            // Add the last individual and family
            if ($currentIndividual) {
                $individuals[] = $currentIndividual;
            }
            if ($currentFamily) {
                $families[] = $currentFamily;
            }

            // Output the parsed individuals
            echo 'Individuals:<br/>';
            foreach ($individuals as $individual) {
                echo $individual['ID'] . ' : ' . $individual['NAME'] . '<br/>&emsp;Born : ' . $individual['BIRT'] . '<br/>&emsp;Died : ' . $individual['DEAT'] . '<br/>';
            }

            // Output the parsed families
            echo "<br/>Families:<br/>";
            foreach ($families as $family) {
                echo $family['ID'] . ' : <br/>&emsp;Husband : ' . $family['HUSB'] . '<br/>&emsp;Wife : ' . $family['WIFE'] . '<br/>&emsp;Children : ' . implode(', ', $family['CHIL']) . '<br/>';
            } 
            */

            // ----------------------------------------------------------------------------------------------------------------------

            /*             // Path to your GEDCOM file
            $file = './gedcom/demo.ged';

            // Read the file into an array of lines
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Initialize variables
            $individuals = [];
            $currentIndividual = null;
            $currentFamily = null;
            $families = [];
            $currentTag = null;

            // Parse each line
            foreach ($lines as $line) {
                // Split the line into level, tag, and value
                preg_match('/^(\d+)\s+(@\w+@)?\s*(\w+)?\s*(.*)$/', $line, $matches);
                if (count($matches) < 4) {
                    continue;
                }

                [, $level, $xref, $tag, $value] = $matches;

                // Handle individual records
                if ($tag === 'INDI') {
                    if ($currentIndividual) {
                        $individuals[] = $currentIndividual;
                    }
                    $currentIndividual = ['ID' => $xref, 'TAGS' => []];
                    $currentTag = null;
                } elseif ($tag === 'FAM') {
                    if ($currentFamily) {
                        $families[] = $currentFamily;
                    }
                    $currentFamily = ['ID' => $xref, 'TAGS' => []];
                    $currentTag = null;
                } elseif ($currentIndividual) {
                    if ($level == 1) {
                        $currentTag = $tag;
                        $currentIndividual['TAGS'][$tag] = $value;
                    } elseif ($level > 1 && $currentTag) {
                        if (!isset($currentIndividual['TAGS'][$currentTag])) {
                            $currentIndividual['TAGS'][$currentTag] = [];
                        }
                        if (!is_array($currentIndividual['TAGS'][$currentTag])) {
                            $currentIndividual['TAGS'][$currentTag] = [$currentIndividual['TAGS'][$currentTag]];
                        }
                        $currentIndividual['TAGS'][$currentTag][$tag] = $value;
                    }
                } elseif ($currentFamily) {
                    if ($level == 1) {
                        $currentTag = $tag;
                        $currentFamily['TAGS'][$tag] = $value;
                    } elseif ($level > 1 && $currentTag) {
                        if (!isset($currentFamily['TAGS'][$currentTag])) {
                            $currentFamily['TAGS'][$currentTag] = [];
                        }
                        if (!is_array($currentFamily['TAGS'][$currentTag])) {
                            $currentFamily['TAGS'][$currentTag] = [$currentFamily['TAGS'][$currentTag]];
                        }
                        $currentFamily['TAGS'][$currentTag][$tag] = $value;
                    }
                }
            }

            // Add the last individual and family
            if ($currentIndividual) {
                $individuals[] = $currentIndividual;
            }
            if ($currentFamily) {
                $families[] = $currentFamily;
            }

            // Output the parsed individuals
            echo 'Individuals :<br/>';
            foreach ($individuals as $individual) {
                echo $individual['ID'] . ' :<br/>';
                foreach ($individual['TAGS'] as $tag => $value) {
                    if (is_array($value)) {
                        echo "&emsp;$tag&nbsp;:<br/>";
                        foreach ($value as $subTag => $subValue) {
                            echo "&emsp;&emsp;$subTag&nbsp;: $subValue<br/>";
                        }
                    } else {
                        echo "&emsp;$tag&nbsp;: $value<br/>";
                    }
                }
            }

            // Output the parsed families
            echo '<br/>Families :<br/>';
            foreach ($families as $family) {
                echo $family['ID'] . ':<br/>';
                foreach ($family['TAGS'] as $tag => $value) {
                    echo "&emsp;$tag&nbsp: $value<br/>";
                }
            }

            // Debugging output
            echo '<br/>Debugging Information:<br/>';
            // echo 'Individuals:<br/>';
            // print_r($individuals);
            echo '<br/>Families:<br/>';
            print_r($families); */

            // ----------------------------------------------------------------------------------------------------------------------
            $parser = new App\GedcomParser();
            $parser->parse('./gedcom/royals.ged');

            echo 'Individuals :<br/><br/>';
            $parser->outputIndividuals();

            echo '<br/>Families :<br/><br/>';
            $parser->outputFamilies();
            // ----------------------------------------------------------------------------------------------------------------------
        @endphp

    </div>
</x-app-layout>
