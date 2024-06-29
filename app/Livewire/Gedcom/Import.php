<?php

declare(strict_types=1);

namespace App\Livewire\Gedcom;

use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Laravel\Jetstream\Events\AddingTeam;
use Livewire\Component;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;

class Import extends Component
{
    use Interactions;
    use TrimStringsAndConvertEmptyStringsToNull;
    use WithFileUploads;

    // -----------------------------------------------------------------------
    public $user = null;

    public $name = null;

    public $description = null;

    public $file = null;

    public $output = '<div>Awaiting input ...</div>';

    // -----------------------------------------------------------------------
    public function rules()
    {
        return $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'file'        => ['required', 'file', 'required'],
        ];
    }

    public function messages()
    {
        return [];
    }

    public function validationAttributes()
    {
        return [
            'name'        => __('team.name'),
            'description' => __('team.description'),
            'file'        => __('gedcom.gedcom_file'),
        ];
    }

    public function mount(): void
    {
        $this->user = Auth()->user();
    }

    public function importTeam(): void
    {
        // validate input
        $input = $this->validate();

        AddingTeam::dispatch($this->user);

        // create and switch team
        $this->user->switchTeam($team = $this->user->ownedTeams()->create([
            'name'          => $input['name'],
            'description'   => $input['description'] ?? null,
            'personal_team' => false,
        ]));

        if ($this->file) {
            $this->file->storeAs(path: 'public/imports', name: $this->file->getClientOriginalName());

            $parser = new \Gedcom\Parser();

            //$gedcom = $parser->parse('./gedcom/royals_nl.ged');
            $gedcom = $parser->parse(asset('storage/imports/' . $this->file->getClientOriginalName()));

            $this->stream(to: 'output', content: '<div>Processing ...</div>', replace: true); 

            $count_indi = $count_fam= 0;
            
            foreach ($gedcom->getIndi() as $individual) {
                $names = $individual->getName();

                if (! empty($names)) {
                    $name = reset($names); // Get the first name object from the array

                    $line = '<div>' . $individual->getId() . ' : ' . $name->getSurn() . ', ' . $name->getGivn() . '</div>';
                    $this->stream(to: 'output', content: $line);

                    $count_indi++;
                }

                usleep(100);
            }

            $this->output = '<div>Done.</div><div>Imported ' . $count_indi . ' individuals.</div><div>Imported ' . $count_fam . ' families.</div>'; 

            $this->toast()->success(__('app.saved'), 'Done.')->send();
        }
    }

    // -----------------------------------------------------------------------
    public function render()
    {
        return view('livewire.gedcom.import');
    }
}
