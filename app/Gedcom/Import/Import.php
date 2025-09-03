<?php

declare(strict_types=1);

namespace App\Gedcom\Import;

use App\Models\Team;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Events\AddingTeam;

/**
 * Main GEDCOM Import orchestrator class
 */
final class Import implements CreatesTeams
{
    public User $user;

    private Team $team;

    private GedcomParser $parser;

    private IndividualImporter $individualImporter;

    private FamilyImporter $familyImporter;

    private CoupleCreator $coupleCreator;

    /**
     * Initialize with user and create a new team
     */
    public function __construct(?string $teamName, ?string $teamDescription)
    {
        $this->user = auth()->user();

        // Create new team for this import
        $this->team = $this->createTeam($teamName, $teamDescription);

        // Initialize sub-components
        $this->parser             = new GedcomParser();
        $this->individualImporter = new IndividualImporter($this->team);
        $this->familyImporter     = new FamilyImporter($this->team);
        $this->coupleCreator      = new CoupleCreator($this->team);
    }

    /**
     * Import GEDCOM file content
     */
    public function import(string $gedcomContent): array
    {
        // At the start of your import method, increase time and memory limits
        ini_set('max_execution_time', 300); // 5 minutes
        ini_set('memory_limit', '512M');

        try {
            DB::beginTransaction();

            // Parse GEDCOM content
            $parsedData = $this->parser->parse($gedcomContent);

            Log::info('GEDCOM IMPORT: parseGedcom', ['gedcomData' => $parsedData->getGedcomData()]);

            // Import individuals first
            $personMap = $this->individualImporter->import($parsedData->getIndividuals());

            Log::info('GEDCOM IMPORT: importIndividuals', [
                'individuals' => $parsedData->getIndividuals(),
                'personMap'   => $personMap,
            ]);

            // Import families and relationships
            $familyMap = $this->familyImporter->import($parsedData->getFamilies(), $personMap);

            Log::info('GEDCOM IMPORT: importFamilies', [
                'families'  => $parsedData->getFamilies(),
                'familyMap' => $familyMap,
            ]);

            // Create couples from families
            $this->coupleCreator->create($familyMap, $personMap);

            Log::info('GEDCOM IMPORT: createCouples', ['data' => '????']);

            DB::commit();

            return [
                'success'              => true,
                'team'                 => $this->team->name,
                'individuals_imported' => count($personMap),
                'families_imported'    => count($familyMap),
                'message'              => 'GEDCOM file imported successfully',
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('GEDCOM Import Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Get import statistics
     */
    public function getStatistics(): array
    {
        // Only get parsed data if parsing has been done
        $parsedData = $this->parser->getParsedData();

        return [
            'individuals_parsed'   => $parsedData ? count($parsedData->getIndividuals()) : 0,
            'families_parsed'      => $parsedData ? count($parsedData->getFamilies()) : 0,
            'individuals_imported' => count($this->individualImporter->getPersonMap()),
            'families_imported'    => count($this->familyImporter->getFamilyMap()),
        ];
    }

    /**
     * Create a new team for the import
     */
    private function createTeam(string $name, ?string $description): Team
    {
        AddingTeam::dispatch($this->user);

        $this->user->switchTeam($team = $this->user->ownedTeams()->create([
            'name'          => $name,
            'description'   => $description ?? null,
            'personal_team' => false,
        ]));

        // -----------------------------------------------------------------------
        // create team photo folder
        // -----------------------------------------------------------------------
        if (! Storage::disk('photos')->exists($team->id)) {
            Storage::disk('photos')->makeDirectory($team->id);
        }
        // -----------------------------------------------------------------------

        return $team;
    }
}
