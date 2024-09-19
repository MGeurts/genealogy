<?php

declare(strict_types=1);

namespace App\Livewire\Gedcom;

use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
// use Laravel\Jetstream\Events\AddingTeam;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Import extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $user = null;

    public ?string $name = null;

    public ?string $description = null;

    public ?TemporaryUploadedFile $file = null;

    public ?string $output = null;

    // -----------------------------------------------------------------------
    public function rules(): array
    {
        return $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'file'        => ['required', 'file'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function validationAttributes(): array
    {
        return [
            'name'        => __('team.name'),
            'description' => __('team.description'),
            'file'        => __('gedcom.gedcom_file'),
        ];
    }

    // -----------------------------------------------------------------------
    public function mount(): void
    {
        $this->user = Auth()->user();
    }

    public function importTeam(): void
    {
        // -----------------------------------------------------------------------
        // validate input
        // $input = $this->validate();

        // AddingTeam::dispatch($this->user);

        // create and switch team
        // $this->user->switchTeam($team = $this->user->ownedTeams()->create([
        //     'name'          => $input['name'],
        //     'description'   => $input['description'] ?? null,
        //     'personal_team' => false,
        // ]));

        // -----------------------------------------------------------------------
        //if ($this->file) {
        //    $this->file->storeAs(path: 'public/imports', name: $this->file->getClientOriginalName());

        $parser = new \Gedcom\Parser;

        //$gedcom = $parser->parse(asset('storage/imports/' . $this->file->getClientOriginalName()));
        $gedcom = $parser->parse('storage/imports/demo.ged');

        $this->stream(to: 'stream', content: '<br/><div>Processing ...</div>', replace: false);
        $this->output .= '<br/><div>Processing ...</div>';

        $count_indi = $count_fam = 0;

        foreach ($gedcom->getIndi() as $individual) {
            $names = $individual->getName();

            if (! empty($names)) {
                $name = reset($names); // Get the first name object from the array

                $line = '<div>' . $individual->getId() . ' : ' . $name->getSurn() . ', ' . $name->getGivn() . '</div>';
                $this->stream(to: 'stream', content: $line);
                $this->output .= $line;

                $count_indi++;
            }

            usleep(250);
        }
        // -----------------------------------------------------------------------
        $this->output .= '<br/><div>Done.</div><div>Imported ' . $count_indi . ' individuals.</div><div>Imported ' . $count_fam . ' families.</div>';

        $this->toast()->success(__('app.saved'), 'Done.')->send();
        //}
    }

    // -----------------------------------------------------------------------
    public function render(): View
    {
        return view('livewire.gedcom.import');
    }
}
