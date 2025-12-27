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
 * Supports both .ged files and .zip files with media
 */
final class Import implements CreatesTeams
{
    public User $user;

    private Team $team;

    private GedcomParser $parser;

    private IndividualImporter $individualImporter;

    private FamilyImporter $familyImporter;

    private CoupleCreator $coupleCreator;

    private ?MediaImportHandler $mediaHandler = null;

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
     * Import GEDCOM file content (text only)
     *
     * @param  string  $gedcomContent  Raw GEDCOM text content
     * @return array Import results
     */
    public function import(string $gedcomContent): array
    {
        return $this->processImport($gedcomContent, []);
    }

    /**
     * Import GEDCOM from ZIP file (with media)
     *
     * @param  string  $zipPath  Path to ZIP file
     * @return array Import results
     */
    public function importFromZip(string $zipPath): array
    {
        $zipImporter = new ZipImporter();

        try {
            // Extract ZIP file
            Log::info('Extracting ZIP file', ['path' => $zipPath]);
            $zipImporter->extract($zipPath);

            $gedcomContent = $zipImporter->getGedcomContent();
            $mediaFiles    = $zipImporter->getMediaFiles();

            Log::info('ZIP extracted successfully', [
                'media_files' => count($mediaFiles),
                'gedcom_size' => mb_strlen($gedcomContent),
            ]);

            // Process import with media files
            return $this->processImport($gedcomContent, $mediaFiles);
        } catch (Exception $e) {
            Log::error('ZIP import failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error'   => 'ZIP extraction failed: ' . $e->getMessage(),
            ];
        } finally {
            $zipImporter->cleanup();
        }
    }

    /**
     * Get import statistics
     */
    public function getStatistics(): array
    {
        $parsedData = $this->parser->getParsedData();

        $stats = [
            'individuals_parsed'   => $parsedData ? count($parsedData->getIndividuals()) : 0,
            'families_parsed'      => $parsedData ? count($parsedData->getFamilies()) : 0,
            'individuals_imported' => count($this->individualImporter->getPersonMap()),
            'families_imported'    => count($this->familyImporter->getFamilyMap()),
        ];

        if ($this->mediaHandler) {
            $stats['media_references'] = count($this->mediaHandler->getPersonMediaMap());
        }

        return $stats;
    }

    /**
     * Core import processing logic
     *
     * @param  string  $gedcomContent  GEDCOM text content
     * @param  array  $mediaFiles  Array of basename => filepath for media
     * @return array Import results
     */
    private function processImport(string $gedcomContent, array $mediaFiles): array
    {
        // Increase limits for large imports
        ini_set('max_execution_time', 300); // 5 minutes
        ini_set('memory_limit', '512M');

        try {
            DB::beginTransaction();

            // Initialize media handler if we have media files
            if (! empty($mediaFiles)) {
                $this->mediaHandler = new MediaImportHandler($mediaFiles);

                // Parse media objects from GEDCOM content BEFORE parsing individuals
                $this->mediaHandler->parseMediaObjects($gedcomContent);

                Log::info('Media handler initialized', [
                    'files_count'         => count($mediaFiles),
                    'media_objects_count' => count($this->mediaHandler->getMediaObjects()),
                ]);
            }

            // Parse GEDCOM content
            $parsedData = $this->parser->parse($gedcomContent);

            Log::info('GEDCOM parsed', [
                'individuals' => count($parsedData->getIndividuals()),
                'families'    => count($parsedData->getFamilies()),
            ]);

            // Import individuals first
            $personMap = $this->individualImporter->import(
                $parsedData->getIndividuals(),
                $this->mediaHandler
            );

            Log::info('Individuals imported', [
                'count' => count($personMap),
            ]);

            // Import families and relationships
            $familyMap = $this->familyImporter->import(
                $parsedData->getFamilies(),
                $personMap
            );

            Log::info('Families imported', [
                'count' => count($familyMap),
            ]);

            // Create couples from families
            $this->coupleCreator->create($familyMap, $personMap);

            Log::info('Couples created');

            // Import media files if available
            $mediaStats = null;
            if ($this->mediaHandler) {
                Log::info('Starting media import');
                $mediaStats = $this->mediaHandler->importMediaToPersons($personMap);
                Log::info('Media import complete', $mediaStats);
            }

            DB::commit();

            $result = [
                'success'              => true,
                'team'                 => $this->team->name,
                'individuals_imported' => count($personMap),
                'families_imported'    => count($familyMap),
                'message'              => 'GEDCOM file imported successfully',
            ];

            if ($mediaStats) {
                $result['media_stats'] = $mediaStats;
            }

            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('GEDCOM Import Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Create a new team for the import
     */
    private function createTeam(string $name, ?string $description): Team
    {
        AddingTeam::dispatch($this->user);

        /** @var Team $team */
        $team = $this->user->ownedTeams()->create([
            'name'          => $name,
            'description'   => $description ?? null,
            'personal_team' => false,
        ]);

        $this->user->switchTeam($team);

        // Create team photo folder
        if (! Storage::disk('photos')->exists($team->id)) {
            Storage::disk('photos')->makeDirectory($team->id);
        }

        return $team;
    }
}
