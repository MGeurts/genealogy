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
            'description' => ['required', 'string', 'max:255'],
            'file'        => ['required', 'file', 'mimes:ged', 'max:1024'],
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
        $this->user = Auth()->user();
    }

    public function createTeam()
    {
        if ($this->isDirty()) {
            $validated = $this->validate();

            if ($this->personForm->image) {
            }

            $this->toast()->success(__('app.create'), __('app.created'))->flash()->send();

            return $this->redirect('/search');
        }
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
