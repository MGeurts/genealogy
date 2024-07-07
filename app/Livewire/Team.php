<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Team extends Component
{
    public $user = null;

    public function mount()
    {
        $this->user = User::with([
            'currentTeam.users',
            'currentTeam.persons',
            'currentTeam.couples',
            'currentTeam.couples.person_1',
            'currentTeam.couples.person_2'
        ])->find(auth()->user()->id);
    }

    public function render()
    {
        return view('livewire.team');
    }
}
