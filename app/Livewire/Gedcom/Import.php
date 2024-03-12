<?php

namespace App\Livewire\Gedcom;

use App\Livewire\Traits\TrimStringsAndConvertEmptyStringsToNull;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Usernotnull\Toast\Concerns\WireToast;

class Import extends Component
{
    use WithFileUploads;
    use WireToast;
    use TrimStringsAndConvertEmptyStringsToNull;

    public $user = null;

    public $name = null;
    public $description = null;
    public $file = null;

    public function rules()
    {
        return $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:ged', 'max:1024'],
        ];
    }

    public function messages()
    {
        return [];
    }

    public function validationAttributes()
    {
        return [
            'name' => __('team.name'),
            'description' => __('team.description'),
            'file' => __('team.gedcom_file'),
        ];
    }

    public function mount()
    {
        $this->user = Auth()->user();
    }

    public function createTeam()
    {
        if ($this->isDirty()) {
            $validated = $this->validate();

            if ($this->personForm->image) {

            }

            toast()->success(__('app.created') . '.', __('app.save'))->pushOnNextPage();
            $this->redirect('/search/');
        }
    }

    public function resetTeam()
    {
        $this->mount();
    }

    public function isDirty()
    {
        return
        $this->name != null or
        $this->description != null or
        $this->file != null;
    }

    public function render()
    {
        return view('livewire.gedcom.import');
    }
}
