<?php

namespace App\Livewire\Gedcom;

use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
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

    // -----------------------------------------------------------------------
    public function rules()
    {
        return $rules = [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'file'        => ['required', 'file', 'mimes:ged'],
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
            'file'        => __('team.gedcom_file'),
        ];
    }

    public function mount(): void
    {
        $this->user        = Auth()->user();
        $this->name        = null;
        $this->description = null;
        $this->file        = null;
    }

    public function importTeam()
    {
        //        if ($this->isDirty()) {
        $validated = $this->validate();

        if (isset($validated['file'])) {
            $this->file = $validated['file'];
        }

        dump('test');

        if ($this->file) {
            $parser = new \PhpGedcom\Parser();
            $gedcom = $parser->parse($this->file);

            foreach ($gedcom->getIndi() as $individual) {
                echo $individual->getId() . ': ' . current($individual->getName())->getSurn();
            }
        }

        $this->toast()->success(__('app.create'), $this->file)->flash()->send();

        // return $this->redirect('/search');
        //        }
    }

    public function resetTeam(): void
    {
        $this->mount();
    }

    public function isDirty(): bool
    {
        return
        $this->name != null or
        $this->description != null or
        $this->file != null;
    }

    // -----------------------------------------------------------------------
    public function render()
    {
        return view('livewire.gedcom.import');
    }
}
