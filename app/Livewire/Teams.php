<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Teams extends Component
{
    public $user = null;

    public function mount()
    {
        $this->user = User::with(['teams.users', 'teams.persons', 'teams.couples'])->find(auth()->user()->id);
    }

    public function render()
    {
        return view('livewire.teams');
    }
}
